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
use Shopware\PayPalSDK\Exception\ErrorApiException;
use Shopware\PayPalSDK\Struct\Error\DetailCollection;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;

/**
 * @internal
 */
#[CoversClass(ErrorApiException::class)]
class ErrorApiExceptionTest extends TestCase
{
    public function test(): void
    {
        $response = new Response(200, body: '{"key":"value"}');

        $details = DetailCollection::createFromAssociative([[
            'description' => 'Invalid format',
            'field' => 'some-field',
            'value' => 'some-value',
        ]]);
        $links = LinkCollection::createFromAssociative([[
            'rel' => 'some-rel',
            'href' => 'some-href',
        ]]);

        $exception = new ErrorApiException(
            ApiException::CODE_INVALID_CLIENT,
            'Some reason',
            $response,
            'some-debug-id',
            $links,
            $details,
        );

        static::assertSame($details, $exception->getDetails());
        static::assertSame($links, $exception->getLinks());
        static::assertEquals([
            'status' => '200',
            'code' => 'INVALID_CLIENT',
            'title' => 'Some reason',
            'detail' => 'The error "INVALID_CLIENT" occurred with the following message: Some reason. | (some-field: some-value) Invalid format',
            'meta' => [
                'details' => [['description' => 'Invalid format', 'field' => 'some-field', 'value' => 'some-value']],
                'links' => [['rel' => 'some-rel', 'enc_type' => null, 'href' => 'some-href']],
            ],
        ], $exception->jsonSerialize());
        static::assertSame(
            'The error "INVALID_CLIENT" occurred with the following message: Some reason. | (some-field: some-value) Invalid format',
            $exception->getMessage()
        );
    }

    #[DataProvider('provideIs')]
    public function testIs(bool $is, string ...$codes): void
    {
        $response = new Response(200, body: '{"key":"value"}');

        $details = DetailCollection::createFromAssociative([[
            'description' => 'Invalid format',
            'field' => 'some-field',
            'value' => 'some-value',
            'issue' => ApiException::CODE_DUPLICATE_INVOICE_ID,
        ]]);

        $exception = new ErrorApiException(
            ApiException::CODE_INVALID_REQUEST,
            'Some message',
            $response,
            'some-debug-id',
            details: $details,
        );

        static::assertSame(
            'The error "INVALID_REQUEST" occurred with the following message: Some message. | [DUPLICATE_INVOICE_ID] (some-field: some-value) Invalid format',
            $exception->getMessage(),
        );

        static::assertSame($is, $exception->is(...$codes));
    }

    public static function provideIs(): \Generator
    {
        yield 'is matching single' => [true, ApiException::CODE_INVALID_REQUEST];
        yield 'is matching multiple' => [true, ApiException::CODE_INVALID_REQUEST, ApiException::CODE_DUPLICATE_INVOICE_ID];
        yield 'is not matching' => [false, ApiException::CODE_INVALID_CLIENT];
        yield 'is matching one of issue' => [true, ApiException::CODE_INVALID_CLIENT, ApiException::CODE_DUPLICATE_INVOICE_ID];
        yield 'is matching one of errorCode' => [true, ApiException::CODE_INVALID_CLIENT, ApiException::CODE_INVALID_REQUEST];
        yield 'is not matching multiple' => [false, ApiException::CODE_INVALID_CLIENT, ApiException::CODE_INVALID_RESOURCE_ID];
    }
}
