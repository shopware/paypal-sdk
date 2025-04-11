<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Exception;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Exception\OAuthApiException;

/**
 * @internal
 */
#[CoversClass(ApiException::class)]
#[CoversClass(OAuthApiException::class)]
class ApiExceptionTest extends TestCase
{
    public function test(): void
    {
        $response = new Response(200, body: '{"key":"value"}');

        $exception = new ApiException(
            ApiException::CODE_INVALID_CLIENT,
            'Some reason',
            $response,
        );

        static::assertSame(ApiException::CODE_INVALID_CLIENT, $exception->getErrorCode());
        static::assertSame('Some reason', $exception->getReason());
        static::assertSame(200, $exception->getStatusCode());
        static::assertSame('{"key":"value"}', $exception->getBody());
        static::assertTrue($exception->is(ApiException::CODE_INVALID_CLIENT));
        static::assertEquals([
            'status' => '200',
            'code' => 'INVALID_CLIENT',
            'title' => 'Some reason',
            'detail' => 'The error "INVALID_CLIENT" occurred with the following message: Some reason.',
            'meta' => [],
        ], $exception->jsonSerialize());
        static::assertSame(
            'The error "INVALID_CLIENT" occurred with the following message: Some reason.',
            $exception->getMessage()
        );
    }

    public function testDotTrim(): void
    {
        $response = new Response(200, body: '{"key":"value"}');

        $exception = new ApiException(
            ApiException::CODE_INVALID_CLIENT,
            'Some reason.',
            $response,
        );

        static::assertSame('The error "INVALID_CLIENT" occurred with the following message: Some reason.', $exception->getMessage());
    }
}
