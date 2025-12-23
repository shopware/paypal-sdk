<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit;

use Http\Discovery\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\AuthorizationCodeOAuthContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Contract\RequestServiceInterface;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Exception\OAuthApiException;
use Shopware\PayPalSDK\RequestService;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @internal
 */
#[CoversClass(RequestService::class)]
class RequestServiceTest extends TestCase
{
    private RequestService $service;

    private Psr17Factory $factory;

    protected function setUp(): void
    {
        $this->service = new RequestService();
        $this->factory = new Psr17Factory();
    }

    public function testCreateRequest(): void
    {
        $context = new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
        );

        $request = $this->service->createRequest('GET', '/some/endpoint', $context);

        static::assertSame('GET', $request->getMethod());
        static::assertSame('https://api-m.paypal.com/some/endpoint', (string) $request->getUri());
        static::assertEquals(['Host' => ['api-m.paypal.com']], $request->getHeaders());
        static::assertEmpty((string) $request->getBody());
    }

    public function testCreateRequestWithSandbox(): void
    {
        $context = new ApiContext(
            new CredentialsOAuthContext('', ''),
            true,
        );

        $request = $this->service->createRequest('GET', '/some/endpoint', $context);

        static::assertSame('GET', $request->getMethod());
        static::assertSame('https://api-m.sandbox.paypal.com/some/endpoint', (string) $request->getUri());
        static::assertEquals(['Host' => ['api-m.sandbox.paypal.com']], $request->getHeaders());
        static::assertEmpty((string) $request->getBody());
    }

    public function testCreateRequestWithQuery(): void
    {
        $context = new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
            queryParameters: ['key' => 'value', 'kEY2' => 'vaLüe2'],
        );

        $request = $this->service->createRequest('GET', '/some/endpoint', $context);

        static::assertSame('GET', $request->getMethod());
        static::assertSame('https://api-m.paypal.com/some/endpoint?key=value&kEY2=vaL%C3%BCe2', (string) $request->getUri());
        static::assertEquals(['Host' => ['api-m.paypal.com']], $request->getHeaders());
        static::assertEmpty((string) $request->getBody());
    }

    public function testCreateRequestWithHeaders(): void
    {
        $context = (new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
            headers: ['X-Header' => 'value'],
        ))->withPartnerAttributionId('some-partner-id');

        $request = $this->service->createRequest('GET', '/some/endpoint', $context);

        static::assertSame('GET', $request->getMethod());
        static::assertSame('https://api-m.paypal.com/some/endpoint', (string) $request->getUri());
        static::assertEquals([
            'Host' => ['api-m.paypal.com'],
            'x-header' => ['value'],
            'paypal-partner-attribution-id' => ['some-partner-id'],
        ], $request->getHeaders());
        static::assertEmpty((string) $request->getBody());
    }

    public function testCreateRequestWithThirdParty(): void
    {
        $context = (new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
            'some-merchant-id',
            thirdParty: true,
        ));

        $request = $this->service->createRequest('GET', '/some/endpoint', $context);

        static::assertSame('GET', $request->getMethod());
        static::assertSame('https://api-m.paypal.com/some/endpoint', (string) $request->getUri());
        static::assertEquals([
            'Host' => ['api-m.paypal.com'],
            'PayPal-Auth-Assertion' => ['eyJhbGciOiJub25lIn0=.eyJpc3MiOiIiLCJwYXllcl9pZCI6InNvbWUtbWVyY2hhbnQtaWQifQ==.'],
        ], $request->getHeaders());
        static::assertEmpty((string) $request->getBody());
    }

    public function testCreateRequestWithThirdPartyWithoutMerchantId(): void
    {
        $context = (new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
            thirdParty: true,
        ));

        static::expectException(\LogicException::class);
        static::expectExceptionMessage('ApiContext is flagged as third party, but misses a merchant id.');

        $this->service->createRequest('GET', '/some/endpoint', $context);
    }

    public function testCreateRequestWithThirdPartyWithoutCredentialsContext(): void
    {
        $context = (new ApiContext(
            new AuthorizationCodeOAuthContext('', '', ''),
            false,
            'some-merchant-id',
            thirdParty: true,
        ));

        $request = $this->service->createRequest('GET', '/some/endpoint', $context);

        static::assertSame('GET', $request->getMethod());
        static::assertSame('https://api-m.paypal.com/some/endpoint', (string) $request->getUri());
        static::assertEquals([
            'Host' => ['api-m.paypal.com'],
        ], $request->getHeaders());
        static::assertEmpty((string) $request->getBody());
    }

    public function testCreateRequestWithAll(): void
    {
        $context = (new ApiContext(
            new CredentialsOAuthContext('', ''),
            true,
            'some-merchant-id',
            ['X-Header' => 'value'],
            ['key' => 'value'],
            true,
        ))->withPartnerAttributionId('some-partner-id');

        $request = $this->service->createRequest('POST', '/some/endpoint', $context);

        static::assertSame('POST', $request->getMethod());
        static::assertSame('https://api-m.sandbox.paypal.com/some/endpoint?key=value', (string) $request->getUri());
        static::assertEquals([
            'Host' => ['api-m.sandbox.paypal.com'],
            'x-header' => ['value'],
            'paypal-partner-attribution-id' => ['some-partner-id'],
            'PayPal-Auth-Assertion' => ['eyJhbGciOiJub25lIn0=.eyJpc3MiOiIiLCJwYXllcl9pZCI6InNvbWUtbWVyY2hhbnQtaWQifQ==.'],
            'Content-Type' => ['application/json'],
        ], $request->getHeaders());
        static::assertEmpty((string) $request->getBody());
    }

    public function testWithBody(): void
    {
        $context = new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
        );

        $request = $this->service->createRequest('POST', '/some/endpoint', $context);

        $request = $this->service->withBody($request, ['key' => 'value']);

        static::assertSame('POST', $request->getMethod());
        static::assertSame('https://api-m.paypal.com/some/endpoint', (string) $request->getUri());
        static::assertEquals([
            'Host' => ['api-m.paypal.com'],
            'Content-Type' => ['application/json'],
        ], $request->getHeaders());
        static::assertSame('{"key":"value"}', (string) $request->getBody());
    }

    public function testWithBodyUrlEncoded(): void
    {
        $context = (new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
        ))->withHeader('Content-Type', RequestServiceInterface::CONTENT_TYPE_URL_ENCODED);

        $request = $this->service->createRequest('POST', '/some/endpoint', $context);

        $request = $this->service->withBody($request, ['key' => 'value']);

        static::assertSame('POST', $request->getMethod());
        static::assertSame('https://api-m.paypal.com/some/endpoint', (string) $request->getUri());
        static::assertEquals([
            'Host' => ['api-m.paypal.com'],
            'content-type' => ['application/x-www-form-urlencoded'],
        ], $request->getHeaders());
        static::assertSame('key=value', (string) $request->getBody());
    }

    public function testWithBodyUrlEncodedJsonSerialize(): void
    {
        $context = (new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
        ))->withHeader('Content-Type', RequestServiceInterface::CONTENT_TYPE_URL_ENCODED);

        $request = $this->service->createRequest('POST', '/some/endpoint', $context);

        $body = new class extends Struct {
            protected string $key = 'value';
        };

        $request = $this->service->withBody($request, $body);

        static::assertSame('POST', $request->getMethod());
        static::assertSame('https://api-m.paypal.com/some/endpoint', (string) $request->getUri());
        static::assertEquals([
            'Host' => ['api-m.paypal.com'],
            'content-type' => ['application/x-www-form-urlencoded'],
        ], $request->getHeaders());
        static::assertSame('key=value', (string) $request->getBody());
    }

    public function testWithBodyWeirdEncoded(): void
    {
        $context = (new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
        ))->withHeader('Content-Type', 'text/cannot-handle-this');

        $request = $this->service->createRequest('POST', '/some/endpoint', $context);

        static::expectException(\InvalidArgumentException::class);
        static::expectExceptionMessage(RequestService::class . '::withBody is unable to handle content-type "text/cannot-handle-this"');

        $this->service->withBody($request, ['key' => 'value']);
    }

    public function testWithBodyWithoutContentType(): void
    {
        $context = (new ApiContext(
            new CredentialsOAuthContext('', ''),
            false,
        ));

        $request = $this->service->createRequest('POST', '/some/endpoint', $context)
            ->withoutHeader(RequestServiceInterface::HEADER_CONTENT_TYPE);

        $requestWithBody = $this->service->withBody($request, ['key' => 'value']);

        static::assertSame($request, $requestWithBody);
    }

    public function testHandleResponse(): void
    {
        $response = $this->factory->createResponse(200)
            ->withBody($this->factory->createStream('{"key":"value"}'));

        $content = $this->service->handleResponse($response);

        static::assertSame(['key' => 'value'], $content);
    }

    #[DataProvider('provideHandleResponseWith400')]
    public function testHandleResponseWith400(int $code): void
    {
        $response = $this->factory->createResponse($code)
            ->withBody($this->factory->createStream('{"name":"TEST", "message":"bad_request"}'));

        if ($code === 401) {
            static::expectException(OAuthApiException::class);
            static::expectExceptionMessage('The error "INVALID_CLIENT" occurred with the following message: Client authentication failed');
        } else {
            static::expectException(ApiException::class);
            static::expectExceptionMessage('The error "TEST" occurred with the following message: bad_request');
        }

        $this->service->handleResponse($response);
    }

    public static function provideHandleResponseWith400(): \Generator
    {
        foreach (\range(400, 499) as $code) {
            yield 'code ' . $code => ['code' => $code];
        }
    }

    public function testHandleResponseWithNonJsonContent(): void
    {
        $response = $this->factory->createResponse(200)
            ->withBody($this->factory->createStream('non-json-content'));

        static::expectException(ApiException::class);
        static::expectExceptionMessage('The error "UNKNOWN" occurred with the following message: non-json-content');

        $this->service->handleResponse($response);
    }

    public function testHandleResponseWithStringJsonContent(): void
    {
        $response = $this->factory->createResponse(200)
            ->withBody($this->factory->createStream('"json-content"'));

        static::expectException(ApiException::class);
        static::expectExceptionMessage('The error "UNKNOWN" occurred with the following message: "json-content"');

        $this->service->handleResponse($response);
    }

    public function testHandleResponseWithoutBody(): void
    {
        $response = $this->factory->createResponse(200);

        static::assertNull($this->service->handleResponse($response));
    }
}
