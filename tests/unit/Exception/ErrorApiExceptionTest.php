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
        $factory = new Psr17Factory();
        $response = $factory
            ->createResponse(204, 'reason phrase')
            ->withBody($factory->createStream('{"key":"value"}'));

        $details = DetailCollection::createFromAssociative([[
            'field' => 'some-field',
            'value' => 'some-value',
        ]]);
        $links = LinkCollection::createFromAssociative([[
            'rel' => 'some-rel',
            'href' => 'some-href',
        ]]);

        $exception = new ErrorApiException(
            ApiException::CODE_INVALID_CLIENT,
            'Some message',
            $response,
            'some-debug-id',
            $links,
            $details,
        );

        static::assertSame($details, $exception->getDetails());
        static::assertSame($links, $exception->getLinks());
        static::assertEquals([
            'status' => '204',
            'code' => 'INVALID_CLIENT',
            'title' => 'The error "INVALID_CLIENT" occurred with the following message: Some message',
            'detail' => 'Some message',
            'meta' => [
                'details' => [['field' => 'some-field', 'value' => 'some-value']],
                'links' => [['rel' => 'some-rel', 'enc_type' => null, 'href' => 'some-href']],
            ],
        ], $exception->jsonSerialize());
    }
}
