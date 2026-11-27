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
use Shopware\PayPalSDK\Test\PHPStan\StructMethodRule;

/**
 * @extends RuleTestCase<StructMethodRule>
 */
#[CoversClass(StructMethodRule::class)]
class StructMethodRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new StructMethodRule();
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/../../../fixture/Struct/PHPStanRuleStruct.php'], [
            [
                'Method setSetterNotVoid should return void',
                121,
            ],
            [
                'Method getWrongGetSetTypes should return Shopware\\PayPalSDK\\Struct\\Struct but returns stdClass',
                128,
            ],
            [
                'Method setWrongGetSetTypes should have first param accept Shopware\\PayPalSDK\\Struct\\Struct but accepts stdClass',
                133,
            ],
            [
                'Method getWrongGetSetSignature should be public',
                137,
            ],
            [
                'Method getWrongGetSetSignature should return Shopware\\PayPalSDK\\Struct\\Struct but returns void',
                137,
            ],
            [
                'Method getWrongGetSetSignature should have no parameters',
                137,
            ],
            [
                'Method setWrongGetSetSignature should have a setter with one parameter',
                141,
            ],
            [
                'Method getNullableCollectionGetter should not return null for a list',
                155,
            ],
            [
                'Method getNullableArrayGetter should not return null for a list',
                168,
            ],
        ]);
    }

    public function testRuleSkipped(): void
    {
        $this->analyse([__DIR__ . '/../../../fixture/Struct/TestStruct.php'], []);
    }
}
