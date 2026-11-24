<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Tools\OpenApi;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Tools\OpenApi\Facets;

/**
 * @internal
 */
#[CoversClass(Facets::class)]
class FacetsTest extends TestCase
{
    public function testFromSchemaDropsFacetsThatConstrainNothing(): void
    {
        $schema = ['format' => 'ppaas_date_time_v3', 'minLength' => 0, 'maxLength' => 2147483647, 'pattern' => '^[\S\s]*$', 'minimum' => 1];

        static::assertSame(['format' => 'date-time'], Facets::fromSchema($schema, 'string'));
        static::assertSame(['minimum' => 1], Facets::fromSchema($schema, 'int'));
    }

    public function testMergeKeepsTheLoosestFacetsEverySetHas(): void
    {
        $merged = Facets::merge([
            ['minLength' => 1, 'maxLength' => 10, 'pattern' => '^a$', 'format' => 'email'],
            ['minLength' => 2, 'maxLength' => 20, 'format' => 'email'],
        ]);

        static::assertSame(['format' => 'email', 'minLength' => 1, 'maxLength' => 20], $merged);
    }

    public function testDiffDescribesEachFacet(): void
    {
        $diff = Facets::diff(['maxLength' => 120, 'pattern' => '^a$'], ['maxLength' => 127, 'minLength' => 1]);

        static::assertSame(['minLength' => '+minLength 1', 'maxLength' => 'maxLength 120 -> 127', 'pattern' => '-pattern'], $diff);
    }

    public function testDiffNamesKnownPatterns(): void
    {
        static::assertSame(['pattern' => '+pattern Pattern::DATE'], Facets::diff([], ['pattern' => Pattern::DATE]));
        static::assertSame(['pattern' => 'pattern Pattern::ALNUM -> Pattern::ALNUM_OR_EMPTY'], Facets::diff(['pattern' => Pattern::ALNUM], ['pattern' => Pattern::ALNUM_OR_EMPTY]));
        static::assertSame('UPPER_ALNUM', Facets::patternName('^[A-Z0-9]+$'));
        static::assertNull(Facets::patternName('^x$'));
    }

    public function testDiffKeepsLongValuesDistinct(): void
    {
        $long = str_repeat('a', 50);

        static::assertNotSame(
            Facets::diff([], ['pattern' => $long . 'b']),
            Facets::diff([], ['pattern' => $long . 'c']),
        );
    }
}
