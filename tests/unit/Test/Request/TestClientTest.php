<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Test\Request;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\OrderGateway;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\PatchCollection;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;
use Shopware\PayPalSDK\Test\Request\TestRequestContext;

/**
 * @internal
 */
#[CoversClass(TestClient::class)]
class TestClientTest extends TestCase
{
    protected TestClient $client;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient(handler: $this->orderGatewayHandler(...));
        $this->gateways = new TestGateways($this->client);
    }

    public function testRequestContext(): void
    {
        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );
        $this->gateways->setCachedToken($context);

        $order = (new Order())->assign(['id' => 'create-order-id']);

        $this->gateways->orderGateway()->createOrder($order, $context);

        $last = $this->client->getLast();
        static::assertNotNull($last);

        static::assertSame(OrderGateway::class, $last->getGatewayClass());
        static::assertSame('createOrder', $last->getGatewayMethod());
        static::assertSame($context, $last->getContext());
        static::assertEquals($order->jsonSerialize(), $last->getRequestBody());
        static::assertSame('/v2/checkout/orders', $last->getRequest()->getUri()->getPath());
        static::assertSame('POST', $last->getRequest()->getMethod());
    }

    public function testRequest(): void
    {
        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        $order = (new Order())->assign(['id' => 'get-order-id']);

        $this->client = new TestClient([new Response(200, [], \json_encode($order, \JSON_THROW_ON_ERROR))]);
        $this->gateways = new TestGateways($this->client);

        $this->gateways->setCachedToken($context);
        $returnedOrder = $this->gateways->orderGateway()->getOrder('order-id', $context);

        static::assertEquals($order, $returnedOrder);
    }

    public function testRequestWithoutResponse(): void
    {
        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage('No response given to handle request "GET https://api-m.sandbox.paypal.com/v2/checkout/orders/order-id"');

        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );

        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);

        $this->gateways->setCachedToken($context);
        $this->gateways->orderGateway()->getOrder('order-id', $context);
    }

    public function testRequestFiltering(): void
    {
        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
        );
        $this->gateways->setCachedToken($context);

        $this->gateways->orderGateway()->getOrder('order-id', $context);
        $patches = PatchCollection::createFromAssociative([['op' => 'repalce', 'path' => '/path/to/value']]);
        $this->gateways->orderGateway()->patchOrder('patch-order-id-1', $patches, $context);
        $this->gateways->orderGateway()->patchOrder('patch-order-id-2', $patches, $context);
        $order = (new Order())->assign(['id' => 'some-order-id']);
        $this->gateways->orderGateway()->createOrder($order, $context);

        $first = $this->client->getFirst();
        static::assertNotNull($first);
        static::assertSame('GET', $first->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/order-id', $first->getRequest()->getUri()->getPath());

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('POST', $last->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders', $last->getRequest()->getUri()->getPath());

        $firstPatch = $this->client->getFirstWhere(static fn (TestRequestContext $context) => $context->getRequest()->getMethod() === 'PATCH');
        static::assertNotNull($firstPatch);
        static::assertSame('PATCH', $firstPatch->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/patch-order-id-1', $firstPatch->getRequest()->getUri()->getPath());

        $lastPatch = $this->client->getLastWhere(static fn (TestRequestContext $context) => $context->getRequest()->getMethod() === 'PATCH');
        static::assertNotNull($lastPatch);
        static::assertSame('PATCH', $lastPatch->getRequest()->getMethod());
        static::assertSame('/v2/checkout/orders/patch-order-id-2', $lastPatch->getRequest()->getUri()->getPath());

        $all = $this->client->getAll();
        static::assertCount(4, $all);
        static::assertSame('/v2/checkout/orders/order-id', $all[0]->getRequest()->getUri()->getPath());
        static::assertSame('/v2/checkout/orders/patch-order-id-1', $all[1]->getRequest()->getUri()->getPath());
        static::assertSame('/v2/checkout/orders/patch-order-id-2', $all[2]->getRequest()->getUri()->getPath());
        static::assertSame('/v2/checkout/orders', $all[3]->getRequest()->getUri()->getPath());

        static::assertSame('/v2/checkout/orders/order-id', $this->client->get(0)?->getRequest()->getUri()->getPath());
        static::assertSame('/v2/checkout/orders/patch-order-id-1', $this->client->get(1)?->getRequest()->getUri()->getPath());
        static::assertSame('/v2/checkout/orders/patch-order-id-2', $this->client->get(2)?->getRequest()->getUri()->getPath());
        static::assertSame('/v2/checkout/orders', $this->client->get(3)?->getRequest()->getUri()->getPath());

        $this->client->resetRequests();
        static::assertEmpty($this->client->getAll());
    }

    protected function orderGatewayHandler(TestRequestContext $context): ?Response
    {
        if ($context->getGatewayMethod() === 'getOrder') {
            $body = \json_encode((new Order())->assign(['id' => 'get-order-id']), \JSON_THROW_ON_ERROR);

            return new Response(200, [], $body);
        }

        if ($context->getGatewayMethod() === 'createOrder') {
            $body = $context->getRequestBody();
            static::assertNotNull($body);
            $body = \json_encode($body, \JSON_THROW_ON_ERROR);

            return new Response(200, [], $body);
        }

        if ($context->getGatewayMethod() === 'patchOrder') {
            return new Response(204);
        }

        return null;
    }
}
