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
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Gateway\AbstractGateway;
use Shopware\PayPalSDK\Gateway\TokenGateway;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Token;
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

    public function testRequestRetriesWithRefreshedTokenOnInvalidCachedToken(): void
    {
        $this->useRealRequestService();

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);
        $this->gateways->setCachedToken($context, $this->createToken('CACHED-TOKEN'));

        // 1. the API rejects the cached token
        $this->client->addResponse($this->createInvalidTokenResponse());
        // 2. the token gateway fetches a fresh token
        $this->client->addResponse($this->createTokenResponse('REFRESHED-TOKEN'));
        // 3. the original request is retried and succeeds
        $this->client->addResponse(new Response(body: \json_encode((new Order())->assign(['id' => 'some-order-id']), \JSON_THROW_ON_ERROR)));

        $order = $this->gateway->testPost(null, Order::class, $context);

        static::assertInstanceOf(Order::class, $order);
        static::assertSame('some-order-id', $order->getId());

        $requests = $this->client->getAll();
        static::assertCount(3, $requests);

        static::assertSame('Bearer CACHED-TOKEN', $requests[0]->getRequest()->getHeaderLine('Authorization'));
        static::assertSame(TokenGateway::GATEWAY_URL, $requests[1]->getRequest()->getUri()->getPath());
        static::assertSame('Bearer REFRESHED-TOKEN', $requests[2]->getRequest()->getHeaderLine('Authorization'));

        static::assertSame('POST', $requests[2]->getRequest()->getMethod());
        static::assertSame(
            'https://api-m.sandbox.paypal.com/some/path/to/endpoint',
            (string) $requests[2]->getRequest()->getUri(),
        );
    }

    public function testRequestDoesNotRetryWithFreshToken(): void
    {
        $this->useRealRequestService();

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        // no cached token, so the token gateway requests a fresh one
        $this->client->addResponse($this->createTokenResponse('FRESH-TOKEN'));
        $this->client->addResponse($this->createInvalidTokenResponse());

        try {
            $this->gateway->testPost(null, Order::class, $context);
            static::fail('Expected an ' . ApiException::class . ' to be thrown.');
        } catch (ApiException $e) {
            static::assertTrue($e->is('invalid_token', ApiException::CODE_INVALID_TOKEN));
        }

        static::assertCount(2, $this->client->getAll(), 'A fresh token must not be refreshed again.');
    }

    public function testRequestDoesNotRetryOnOtherApiException(): void
    {
        $this->useRealRequestService();

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);
        $this->gateways->setCachedToken($context, $this->createToken('CACHED-TOKEN'));

        $this->client->addResponse(new Response(422, [], \json_encode([
            'name' => ApiException::CODE_UNPROCESSABLE_ENTITY,
            'message' => 'The requested action could not be performed.',
        ], \JSON_THROW_ON_ERROR)));

        try {
            $this->gateway->testPost(null, Order::class, $context);
            static::fail('Expected an ' . ApiException::class . ' to be thrown.');
        } catch (ApiException $e) {
            static::assertSame(ApiException::CODE_UNPROCESSABLE_ENTITY, $e->getErrorCode());
        }

        static::assertCount(1, $this->client->getAll(), 'A non token related error must not be retried.');
    }

    public function testRequestRethrowsWhenRetryFailsAgain(): void
    {
        $this->useRealRequestService();

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);
        $this->gateways->setCachedToken($context, $this->createToken('CACHED-TOKEN'));

        $this->client->addResponse($this->createInvalidTokenResponse());
        $this->client->addResponse($this->createTokenResponse('REFRESHED-TOKEN'));
        $this->client->addResponse($this->createInvalidTokenResponse());

        try {
            $this->gateway->testPost(null, Order::class, $context);
            static::fail('Expected an ' . ApiException::class . ' to be thrown.');
        } catch (ApiException $e) {
            static::assertTrue($e->is('invalid_token', ApiException::CODE_INVALID_TOKEN));
        }

        static::assertCount(3, $this->client->getAll(), 'The retry must not be retried itself.');
    }

    public function testRequestRetrySendsBodyAgain(): void
    {
        $this->useRealRequestService();

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);
        $this->gateways->setCachedToken($context, $this->createToken('CACHED-TOKEN'));

        $body = new class extends Struct {
            protected string $someKey = 'test-value';
        };

        $this->client->addResponse($this->createInvalidTokenResponse());
        $this->client->addResponse($this->createTokenResponse('REFRESHED-TOKEN'));
        $this->client->addResponse(new Response(body: \json_encode((new Order())->assign(['id' => 'some-order-id']), \JSON_THROW_ON_ERROR)));

        $order = $this->gateway->testPost($body, Order::class, $context);

        static::assertInstanceOf(Order::class, $order);

        $retry = $this->client->get(2)?->getRequest();
        static::assertNotNull($retry);
        static::assertJsonStringEqualsJsonString(\json_encode($body, \JSON_THROW_ON_ERROR), (string) $retry->getBody());
    }

    /**
     * Replaces the mocked request service with a real one, so responses are handled
     * and {@see ApiException}s are actually thrown.
     */
    protected function useRealRequestService(): void
    {
        $requestService = new RequestService();
        $this->requestService->method('createRequest')->willReturnCallback($requestService->createRequest(...));
        $this->requestService->method('withBody')->willReturnCallback($requestService->withBody(...));
        $this->requestService->method('handleResponse')->willReturnCallback($requestService->handleResponse(...));
    }

    protected function createToken(string $accessToken): Token
    {
        return (new Token())->assign([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => 36000,
        ]);
    }

    protected function createTokenResponse(string $accessToken): Response
    {
        return new Response(200, [], \json_encode([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => 36000,
        ], \JSON_THROW_ON_ERROR));
    }

    protected function createInvalidTokenResponse(): Response
    {
        return new Response(401, [], \json_encode([
            'error' => 'invalid_token',
            'error_description' => 'The token passed in was not found in the system.',
        ], \JSON_THROW_ON_ERROR));
    }
}
