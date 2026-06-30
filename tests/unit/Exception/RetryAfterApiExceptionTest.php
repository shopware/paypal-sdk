<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Exception;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Exception\RetryAfterApiException;

/**
 * @internal
 */
#[CoversClass(RetryAfterApiException::class)]
class RetryAfterApiExceptionTest extends TestCase
{
    public function testConstructorParsesSecondsHeader(): void
    {
        $response = new Response(429, body: '{"name":"RATE_LIMIT_REACHED"}');

        $exception = new RetryAfterApiException(
            ApiException::CODE_RATE_LIMIT_REACHED,
            'Rate limit reached',
            $response,
            'debug-id',
            retryAfter: '120',
        );

        static::assertSame(ApiException::CODE_RATE_LIMIT_REACHED, $exception->getErrorCode());
        static::assertSame(429, $exception->getStatusCode());
        static::assertSame('debug-id', $exception->debugId);
        static::assertNotNull($exception->getRetryAt());
        static::assertNotNull($exception->getRetryDelay());
        static::assertGreaterThanOrEqual(110000, $exception->getRetryDelay());
        static::assertLessThanOrEqual(120000, $exception->getRetryDelay());
    }

    public function testConstructorParsesDateHeader(): void
    {
        $retryAt = new \DateTimeImmutable('+2 minutes');

        $exception = new RetryAfterApiException(
            ApiException::CODE_RATE_LIMIT_REACHED,
            'Rate limit reached',
            new Response(429),
            'debug-id',
            retryAfter: $retryAt->format(\DateTimeInterface::RFC7231),
        );

        static::assertNotNull($exception->getRetryAt());
        static::assertSame($retryAt->getTimestamp(), $exception->getRetryAt()->getTimestamp());
        static::assertNotNull($exception->getRetryDelay());
        static::assertGreaterThanOrEqual(110000, $exception->getRetryDelay());
        static::assertLessThanOrEqual(120000, $exception->getRetryDelay());
    }

    #[DataProvider('provideConstructorIgnoresInvalidHeaders')]
    public function testConstructorIgnoresInvalidHeaders(?string $retryAfter): void
    {
        $exception = new RetryAfterApiException(
            ApiException::CODE_RATE_LIMIT_REACHED,
            'Rate limit reached',
            new Response(429),
            'debug-id',
            retryAfter: $retryAfter,
        );

        static::assertNull($exception->getRetryAt());
        static::assertNull($exception->getRetryDelay());
    }

    public static function provideConstructorIgnoresInvalidHeaders(): \Generator
    {
        yield 'missing' => [null];
        yield 'empty' => [''];
        yield 'zero seconds' => ['0'];
        yield 'invalid date' => ['not-a-date'];
        yield 'past date' => [(new \DateTimeImmutable('-1 minute'))->format(\DateTimeInterface::RFC7231)];
    }
}
