<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Exception;

use Http\Discovery\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Exception\ErrorApiException;
use Shopware\PayPalSDK\Exception\ExceptionFactory;
use Shopware\PayPalSDK\Exception\OAuthApiException;
use Shopware\PayPalSDK\Exception\RetryAfterApiException;
use Shopware\PayPalSDK\Struct\Error\Error;

/**
 * @internal
 */
#[CoversClass(ExceptionFactory::class)]
#[CoversClass(RetryAfterApiException::class)]
class ExceptionFactoryTest extends TestCase
{
    private Psr17Factory $factory;

    protected function setUp(): void
    {
        $this->factory = new Psr17Factory();
    }

    public function testCreateFromResponse(): void
    {
        $error = new Error();
        $error->setName(ApiException::CODE_DUPLICATE_INVOICE_ID);
        $error->setMessage('Duplicate invoice ID');
        $error->setDebugId('1234567890');

        $response = $this->factory
            ->createResponse()
            ->withBody($this->factory->createStream(\json_encode($error, \JSON_THROW_ON_ERROR)))
            ->withStatus(400);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertInstanceOf(ErrorApiException::class, $exception);
        static::assertSame('The error "DUPLICATE_INVOICE_ID" occurred with the following message: Duplicate invoice ID.', $exception->getMessage());
    }

    public function testCreateFromResponse401(): void
    {
        $error = new Error();
        $error->setError(ApiException::CODE_INVALID_CLIENT);
        $error->setErrorDescription('Client authentication failed');

        $response = $this->factory
            ->createResponse()
            ->withBody($this->factory->createStream(\json_encode($error, \JSON_THROW_ON_ERROR)))
            ->withStatus(401);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertInstanceOf(OAuthApiException::class, $exception);
        static::assertSame('The error "INVALID_CLIENT" occurred with the following message: Client authentication failed.', $exception->getMessage());
    }

    public function testCreateFromResponseOAuthError(): void
    {
        $error = new Error();
        $error->setError(ApiException::CODE_INVALID_CLIENT);
        $error->setErrorDescription('Client authentication failed');

        $response = $this->factory
            ->createResponse()
            ->withBody($this->factory->createStream(\json_encode($error, \JSON_THROW_ON_ERROR)))
            ->withStatus(400);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertInstanceOf(OAuthApiException::class, $exception);
        static::assertSame('The error "INVALID_CLIENT" occurred with the following message: Client authentication failed.', $exception->getMessage());
    }

    public function testCreateFromResponse401WithoutBody(): void
    {
        $response = $this->factory
            ->createResponse()
            ->withStatus(401);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertSame(ApiException::class, $exception::class);
        static::assertSame(ApiException::CODE_UNKNOWN, $exception->getErrorCode());
        static::assertSame('Unauthorized', $exception->getReason());
    }

    #[DataProvider('provideNonOAuth401PreservesResponseBody')]
    public function testNonOAuth401PreservesResponseBody(string $body): void
    {
        $response = $this->factory->createResponse(401)->withBody($this->factory->createStream($body));

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertSame(ApiException::class, $exception::class);
        static::assertSame(ApiException::CODE_UNKNOWN, $exception->getErrorCode());
        static::assertSame($body, $exception->getReason());
        static::assertSame(401, $exception->getStatusCode());
    }

    public static function provideNonOAuth401PreservesResponseBody(): \Generator
    {
        yield 'plain text' => ['Gateway authentication rejected'];
        yield 'JSON scalar' => ['"Access denied"'];
        yield 'missing error code' => ['{"error_description":"Access denied"}'];
        yield 'empty error code' => ['{"error":""}'];
        yield 'nested error' => ['{"error":{"message":"Access denied"}}'];
        yield 'numeric error code' => ['{"error":401}'];
        yield 'invalid description' => ['{"error":"invalid_client","error_description":[]}'];
        yield 'invalid URI' => ['{"error":"invalid_client","error_uri":123}'];
    }

    public function testRestErrorWith401PreservesDetails(): void
    {
        $response = $this->factory->createResponse(401)->withBody($this->factory->createStream(\json_encode([
            'name' => 'NOT_AUTHORIZED',
            'message' => 'Permission denied',
            'debug_id' => 'debug-123',
            'details' => [['issue' => 'PERMISSION_DENIED', 'description' => 'Missing permission']],
        ], \JSON_THROW_ON_ERROR)));

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertInstanceOf(ErrorApiException::class, $exception);
        static::assertSame('NOT_AUTHORIZED', $exception->getErrorCode());
        static::assertSame('Permission denied', $exception->getReason());
        static::assertSame('debug-123', $exception->debugId);
        static::assertTrue($exception->is('PERMISSION_DENIED'));
    }

    public function testOAuthErrorWithoutDescriptionPreservesErrorCode(): void
    {
        $response = $this->factory->createResponse(401)->withBody($this->factory->createStream('{"error":"invalid_token"}'));

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertInstanceOf(OAuthApiException::class, $exception);
        static::assertSame('invalid_token', $exception->getErrorCode());
    }

    public function testCreateFromResponseWithoutBody(): void
    {
        $response = $this->factory
            ->createResponse()
            ->withStatus(400);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertSame(ApiException::class, $exception::class);
        static::assertSame('The error "UNKNOWN" occurred with the following message: Bad Request.', $exception->getMessage());
    }

    public function testCreateFromResponseWithBodyMalformed(): void
    {
        $response = $this->factory
            ->createResponse()
            ->withBody($this->factory->createStream('something'))
            ->withStatus(400);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertSame(ApiException::class, $exception::class);
        static::assertSame('The error "UNKNOWN" occurred with the following message: something.', $exception->getMessage());
    }

    public function testCreateFromResponseWithRetryAfterSeconds(): void
    {
        $error = new Error();
        $error->setName('OTHER_RATE_LIMIT');
        $error->setMessage('Rate limit reached');
        $error->setDebugId('1234567890');

        $response = $this->factory
            ->createResponse()
            ->withHeader('Retry-After', '120')
            ->withBody($this->factory->createStream(\json_encode($error, \JSON_THROW_ON_ERROR)))
            ->withStatus(429);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertInstanceOf(RetryAfterApiException::class, $exception);
        static::assertSame('OTHER_RATE_LIMIT', $exception->getErrorCode());
        static::assertNotNull($exception->getRetryAt());
        static::assertNotNull($exception->getRetryDelay());
        static::assertGreaterThanOrEqual(110000, $exception->getRetryDelay());
        static::assertLessThanOrEqual(120000, $exception->getRetryDelay());
    }

    public function testCreateFromResponseWithRetryAfterWithoutErrorBody(): void
    {
        $response = $this->factory
            ->createResponse()
            ->withHeader('Retry-After', '120')
            ->withStatus(429);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertNotInstanceOf(RetryAfterApiException::class, $exception);
        static::assertSame(ApiException::class, $exception::class);
        static::assertSame(ApiException::CODE_UNKNOWN, $exception->getErrorCode());
        static::assertSame(429, $exception->getStatusCode());
    }

    public function testCreateFromResponseWithRateLimitCodeWithoutTooManyRequestsStatus(): void
    {
        $error = new Error();
        $error->setName(ApiException::CODE_RATE_LIMIT_REACHED);
        $error->setMessage('Rate limit reached');
        $error->setDebugId('1234567890');

        $response = $this->factory
            ->createResponse()
            ->withHeader('Retry-After', '120')
            ->withBody($this->factory->createStream(\json_encode($error, \JSON_THROW_ON_ERROR)))
            ->withStatus(400);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertSame(ErrorApiException::class, $exception::class);
        static::assertSame(ApiException::CODE_RATE_LIMIT_REACHED, $exception->getErrorCode());
    }

    public function testCreateFromResponseWithRetryAfterDate(): void
    {
        $error = new Error();
        $error->setName(ApiException::CODE_RATE_LIMIT_REACHED);
        $error->setMessage('Rate limit reached');
        $error->setDebugId('1234567890');

        $response = $this->factory
            ->createResponse()
            ->withHeader('Retry-After', (new \DateTimeImmutable('+2 minutes', new \DateTimeZone('GMT')))->format('D, d M Y H:i:s \G\M\T'))
            ->withBody($this->factory->createStream(\json_encode($error, \JSON_THROW_ON_ERROR)))
            ->withStatus(429);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertInstanceOf(RetryAfterApiException::class, $exception);
        static::assertNotNull($exception->getRetryAt());
        static::assertNotNull($exception->getRetryDelay());
        static::assertGreaterThanOrEqual(110000, $exception->getRetryDelay());
        static::assertLessThanOrEqual(120000, $exception->getRetryDelay());
    }

    public function testCreateFromResponseWithInvalidRetryAfter(): void
    {
        $error = new Error();
        $error->setName(ApiException::CODE_RATE_LIMIT_REACHED);
        $error->setMessage('Rate limit reached');
        $error->setDebugId('1234567890');

        $response = $this->factory
            ->createResponse()
            ->withHeader('Retry-After', 'not-a-date')
            ->withBody($this->factory->createStream(\json_encode($error, \JSON_THROW_ON_ERROR)))
            ->withStatus(429);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertInstanceOf(RetryAfterApiException::class, $exception);
        static::assertNull($exception->getRetryAt());
        static::assertNull($exception->getRetryDelay());
    }

    public function testCreateFromResponseWihBodyEmpty(): void
    {
        $response = $this->factory
            ->createResponse()
            ->withBody($this->factory->createStream(''))
            ->withStatus(400);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertSame(ApiException::class, $exception::class);
        static::assertSame('The error "UNKNOWN" occurred with the following message: Bad Request.', $exception->getMessage());
    }

    public function testCreateFromResponse501(): void
    {
        $response = $this->factory
            ->createResponse()
            ->withStatus(501);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertSame(ApiException::class, $exception::class);
        static::assertSame('The error "INTERNAL_SERVER_ERROR" occurred with the following message: An internal server error at PayPal has occurred.', $exception->getMessage());
    }

    public function testCreateFromResponse503(): void
    {
        $response = $this->factory
            ->createResponse()
            ->withStatus(503);

        $exception = ExceptionFactory::createFromResponse($response);

        static::assertSame(ApiException::class, $exception::class);
        static::assertSame('The error "SERVICE_UNAVAILABLE" occurred with the following message: PayPal is unavailable.', $exception->getMessage());
    }
}
