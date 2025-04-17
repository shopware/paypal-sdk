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
use Shopware\PayPalSDK\Gateway\PaymentV1Gateway;
use Shopware\PayPalSDK\Struct\V1\Capture;
use Shopware\PayPalSDK\Struct\V1\Payment;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Authorization;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Order;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Sale;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;

/**
 * @internal
 */
#[CoversClass(PaymentV1Gateway::class)]
class PaymentV1GatewayTest extends TestCase
{
    protected TestClient $client;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);
    }

    public function testGetAuthorization(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Authorization())->assign(['id' => 'autorization-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentV1Gateway()->getAuthorization('autorizationId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/payments/authorization/autorizationId', $last->getRequest()->getUri()->getPath());
    }

    public function testGetCapture(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Capture())->assign(['id' => 'capture-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentV1Gateway()->getCapture('captureId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/payments/capture/captureId', $last->getRequest()->getUri()->getPath());
    }

    public function testGetOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'order-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentV1Gateway()->getOrder('orderId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/payments/orders/orderId', $last->getRequest()->getUri()->getPath());
    }

    public function testGetPayment(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Payment())->assign(['id' => 'payment-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentV1Gateway()->getPayment('paymentId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/payments/payment/paymentId', $last->getRequest()->getUri()->getPath());
    }

    public function testGetSale(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Sale())->assign(['id' => 'sale-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->paymentV1Gateway()->getSale('saleId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/payments/sale/saleId', $last->getRequest()->getUri()->getPath());
    }
}
