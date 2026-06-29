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
    public function testGetRetryDelayFromConstructor(): void
    {
        $response = new Response(429, body: '{"name":"RATE_LIMIT_REACHED"}');

        $exception = new RetryAfterApiException(
            ApiException::CODE_RATE_LIMIT_REACHED,
            'Rate limit reached',
            $response,
            'debug-id',
            retryDelay: 60000,
        );

        static::assertSame(ApiException::CODE_RATE_LIMIT_REACHED, $exception->getErrorCode());
        static::assertSame(429, $exception->getStatusCode());
        static::assertSame('debug-id', $exception->debugId);
        static::assertSame(60000, $exception->getRetryDelay());
    }

    public function testFromErrorResponseParsesSecondsHeader(): void
    {
        $exception = RetryAfterApiException::fromErrorResponse(
            ApiException::CODE_RATE_LIMIT_REACHED,
            'Rate limit reached',
            new Response(429, ['Retry-After' => '120']),
            'debug-id',
        );

        static::assertSame(120000, $exception->getRetryDelay());
    }

    public function testFromErrorResponseParsesDateHeader(): void
    {
        $response = new Response(429, [
            'Retry-After' => (new \DateTimeImmutable('+2 minutes'))->format(\DateTimeInterface::RFC7231),
        ]);

        $exception = RetryAfterApiException::fromErrorResponse(
            ApiException::CODE_RATE_LIMIT_REACHED,
            'Rate limit reached',
            $response,
            'debug-id',
        );

        static::assertNotNull($exception->getRetryDelay());
        static::assertGreaterThanOrEqual(110000, $exception->getRetryDelay());
        static::assertLessThanOrEqual(120000, $exception->getRetryDelay());
    }

    #[DataProvider('provideInvalidRetryAfterHeaders')]
    public function testFromErrorResponseIgnoresInvalidHeaders(?string $retryAfter): void
    {
        $headers = $retryAfter !== null ? ['Retry-After' => $retryAfter] : [];

        $exception = RetryAfterApiException::fromErrorResponse(
            ApiException::CODE_RATE_LIMIT_REACHED,
            'Rate limit reached',
            new Response(429, $headers),
            'debug-id',
        );

        static::assertNull($exception->getRetryDelay());
    }

    public static function provideInvalidRetryAfterHeaders(): \Generator
    {
        yield 'missing' => [null];
        yield 'empty' => [''];
        yield 'zero seconds' => ['0'];
        yield 'invalid date' => ['not-a-date'];
        yield 'past date' => [(new \DateTimeImmutable('-1 minute'))->format(\DateTimeInterface::RFC7231)];
    }
}
