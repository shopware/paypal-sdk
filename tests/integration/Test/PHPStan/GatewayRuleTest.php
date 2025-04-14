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
use Shopware\PayPalSDK\Contract\Gateway\GatewayInterface;
use Shopware\PayPalSDK\Test\PHPStan\GatewayRule;

/**
 * @extends RuleTestCase<GatewayRule>
 */
#[CoversClass(GatewayRule::class)]
class GatewayRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new GatewayRule();
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/../../../fixture/Gateway/PHPStanRuleGateway.php'], [
            [
                'Should implement ' . GatewayInterface::class,
                10,
            ],
        ]);
    }

    public function testRuleSkipped(): void
    {
        $this->analyse([__DIR__ . '/../../../fixture/Struct/TestStruct.php'], []);
    }
}
