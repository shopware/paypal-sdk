<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Contract\Context\OAuthContextInterface;
use Shopware\PayPalSDK\Gateway\TokenGateway;
use Shopware\PayPalSDK\Struct\V1\Token;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;

/**
 * @internal
 */
#[CoversClass(TokenGateway::class)]
class TokenGatewayTest extends TestCase
{
    protected TestClient $client;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);
    }

    public function testGetToken(): void
    {
        $this->client->addResponse(new Response(200, [], \json_encode([
            'access_token' => 'some-cached-access-token',
            'expires_in' => 36000,
        ]) ?: null));

        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        $token = $this->gateways->tokenGateway()->getToken($context);
        static::assertSame('some-cached-access-token', $token->getAccessToken());

        $request = $this->client->getLast()?->getRequest();
        static::assertNotNull($request);

        static::assertSame('POST', $request->getMethod());
        static::assertSame('return=representation', $request->getHeaderLine('Prefer'));
        static::assertSame('grant_type=client_credentials', (string) $request->getBody());
        static::assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('content-type'));
        static::assertSame('Basic Y2xpZW50LWlkOmNsaWVudC1zZWNyZXQ=', $request->getHeaderLine('Authorization'));

        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        static::assertSame($token, $this->gateways->getTokenCache()->get($key));
    }

    public function testGetTokenWithoutResponse(): void
    {
        $this->client->addResponse(new Response(200));

        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        static::expectException(\LogicException::class);
        static::expectExceptionMessage('Expected response content for deserializing into ' . Token::class);

        $this->gateways->tokenGateway()->getToken($context);
    }

    public function testPersistentCacheStoresAndReusesTokens(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);
        $cacheKey = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($cacheKey);

        /** @var array<string, string> $entries */
        $entries = [];
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects(static::exactly(2))->method('get')->with($cacheKey)->willReturnCallback(
            static function (string $key) use (&$entries): mixed {
                return isset($entries[$key]) ? \unserialize($entries[$key]) : null;
            },
        );
        $cache->expects(static::once())->method('set')->willReturnCallback(
            static function (string $key, Token $token, int $ttl) use (&$entries, $cacheKey): bool {
                static::assertSame($cacheKey, $key);
                static::assertSame(3420, $ttl);
                $entries[$key] = \serialize($token);

                return true;
            },
        );
        $cache->expects(static::once())->method('delete')->with($cacheKey)->willReturn(true);
        $this->client->addResponse(new Response(200, [], '{"access_token":"new-token","expires_in":3600}'));
        $gateway = new TokenGateway($this->client, $cache);

        static::assertFalse($gateway->getToken($context)->isCached());
        $cachedToken = $gateway->getToken($context);
        static::assertSame('new-token', $cachedToken->getAccessToken());
        static::assertTrue($cachedToken->isCached());
        static::assertCount(1, $this->client->getAll());
        static::assertCount(1, $entries);
    }

    public function testNonCacheableContextNeverAccessesCache(): void
    {
        $oauthContext = $this->createMock(OAuthContextInterface::class);
        $oauthContext->method('getCacheKey')->willReturn(null);
        $oauthContext->method('getBody')->willReturn(['grant_type' => 'client_credentials']);
        $context = new ApiContext($oauthContext, true);
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects(static::never())->method('get');
        $cache->expects(static::never())->method('set');
        $cache->expects(static::never())->method('delete');
        $this->client->addResponse(new Response(200, [], '{"access_token":"first-token","expires_in":3600}'));
        $this->client->addResponse(new Response(200, [], '{"access_token":"second-token","expires_in":3600}'));
        $gateway = new TokenGateway($this->client, $cache);

        static::assertSame('first-token', $gateway->getToken($context)->getAccessToken());
        static::assertSame('second-token', $gateway->getToken($context)->getAccessToken());
        static::assertCount(2, $this->client->getAll());
    }

    public function testGetCachedToken(): void
    {
        $token = (new Token())->assign([
            'access_token' => 'some-cached-access-token',
            'expires_in' => 36000,
        ]);
        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        $this->gateways->getTokenCache()->set($key, $token, $token->getExpiresIn());

        static::assertSame('some-cached-access-token', $this->gateways->tokenGateway()->getToken($context)->getAccessToken());
    }

    public function testFreshTokenIsNotMarkedAsCached(): void
    {
        $this->client->addResponse(new Response(200, [], \json_encode([
            'access_token' => 'some-fresh-access-token',
            'expires_in' => 36000,
        ]) ?: null));

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $token = $this->gateways->tokenGateway()->getToken($context);

        static::assertFalse($token->isCached());
    }

    public function testCachedTokenIsMarkedAsCached(): void
    {
        $token = (new Token())->assign([
            'access_token' => 'some-cached-access-token',
            'expires_in' => 36000,
        ]);
        static::assertFalse($token->isCached());

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        $this->gateways->getTokenCache()->set($key, $token, $token->getExpiresIn());

        $cachedToken = $this->gateways->tokenGateway()->getToken($context);

        static::assertSame('some-cached-access-token', $cachedToken->getAccessToken());
        static::assertTrue($cachedToken->isCached());
        static::assertCount(0, $this->client->getAll(), 'A cached token must not trigger a request.');

        static::assertFalse($token->isCached(), 'The instance kept in the cache must not be mutated.');
    }

    public function testCachedTokenDoesNotMutateAlreadyReturnedInstances(): void
    {
        $this->client->addResponse(new Response(200, [], \json_encode([
            'access_token' => 'some-access-token',
            'expires_in' => 36000,
        ]) ?: null));

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);
        $tokenGateway = $this->gateways->tokenGateway();

        $fresh = $tokenGateway->getToken($context);
        static::assertFalse($fresh->isCached());

        $fromCache = $tokenGateway->getToken($context);
        static::assertTrue($fromCache->isCached());

        static::assertNotSame($fresh, $fromCache, 'Each call must hand out an independent instance.');
        static::assertFalse(
            $fresh->isCached(),
            'A token handed out earlier must not retroactively become cached.',
        );
    }

    public function testGetTokenWithRefreshBypassesCache(): void
    {
        $cachedToken = (new Token())->assign([
            'access_token' => 'some-cached-access-token',
            'expires_in' => 36000,
        ]);

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        $this->gateways->getTokenCache()->set($key, $cachedToken, $cachedToken->getExpiresIn());

        $this->client->addResponse(new Response(200, [], \json_encode([
            'access_token' => 'some-refreshed-access-token',
            'expires_in' => 36000,
        ]) ?: null));

        $token = $this->gateways->tokenGateway()->getToken($context, refresh: true);

        static::assertSame('some-refreshed-access-token', $token->getAccessToken());
        static::assertFalse($token->isCached());
        static::assertCount(1, $this->client->getAll());

        static::assertSame($token, $this->gateways->getTokenCache()->get($key), 'The refreshed token must replace the cached one.');
    }

    public function testGetTokenWithRefreshFalseUsesCache(): void
    {
        $cachedToken = (new Token())->assign([
            'access_token' => 'some-cached-access-token',
            'expires_in' => 36000,
        ]);

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        $this->gateways->getTokenCache()->set($key, $cachedToken, $cachedToken->getExpiresIn());

        $token = $this->gateways->tokenGateway()->getToken($context, refresh: false);

        static::assertSame('some-cached-access-token', $token->getAccessToken());
        static::assertCount(0, $this->client->getAll());
    }

    public function testGetTokenWithExpiredCachedToken(): void
    {
        $expiredToken = (new Token())->assign([
            'access_token' => 'some-expired-access-token',
            'expires_in' => 36000,
        ]);
        $expiredToken->setExpireDateTime(new \DateTimeImmutable('now -1 hour', new \DateTimeZone('UTC')));

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        $this->gateways->getTokenCache()->set($key, $expiredToken, 36000);

        $this->client->addResponse(new Response(200, [], \json_encode([
            'access_token' => 'some-new-access-token',
            'expires_in' => 36000,
        ]) ?: null));

        $token = $this->gateways->tokenGateway()->getToken($context);

        static::assertSame('some-new-access-token', $token->getAccessToken());
        static::assertFalse($token->isCached());
        static::assertCount(1, $this->client->getAll(), 'An expired cached token must trigger a new request.');
    }
}
