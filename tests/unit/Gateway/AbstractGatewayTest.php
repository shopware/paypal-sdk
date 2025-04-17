<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Contract\RequestServiceInterface;
use Shopware\PayPalSDK\Gateway\AbstractGateway;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;
use Shopware\PayPalSDK\Tests\Fixture\Gateway\TestGateway;

/**
 * @internal
 */
#[CoversClass(AbstractGateway::class)]
#[UsesClass(TestGateway::class)]
class AbstractGatewayTest extends TestCase
{
    protected TestClient $client;

    /** @phpstan-ignore-next-line phpunit.noMockObjectAndRealObjectProperty */
    protected RequestService&MockObject $requestService;

    protected TestGateways $gateways;

    protected TestGateway $gateway;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->requestService = $this->createMock(RequestService::class);
        $this->gateways = new TestGateways($this->client, $this->requestService);

        $this->gateway = new TestGateway($this->client, $this->gateways->tokenGateway(), $this->requestService);
    }

    public function testRequest(): void
    {
        $requestService = new RequestService();
        $this->requestService->expects(static::once())->method('createRequest')->willReturnCallback($requestService->createRequest(...));
        $this->requestService->expects(static::once())->method('withBody')->willReturnCallback($requestService->withBody(...));
        $this->requestService->expects(static::once())->method('handleResponse')->willReturnCallback($requestService->handleResponse(...));

        $body = new class extends Struct {
            protected string $someKey = 'test-value';
        };

        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
            'merchant-id',
            ['header-key' => 'header-value'],
            ['query-key' => 'query-value'],
            thirdParty: true,
        );

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode((new Order())->assign(['id' => 'some-order-id']), \JSON_THROW_ON_ERROR)));

        $order = $this->gateway->testPost(
            $body,
            Order::class,
            $context,
        );

        static::assertInstanceOf(Order::class, $order);
        static::assertSame('some-order-id', $order->getId());

        $request = $this->client->getLast()?->getRequest();
        static::assertNotNull($request);

        static::assertJsonStringEqualsJsonString(\json_encode($body, \JSON_THROW_ON_ERROR), (string) $request->getBody());
        static::assertEquals([
            'header-key' => ['header-value'],
            'Host' => ['api-m.sandbox.paypal.com'],
            'PayPal-Auth-Assertion' => ['eyJhbGciOiJub25lIn0=.eyJpc3MiOiJjbGllbnQtaWQiLCJwYXllcl9pZCI6Im1lcmNoYW50LWlkIn0=.'],
            'Authorization' => ['Bearer ACCESS-TOKEN'],
            'Content-Type' => [RequestServiceInterface::CONTENT_TYPE_JSON],
        ], $request->getHeaders());
        static::assertSame(
            'https://api-m.sandbox.paypal.com/some/path/to/endpoint?query-key=query-value',
            (string) $request->getUri(),
        );
        static::assertSame('POST', $request->getMethod());
    }

    public function testRequestWithoutBody(): void
    {
        $requestService = new RequestService();
        $this->requestService->expects(static::once())->method('createRequest')->willReturnCallback($requestService->createRequest(...));
        $this->requestService->expects(static::never())->method('withBody');
        $this->requestService->expects(static::once())->method('handleResponse')->willReturnCallback($requestService->handleResponse(...));

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response());

        $order = $this->gateway->testPost(
            null,
            null,
            $context,
        );

        static::assertNull($order);

        $request = $this->client->getLast()?->getRequest();
        static::assertNotNull($request);

        static::assertSame('', (string) $request->getBody());
        static::assertEquals([
            'Host' => ['api-m.sandbox.paypal.com'],
            'Authorization' => ['Bearer ACCESS-TOKEN'],
            'Content-Type' => [RequestServiceInterface::CONTENT_TYPE_JSON],
        ], $request->getHeaders());
        static::assertSame(
            'https://api-m.sandbox.paypal.com/some/path/to/endpoint',
            (string) $request->getUri(),
        );
        static::assertSame('POST', $request->getMethod());
    }

    public function testRequestWithoutBodyExpectsBody(): void
    {
        $requestService = new RequestService();
        $this->requestService->expects(static::once())->method('createRequest')->willReturnCallback($requestService->createRequest(...));
        $this->requestService->expects(static::never())->method('withBody');
        $this->requestService->expects(static::once())->method('handleResponse')->willReturnCallback($requestService->handleResponse(...));

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response());

        static::expectException(\LogicException::class);
        static::expectExceptionMessage('Expected response content for deserializing into ' . Order::class);

        $this->gateway->testPost(
            null,
            Order::class,
            $context,
        );
    }
}
