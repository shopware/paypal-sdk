<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Integration\Test\PHPStan;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Shopware\PayPalSDK\Test\PHPStan\StructRule;

/**
 * @extends RuleTestCase<StructRule>
 */
#[CoversClass(StructRule::class)]
class StructRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new StructRule();
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/../../../fixture/Struct/PHPStanRuleStruct.php'], [
            [
                'Property $wrongVisibility should be protected',
                18,
            ],
            [
                'Property $snake_case_name should have a camelCased name of "snakeCaseName"',
                20,
            ],
            [
                'Property $missingNativeType should have a native type',
                22,
            ],
            [
                'Property $missingDefaultValue is nullable and should default to null',
                24,
            ],
            [
                'Property $missingSetter should have a setter "setMissingSetter"',
                26,
            ],
            [
                'Property $missingGetter should have a getter "getMissingGetter"',
                28,
            ],
            [
                'Property $notNullableCollection is a collection and should be nullable',
                40,
            ],
            [
                'Property $nonNullDefault should not have a default, unless it is @internal or its #[OA\\Property] enum allows only that value',
                47,
            ],
        ]);
    }

    public function testRuleSkipped(): void
    {
        $this->analyse([
            __DIR__ . '/../../../fixture/Struct/TestStruct.php',
            __DIR__ . '/../../../fixture/Struct/Builder/TestBuilder.php',
        ], []);
    }
}
