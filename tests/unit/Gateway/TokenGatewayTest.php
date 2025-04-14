<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle7\Client as Guzzle7Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Gateway\TokenGateway;
use Shopware\PayPalSDK\Struct\V1\Token;
use Shopware\PayPalSDK\Util\TokenArrayCache;

/**
 * @internal
 */
#[CoversClass(TokenGateway::class)]
class TokenGatewayTest extends TestCase
{
    protected MockHandler $handler;

    protected Client $client;

    protected TokenArrayCache $cache;

    protected TokenGateway $gateway;

    protected function setUp(): void
    {
        $this->handler = new MockHandler();
        $this->client = new Client(['handler' => HandlerStack::create($this->handler)]);
        $this->cache = new TokenArrayCache();
        $this->gateway = new TokenGateway(new Guzzle7Client($this->client), $this->cache);
    }

    public function testGetToken(): void
    {
        $this->handler->append(new Response(200, [], \json_encode([
            'access_token' => 'some-cached-access-token',
            'expires_in' => 36000,
        ]) ?: null));

        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        $token = $this->gateway->getToken($context);
        static::assertSame('some-cached-access-token', $token->getAccessToken());

        $request = $this->handler->getLastRequest();
        static::assertNotNull($request);

        static::assertSame('POST', $request->getMethod());
        static::assertSame('grant_type=client_credentials', (string) $request->getBody());
        static::assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('content-type'));
        static::assertSame('Basic Y2xpZW50LWlkOmNsaWVudC1zZWNyZXQ=', $request->getHeaderLine('Authorization'));

        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        static::assertSame($token, $this->cache->get($key));
    }

    public function testGetTokenWithoutResponse(): void
    {
        $this->handler->append(new Response(200));

        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        static::expectException(ApiException::class);
        static::expectExceptionMessage('The error "UNKNOWN" occurred with the following message: OK.');

        $this->gateway->getToken($context);
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
        $this->cache->set($key, $token, $token->getExpiresIn());

        static::assertSame($token, $this->gateway->getToken($context));
    }
}
