<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use PHPUnit\Framework\Attributes\CoversClass;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\PaymentV1Gateway;
use Shopware\PayPalSDK\Struct\V1\Capture;
use Shopware\PayPalSDK\Struct\V1\Payment;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Authorization;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Order;
use Shopware\PayPalSDK\Struct\V1\Payment\Transaction\RelatedResource\Sale;

/**
 * @internal
 *
 * @extends AbstractGatewayTestCase<PaymentV1Gateway>
 */
#[CoversClass(PaymentV1Gateway::class)]
class PaymentV1GatewayTest extends AbstractGatewayTestCase
{
    protected PaymentV1Gateway $gateway;

    protected function setUp(): void
    {
        $this->gateway = new PaymentV1Gateway($this->client, $this->tokenGateway);
    }

    protected function gatewayClass(): string
    {
        return PaymentV1Gateway::class;
    }

    public function testGetAuthorization(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Authorization())->assign(['id' => 'autorization-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getAuthorization('autorizationId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/payments/authorization/autorizationId', $this->getLast()->getUri()->getPath());
    }

    public function testGetCapture(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Capture())->assign(['id' => 'capture-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getCapture('captureId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/payments/capture/captureId', $this->getLast()->getUri()->getPath());
    }

    public function testGetOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'order-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getOrder('orderId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/payments/orders/orderId', $this->getLast()->getUri()->getPath());
    }

    public function testGetPayment(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Payment())->assign(['id' => 'payment-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getPayment('paymentId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/payments/payment/paymentId', $this->getLast()->getUri()->getPath());
    }

    public function testGetSale(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Sale())->assign(['id' => 'sale-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getSale('saleId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v1/payments/sale/saleId', $this->getLast()->getUri()->getPath());
    }
}
