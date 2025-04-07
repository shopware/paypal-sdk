<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Plan\BillingCycle;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Money;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v1_plan_billing_cycle_pricing_scheme')]
class PricingScheme extends Struct
{
    #[OA\Property(ref: Money::class)]
    protected Money $fixedPrice;

    public function getFixedPrice(): Money
    {
        return $this->fixedPrice;
    }

    public function setFixedPrice(Money $fixedPrice): void
    {
        $this->fixedPrice = $fixedPrice;
    }
}
