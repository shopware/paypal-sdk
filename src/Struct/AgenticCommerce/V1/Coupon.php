<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */

/**
 * Discount coupon for cart operations. Multiple coupons can be applied simultaneously, with merchant business rules determining stacking behavior, priorities, and conflicts.
 *
 * Common Scenarios:
 *
 * Apply multiple discount codes (percentage + fixed amount)
 * Stack loyalty discounts with promotional codes
 * Remove specific coupons while keeping others
 * Apply category-specific and store-wide discounts together
 *
 * Business Rules: Merchants define stacking rules, maximum discounts, exclusions, and priority orders. Invalid combinations return validation issues with suggested resolutions.
 */
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_coupon',
    required: ['code', 'action']
)]
class Coupon extends Struct
{
    public const APPLY = 'APPLY';
    public const REMOVE = 'REMOVE';

    /**
     * Coupon code identifier
     */
    #[OA\Property(type: 'string')]
    protected string $code;

    /**
     * Action to perform on this specific coupon
     *
     * Enum: [ APPLY, REMOVE ]
     */
    #[OA\Property(
        type: 'string',
        enum: [self::APPLY, self::REMOVE]
    )]
    protected string $action;

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        if (!\in_array($action, [self::APPLY, self::REMOVE], true)) {
            throw new \InvalidArgumentException('Invalid coupon code');
        }

        $this->action = $action;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}
