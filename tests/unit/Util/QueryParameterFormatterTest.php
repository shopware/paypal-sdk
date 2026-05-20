<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Util;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Util\QueryParameterFormatter;

/**
 * @internal
 */
#[CoversClass(QueryParameterFormatter::class)]
class QueryParameterFormatterTest extends TestCase
{
    public function testWithStructQueryParametersAddsScalarValuesAndSkipsNullValues(): void
    {
        $context = new ApiContext(
            new CredentialsOAuthContext('client-id', 'client-secret'),
            true,
            queryParameters: ['existing' => 'value'],
        );

        $query = new class extends Struct {
            protected string $text = 'some-text';

            protected int $integer = 10;

            protected float $decimal = 12.5;

            protected bool $enabled = true;

            protected \DateTimeInterface $moment;

            protected ?string $empty = null;
        };
        $query->assign(['moment' => new \DateTimeImmutable('2026-01-31T23:59:59+02:00')]);

        $context = QueryParameterFormatter::withStructQueryParameters($context, $query);

        static::assertSame([
            'existing' => 'value',
            'text' => 'some-text',
            'integer' => '10',
            'decimal' => '12.5',
            'enabled' => 'true',
            'moment' => '2026-01-31T23:59:59+02:00',
        ], $context->getQueryParameters());
    }
}
