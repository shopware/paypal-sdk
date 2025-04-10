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
                16,
            ],
            [
                'Property $snake_case_name should have a camelCased name of "snakeCaseName"',
                18,
            ],
            [
                'Property $missingNativeType should have a native type',
                20,
            ],
            [
                'Property $missingDefaultValue is nullable and should have an default value',
                22,
            ],
            [
                'Property $missingSetter should have a setter "setMissingSetter"',
                24,
            ],
            [
                'Property $missingGetter should have a getter "getMissingGetter"',
                26,
            ],
        ]);
    }

    public function testRuleSkipped(): void
    {
        $this->analyse([__DIR__ . '/../../../fixture/Struct/TestStruct.php'], []);
    }
}
