<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_pricing_error_context')]
class PricingErrorContext extends Struct implements ContextInterface
{
    /**
     * Specific pricing issue type
     *
     * Enum: [ PRICE_MISMATCH, DISCOUNT_EXPIRED, DISCOUNT_USAGE_LIMIT_EXCEEDED, DISCOUNT_CUSTOMER_INELIGIBLE, DISCOUNT_MINIMUM_NOT_MET, TAX_CALCULATION_FAILED, CURRENCY_NOT_SUPPORTED, CURRENCY_MISMATCH, PROMOTIONAL_CONFLICT ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['PRICE_MISMATCH', 'DISCOUNT_EXPIRED', 'DISCOUNT_USAGE_LIMIT_EXCEEDED', 'DISCOUNT_CUSTOMER_INELIGIBLE', 'DISCOUNT_MINIMUM_NOT_MET', 'TAX_CALCULATION_FAILED', 'CURRENCY_NOT_SUPPORTED', 'CURRENCY_MISMATCH', 'PROMOTIONAL_CONFLICT']
    )]
    protected string $specificIssue;

    /**
     * Item with pricing issue
     */
    #[OA\Property(type: 'string')]
    protected string $itemId;

    /**
     * Original price value
     */
    #[OA\Property(type: 'string')]
    protected string $originalPrice;

    /**
     * Current price value
     */
    #[OA\Property(type: 'string')]
    protected string $currentPrice;

    /**
     * Currency code
     */
    #[OA\Property(type: 'string')]
    protected string $currencyCode;

    /**
     * Reason for price change
     *
     * Enum: [ promotional_ended, promotional_started, market_adjustment, cost_increase, seasonal_pricing, component_cost_increase, terms_updated ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['promotional_ended', 'promotional_started', 'market_adjustment', 'cost_increase', 'seasonal_pricing', 'component_cost_increase', 'terms_updated']
    )]
    protected string $priceChangeReason;

    /**
     * Amount of price increase
     */
    #[OA\Property(type: 'string')]
    protected string $priceIncrease;

    /**
     * Amount of price decrease
     */
    #[OA\Property(type: 'string')]
    protected string $priceDecrease;

    /**
     * Coupon code with issues
     */
    #[OA\Property(type: 'string')]
    protected string $couponCode;

    /**
     * Coupon usage limit
     */
    #[OA\Property(type: 'integer')]
    protected int $usageLimit;

    /**
     * Current coupon usage count
     */
    #[OA\Property(type: 'integer')]
    protected int $currentUsage;

    /**
     * Discount expiration date
     */
    #[OA\Property(type: 'string')]
    protected string $expirationDate;

    /**
     * Minimum order for discount
     */
    #[OA\Property(type: 'string')]
    protected string $minimumOrderAmount;

    /**
     * List of supported currencies
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $supportedCurrencies;

    /**
     * Multiple currencies found in cart
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $foundCurrencies;

    /**
     * Tax calculation service error
     */
    #[OA\Property(type: 'string')]
    protected string $taxServiceError;

    /**
     * Current system date for comparisons
     */
    #[OA\Property(type: 'string')]
    protected string $currentDate;

    /**
     * Discount amount that was applied
     */
    #[OA\Property(type: 'string')]
    protected string $discountAmount;

    /**
     * Whether all items must use same currency
     */
    #[OA\Property(type: 'boolean')]
    protected bool $requiredCurrencyConsistency;

    /**
     * Items with different currencies
     *
     * @var list<array{item_id: string, currency: string}>
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['item_id', 'currency'],
            properties: [
                new OA\Property(property: 'item_id', type: 'string'),
                new OA\Property(property: 'currency', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected array $mixedItems;
}
