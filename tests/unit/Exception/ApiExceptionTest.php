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
        $factory = new Psr17Factory();
        $response = $factory
            ->createResponse(204, 'reason phrase')
            ->withBody($factory->createStream('{"key":"value"}'));

        $exception = new ApiException(
            ApiException::CODE_INVALID_CLIENT,
            'Some message',
            $response,
        );

        static::assertSame(ApiException::CODE_INVALID_CLIENT, $exception->getErrorCode());
        static::assertSame('Some message', $exception->getReason());
        static::assertSame(204, $exception->getStatusCode());
        static::assertSame('{"key":"value"}', $exception->getBody());
        static::assertTrue($exception->is(ApiException::CODE_INVALID_CLIENT));
        static::assertEquals([
            'status' => '204',
            'code' => 'INVALID_CLIENT',
            'title' => 'The error "INVALID_CLIENT" occurred with the following message: Some message',
            'detail' => 'Some message',
            'meta' => [],
        ], $exception->jsonSerialize());
    }
}
