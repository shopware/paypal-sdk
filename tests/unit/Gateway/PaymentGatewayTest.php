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
use Shopware\PayPalSDK\Gateway\PaymentGateway;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Authorization;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Refund;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;

/**
 * @internal
 */
#[CoversClass(PaymentGateway::class)]
class PaymentGatewayTest extends TestCase
{
    protected TestClient $client;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);
    }

    public function testGetCapture(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Capture())->assign(['id' => 'capture-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentGateway()->getCapture('captureId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v2/payments/captures/captureId', $last->getRequest()->getUri()->getPath());
    }

    public function testGetAuthorization(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Authorization())->assign(['id' => 'authorization-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentGateway()->getAuthorization('authorizationId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v2/payments/authorizations/authorizationId', $last->getRequest()->getUri()->getPath());
    }

    public function testGetRefund(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Refund())->assign(['id' => 'refund-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentGateway()->getRefund('refundId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v2/payments/refunds/refundId', $last->getRequest()->getUri()->getPath());
    }

    public function testRefundCapture(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Refund())->assign(['id' => 'refund-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentGateway()->refundCapture('captureId', $body, $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/payments/captures/captureId/refund', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $last->getRequest()->getBody());
    }

    public function testCaptureAuthorization(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Capture())->assign(['id' => 'capture-id']);

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentGateway()->captureAuthorization('authorizationId', $body, $context);
        static::assertEquals($body, $response);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/payments/authorizations/authorizationId/capture', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $last->getRequest()->getBody());
    }

    public function testVoidAuthorization(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');

        $this->gateways->setCachedToken($context);
        $this->client->add(new Response());

        $this->gateways->paymentGateway()->voidAuthorization('authorizationId', $context);

        $last = $this->client->last();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/payments/authorizations/authorizationId/void', $last->getRequest()->getUri()->getPath());
        static::assertSame('', (string) $last->getRequest()->getBody());
    }
}
