<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\V1\Token;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;
use Shopware\PayPalSDK\Util\TokenArrayCache;

/**
 * @internal
 */
#[CoversClass(TestGateways::class)]
class TestGatewaysTest extends TestCase
{
    protected TestClient $client;

    protected RequestService $requestService;

    protected TokenArrayCache $tokenCache;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->requestService = new RequestService();
        $this->tokenCache = new TokenArrayCache();
        $this->gateways = new TestGateways($this->client, $this->requestService, $this->tokenCache);
    }

    public function testSetCachedToken(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);
        $cacheKey = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($cacheKey);

        $this->gateways->setCachedToken($context);

        static::assertNotNull($this->tokenCache->get($cacheKey));

        $token = new Token();
        $this->gateways->setCachedToken($context, $token);

        static::assertSame($token, $this->tokenCache->get($cacheKey));
    }

    public function testGetTokenCache(): void
    {
        static::assertSame($this->tokenCache, $this->gateways->getTokenCache());
    }

    public function testTokenGateway(): void
    {
        $gateway = $this->gateways->tokenGateway();
        $reflection = new \ReflectionClass($gateway);

        static::assertSame($this->client, $reflection->getProperty('client')->getValue($gateway));
        static::assertSame($this->requestService, $reflection->getProperty('requestService')->getValue($gateway));
        static::assertSame($this->tokenCache, $reflection->getProperty('tokenCache')->getValue($gateway));
    }

    public function testCustomerGateway(): void
    {
        $gateway = $this->gateways->customerGateway();
        $reflection = new \ReflectionClass($gateway);

        static::assertSame($this->client, $reflection->getProperty('client')->getValue($gateway));
        static::assertSame($this->requestService, $reflection->getProperty('requestService')->getValue($gateway));
    }

    public function testOrderGateway(): void
    {
        $gateway = $this->gateways->orderGateway();
        $reflection = new \ReflectionClass($gateway);

        static::assertSame($this->client, $reflection->getProperty('client')->getValue($gateway));
        static::assertSame($this->requestService, $reflection->getProperty('requestService')->getValue($gateway));
    }

    public function testPaymentGateway(): void
    {
        $gateway = $this->gateways->paymentGateway();
        $reflection = new \ReflectionClass($gateway);

        static::assertSame($this->client, $reflection->getProperty('client')->getValue($gateway));
        static::assertSame($this->requestService, $reflection->getProperty('requestService')->getValue($gateway));
    }

    public function testPaymentV1Gateway(): void
    {
        $gateway = $this->gateways->paymentV1Gateway();
        $reflection = new \ReflectionClass($gateway);

        static::assertSame($this->client, $reflection->getProperty('client')->getValue($gateway));
        static::assertSame($this->requestService, $reflection->getProperty('requestService')->getValue($gateway));
    }

    public function testReportingGateway(): void
    {
        $gateway = $this->gateways->reportingGateway();
        $reflection = new \ReflectionClass($gateway);

        static::assertSame($this->client, $reflection->getProperty('client')->getValue($gateway));
        static::assertSame($this->requestService, $reflection->getProperty('requestService')->getValue($gateway));
    }

    public function testWebhookGateway(): void
    {
        $gateway = $this->gateways->webhookGateway();
        $reflection = new \ReflectionClass($gateway);

        static::assertSame($this->client, $reflection->getProperty('client')->getValue($gateway));
        static::assertSame($this->requestService, $reflection->getProperty('requestService')->getValue($gateway));
    }
}
