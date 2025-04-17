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
use Shopware\PayPalSDK\Gateway\OrderGateway;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\Tracker;
use Shopware\PayPalSDK\Struct\V2\PatchCollection;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;

/**
 * @internal
 */
#[CoversClass(OrderGateway::class)]
class OrderGatewayTest extends TestCase
{
    protected TestClient $client;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);
    }

    public function testCreateOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->orderGateway()->createOrder($body, $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $last->getRequest()->getBody());
    }

    public function testGetOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->orderGateway()->getOrder('orderId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId', $last->getRequest()->getUri()->getPath());
    }

    public function testAuthorizeOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->orderGateway()->authorizeOrder('orderId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId/authorize', $last->getRequest()->getUri()->getPath());
        static::assertSame('', (string) $last->getRequest()->getBody());
    }

    public function testCaptureOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Order())->assign(['id' => 'some-order-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->orderGateway()->captureOrder('orderId', $context);
        static::assertEquals($body, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId/capture', $last->getRequest()->getUri()->getPath());
        static::assertSame('', (string) $last->getRequest()->getBody());
    }

    public function testPatchOrder(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = PatchCollection::createFromAssociative([[
            'op' => 'REPLACE',
            'path' => '/some/path',
        ]]);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response());

        $this->gateways->orderGateway()->patchOrder('orderId', $body, $context);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('PATCH', $last->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($body), (string) $last->getRequest()->getBody());
    }

    public function testAddTracker(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = (new Tracker())->assign(['tracking_number' => '1000']);
        $order = (new Order())->assign(['id' => 'some-order-id']);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($order, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->orderGateway()->addTracker($body, 'orderId', $context);
        static::assertEquals($order, $response);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId/track', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($body, \JSON_THROW_ON_ERROR), (string) $last->getRequest()->getBody());
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

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $this->gateways->orderGateway()->removeTracker($tracker, 'orderId', $context);

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertEquals(\json_encode($patch), $last->getRequest()->getBody());
        static::assertSame('PATCH', $last->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/orderId/trackers/some-capture-id-1000', $last->getRequest()->getUri()->getPath());
        static::assertSame(\json_encode($patch), (string) $last->getRequest()->getBody());
    }
}
