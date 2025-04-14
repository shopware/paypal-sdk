<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\PaymentGateway;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Authorization;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Refund;

/**
 * @internal
 *
 * @extends AbstractGatewayTestCase<PaymentGateway>
 */
#[CoversClass(PaymentGateway::class)]
class PaymentGatewayTest extends AbstractGatewayTestCase
{
    protected PaymentGateway $gateway;

    protected function setUp(): void
    {
        $this->gateway = new PaymentGateway($this->client, $this->tokenGateway);
    }

    protected function gatewayClass(): string
    {
        return PaymentGateway::class;
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
        static::assertSame('/v2/payments/captures/captureId', $this->getLast()->getUri()->getPath());
    }

    public function testGetAuthorization(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Authorization())->assign(['id' => 'authorization-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getAuthorization('authorizationId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v2/payments/authorizations/authorizationId', $this->getLast()->getUri()->getPath());
    }

    public function testGetRefund(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Refund())->assign(['id' => 'refund-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getRefund('refundId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v2/payments/refunds/refundId', $this->getLast()->getUri()->getPath());
    }

    public function testRefundCapture(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Refund())->assign(['id' => 'refund-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->refundCapture('captureId', $body, $context);
        static::assertEquals($body, $response);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v2/payments/captures/captureId/refund', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $this->getLast()->getBody());
    }

    public function testCaptureAuthorization(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Capture())->assign(['id' => 'capture-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->captureAuthorization('authorizationId', $body, $context);
        static::assertEquals($body, $response);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v2/payments/authorizations/authorizationId/capture', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $this->getLast()->getBody());
    }

    public function testVoidAuthorization(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');

        $this->setCachedToken($context, $this->getValidToken());
        $this->handler->append(new Response());

        $this->gateway->voidAuthorization('authorizationId', $context);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v2/payments/authorizations/authorizationId/void', $this->getLast()->getUri()->getPath());
        static::assertSame('', (string) $this->getLast()->getBody());
    }
}
