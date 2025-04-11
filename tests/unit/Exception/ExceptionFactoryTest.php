<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Exception;

use Http\Discovery\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Exception\ErrorApiException;
use Shopware\PayPalSDK\Exception\ExceptionFactory;
use Shopware\PayPalSDK\Exception\OAuthApiException;
use Shopware\PayPalSDK\Struct\Error\Error;

/**
 * @internal
 */
#[CoversClass(ExceptionFactory::class)]
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

        static::assertInstanceOf(OAuthApiException::class, $exception);
        static::assertSame('The error "INVALID_CLIENT" occurred with the following message: Client authentication failed.', $exception->getMessage());
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
