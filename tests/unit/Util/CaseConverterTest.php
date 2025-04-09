<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Util;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Util\CaseConverter;

/**
 * @internal
 */
#[CoversClass(CaseConverter::class)]
class CaseConverterTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testDenormalize(string $camelCase, string $snakeCase): void
    {
        static::assertSame($camelCase, CaseConverter::denormalize($snakeCase));
    }

    #[DataProvider('dataProvider')]
    public function testNormalize(string $camelCase, string $snakeCase): void
    {
        static::assertSame($snakeCase, CaseConverter::normalize($camelCase));
    }

    public static function dataProvider(): \Generator
    {
        yield ['fooBar', 'foo_bar'];
        yield ['_fooBar', '_foo_bar'];
        yield ['fooBar_', 'foo_bar_'];
        yield ['fooBar2', 'foo_bar_2'];
    }
}
