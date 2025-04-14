<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Http\Adapter\Guzzle7\Client as Guzzle7Client;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Gateway\GatewayInterface;
use Shopware\PayPalSDK\Contract\RequestServiceInterface;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Gateway\AbstractGateway;
use Shopware\PayPalSDK\Gateway\TokenGateway;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Token;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Util\TokenArrayCache;

/**
 * @template T of GatewayInterface
 *
 * @internal
 */
#[CoversClass(AbstractGateway::class)]
abstract class AbstractGatewayTestCase extends TestCase
{
    protected MockHandler $handler;

    protected Client $client;

    protected TokenArrayCache $cache;

    protected TokenGateway $tokenGateway;

    #[Before]
    protected function setUpTestCase(): void
    {
        $this->handler = new MockHandler();
        $this->client = new Client(['handler' => HandlerStack::create($this->handler)]);
        $this->cache = new TokenArrayCache();
        $this->tokenGateway = new TokenGateway(new Guzzle7Client($this->client), $this->cache);
    }

    /**
     * @return class-string<GatewayInterface>
     */
    abstract protected function gatewayClass(): string;

    protected function addStructResponse(Struct $response): void
    {
        $json = \json_encode($response);
        static::assertNotFalse($json);

        $this->handler->append(new Response(body: $json));
    }

    protected function getValidToken(): Token
    {
        return (new Token())->assign([
            'access_token' => 'some-access-token',
            'expires_in' => 36000,
            'token_type' => 'Bearer',
        ]);
    }

    protected function setCachedToken(ApiContextInterface $context, Token $token): void
    {
        $key = $context->getOAuthContext()->getCacheKey($context);
        static::assertNotNull($key);
        $this->cache->set($key, $token);
    }

    protected function getLast(): RequestInterface
    {
        $request = $this->handler->getLastRequest();
        static::assertNotNull($request);

        return $request;
    }

    public function testRequest(): void
    {
        $reflectionClass = new \ReflectionClass(static::gatewayClass());

        if (!$reflectionClass->hasMethod('request')) {
            static::markTestSkipped('No "request" method available to test');
        }

        $requestMethod = $reflectionClass->getMethod('request');

        $requestService = new RequestService();
        $requestServiceMock = $this->createMock(RequestService::class);
        $requestServiceMock->expects(static::once())->method('createRequest')->willReturnCallback($requestService->createRequest(...));
        $requestServiceMock->expects(static::once())->method('withBody')->willReturnCallback($requestService->withBody(...));
        $requestServiceMock->expects(static::once())->method('handleResponse')->willReturnCallback($requestService->handleResponse(...));

        $gateway = new (static::gatewayClass())($this->client, $this->tokenGateway, $requestServiceMock);

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

        $this->setCachedToken($context, $this->getValidToken());
        $this->addStructResponse((new Order())->assign(['id' => 'some-order-id']));

        /** @var ?Struct $order */
        $order = $requestMethod->invoke(
            $gateway,
            'POST',
            '/some/path/to/endpoint',
            $body,
            Order::class,
            $context,
        );

        static::assertInstanceOf(Order::class, $order);
        static::assertSame('some-order-id', $order->getId());

        $request = $this->getLast();
        static::assertJsonStringEqualsJsonString(\json_encode($body, \JSON_THROW_ON_ERROR), (string) $request->getBody());
        static::assertEquals([
            'header-key' => ['header-value'],
            'Host' => ['api-m.sandbox.paypal.com'],
            'PayPal-Auth-Assertion' => ['eyJhbGciOiJub25lIn0=.eyJpc3MiOiJjbGllbnQtaWQiLCJwYXllcl9pZCI6Im1lcmNoYW50LWlkIn0=.'],
            'Authorization' => ['Bearer some-access-token'],
            'Content-Length' => ['25'],
            'User-Agent' => ['GuzzleHttp/7'],
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
        $reflectionClass = new \ReflectionClass(static::gatewayClass());

        if (!$reflectionClass->hasMethod('request')) {
            static::markTestSkipped('No "request" method available to test');
        }

        $requestMethod = $reflectionClass->getMethod('request');

        $requestService = new RequestService();
        $requestServiceMock = $this->createMock(RequestService::class);
        $requestServiceMock->expects(static::once())->method('createRequest')->willReturnCallback($requestService->createRequest(...));
        $requestServiceMock->expects(static::never())->method('withBody');
        $requestServiceMock->expects(static::once())->method('handleResponse')->willReturnCallback($requestService->handleResponse(...));

        $gateway = new (static::gatewayClass())($this->client, $this->tokenGateway, $requestServiceMock);

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $this->setCachedToken($context, $this->getValidToken());
        $this->handler->append(new Response());

        /** @var ?Struct $order */
        $order = $requestMethod->invoke(
            $gateway,
            'POST',
            '/some/path/to/endpoint',
            null,
            null,
            $context,
        );

        static::assertNull($order);

        $request = $this->getLast();
        static::assertSame('', (string) $request->getBody());
        static::assertEquals([
            'Host' => ['api-m.sandbox.paypal.com'],
            'Authorization' => ['Bearer some-access-token'],
            'User-Agent' => ['GuzzleHttp/7'],
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
        $reflectionClass = new \ReflectionClass(static::gatewayClass());

        if (!$reflectionClass->hasMethod('request')) {
            static::markTestSkipped('No "request" method available to test');
        }

        $requestMethod = $reflectionClass->getMethod('request');

        $requestService = new RequestService();
        $requestServiceMock = $this->createMock(RequestService::class);
        $requestServiceMock->expects(static::once())->method('createRequest')->willReturnCallback($requestService->createRequest(...));
        $requestServiceMock->expects(static::never())->method('withBody');
        $requestServiceMock->expects(static::once())->method('handleResponse')->willReturnCallback($requestService->handleResponse(...));

        $gateway = new (static::gatewayClass())($this->client, $this->tokenGateway, $requestServiceMock);

        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true);

        $this->setCachedToken($context, $this->getValidToken());
        $this->handler->append(new Response());

        static::expectException(ApiException::class);
        static::expectExceptionMessage('The error "UNKNOWN" occurred with the following message: OK.');

        $requestMethod->invoke(
            $gateway,
            'POST',
            '/some/path/to/endpoint',
            null,
            Order::class,
            $context,
        );
    }
}
