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
use Shopware\PayPalSDK\Gateway\OrderGateway;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\Tracker;
use Shopware\PayPalSDK\Struct\V2\PatchCollection;

/**
 * @internal
 *
 * @extends AbstractGatewayTestCase<OrderGateway>
 */
#[CoversClass(OrderGateway::class)]
class OrderGatewayTest extends AbstractGatewayTestCase
{
    protected OrderGateway $gateway;

    protected function setUp(): void
    {
        $this->gateway = new OrderGateway($this->client, $this->tokenGateway);
    }

    protected function gatewayClass(): string
    {
        return OrderGateway::class;
    }

    public function testCreateOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->createOrder($body, $context);
        static::assertEquals($body, $response);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v2/checkout/orders', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $this->getLast()->getBody());
    }

    public function testGetOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->getOrder('orderId', $context);
        static::assertEquals($body, $response);
        static::assertSame('GET', $this->getLast()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId', $this->getLast()->getUri()->getPath());
    }

    public function testAuthorizeOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->authorizeOrder('orderId', $context);
        static::assertEquals($body, $response);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId/authorize', $this->getLast()->getUri()->getPath());
        static::assertSame('', (string) $this->getLast()->getBody());
    }

    public function testCaptureOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $response = $this->gateway->captureOrder('orderId', $context);
        static::assertEquals($body, $response);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId/capture', $this->getLast()->getUri()->getPath());
        static::assertSame('', (string) $this->getLast()->getBody());
    }

    public function testPatchOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = PatchCollection::createFromAssociative([[
            'op' => 'REPLACE',
            'path' => '/some/path',
        ]]);

        $this->setCachedToken($context, $this->getValidToken());
        $this->handler->append(new Response());

        $this->gateway->patchOrder('orderId', $body, $context);
        static::assertSame('PATCH', $this->getLast()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $this->getLast()->getBody());
    }

    public function testAddTracker(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Tracker())->assign(['tracking_number' => '1000']);
        $order = (new Order())->assign(['id' => 'some-order-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($order);

        $response = $this->gateway->addTracker($body, 'orderId', $context);
        static::assertEquals($order, $response);
        static::assertSame('POST', $this->getLast()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId/track', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $this->getLast()->getBody());
    }

    public function testRemoveTracker(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);
        $patch = PatchCollection::createFromAssociative([[
            'op' => 'replace',
            'path' => '/status',
            'value' => Order\PurchaseUnit\Shipping\Tracker::STATUS_CANCELLED,
        ]]);
        $tracker = (new Tracker())->assign(['tracking_number' => '1000', 'capture_id' => 'some-capture-id']);

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse($body);

        $this->gateway->removeTracker($tracker, 'orderId', $context);
        static::assertEquals(\json_encode($patch), $this->getLast()->getBody());
        static::assertSame('PATCH', $this->getLast()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId/trackers/some-capture-id-1000', $this->getLast()->getUri()->getPath());
        static::assertSame(\json_encode($patch), (string) $this->getLast()->getBody());
    }
}
