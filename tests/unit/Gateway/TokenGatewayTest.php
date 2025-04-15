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
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Exception\ApiException;
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
        $this->client->add(new Response(200, [], \json_encode([
            'access_token' => 'some-cached-access-token',
            'expires_in' => 36000,
        ]) ?: null));

        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        $token = $this->gateways->tokenGateway()->getToken($context);
        static::assertSame('some-cached-access-token', $token->getAccessToken());

        $request = $this->client->last()?->getRequest();
        static::assertNotNull($request);

        static::assertSame('POST', $request->getMethod());
        static::assertSame('grant_type=client_credentials', (string) $request->getBody());
        static::assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('content-type'));
        static::assertSame('Basic Y2xpZW50LWlkOmNsaWVudC1zZWNyZXQ=', $request->getHeaderLine('Authorization'));

        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        static::assertSame($token, $this->gateways->getTokenCache()->get($key));
    }

    public function testGetTokenWithoutResponse(): void
    {
        $this->client->add(new Response(200));

        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        static::expectException(ApiException::class);
        static::expectExceptionMessage('The error "UNKNOWN" occurred with the following message: OK.');

        $this->gateways->tokenGateway()->getToken($context);
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

        static::assertSame($token, $this->gateways->tokenGateway()->getToken($context));
    }
}
