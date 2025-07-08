<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_pricing_error_context')]
class PricingErrorContext extends AbstractContext
{
    public const SPECIFIC_ISSUES = [
        'PRICE_MISMATCH' => 'PRICE_MISMATCH',
        'DISCOUNT_EXPIRED' => 'DISCOUNT_EXPIRED',
        'DISCOUNT_USAGE_LIMIT_EXCEEDED' => 'DISCOUNT_USAGE_LIMIT_EXCEEDED',
        'DISCOUNT_CUSTOMER_INELIGIBLE' => 'DISCOUNT_CUSTOMER_INELIGIBLE',
        'DISCOUNT_MINIMUM_NOT_MET' => 'DISCOUNT_MINIMUM_NOT_MET',
        'TAX_CALCULATION_FAILED' => 'TAX_CALCULATION_FAILED',
        'CURRENCY_NOT_SUPPORTED' => 'CURRENCY_NOT_SUPPORTED',
        'CURRENCY_MISMATCH' => 'CURRENCY_MISMATCH',
        'PROMOTIONAL_CONFLICT' => 'PROMOTIONAL_CONFLICT',
    ];

    /**
     * Item with pricing issue
     */
    #[OA\Property(type: 'string')]
    protected ?string $itemId = null;

    /**
     * Original price value
     */
    #[OA\Property(type: 'string')]
    protected ?string $originalPrice = null;

    /**
     * Current price value
     */
    #[OA\Property(type: 'string')]
    protected ?string $currentPrice = null;

    /**
     * Currency code
     */
    #[OA\Property(type: 'string')]
    protected ?string $currencyCode = null;

    /**
     * Reason for price change
     */
    #[OA\Property(
        type: 'string',
        enum: ['promotional_ended', 'promotional_started', 'market_adjustment', 'cost_increase', 'seasonal_pricing', 'component_cost_increase', 'terms_updated']
    )]
    protected ?string $priceChangeReason = null;

    /**
     * Amount of price increase
     */
    #[OA\Property(type: 'string')]
    protected ?string $priceIncrease = null;

    /**
     * Amount of price decrease
     */
    #[OA\Property(type: 'string')]
    protected ?string $priceDecrease = null;

    /**
     * Coupon code with issues
     */
    #[OA\Property(type: 'string')]
    protected ?string $couponCode = null;

    /**
     * Coupon usage limit
     */
    #[OA\Property(type: 'integer')]
    protected ?int $usageLimit = null;

    /**
     * Current coupon usage count
     */
    #[OA\Property(type: 'integer')]
    protected ?int $currentUsage = null;

    /**
     * Discount expiration date
     */
    #[OA\Property(type: 'string')]
    protected ?string $expirationDate = null;

    /**
     * Minimum order for discount
     */
    #[OA\Property(type: 'string')]
    protected ?string $minimumOrderAmount = null;

    /**
     * List of supported currencies
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $supportedCurrencies = null;

    /**
     * Multiple currencies found in cart
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $foundCurrencies = null;

    /**
     * Tax calculation service error
     */
    #[OA\Property(type: 'string')]
    protected ?string $taxServiceError = null;

    /**
     * Current system date for comparisons
     */
    #[OA\Property(type: 'string')]
    protected ?string $currentDate = null;

    /**
     * Discount amount that was applied
     */
    #[OA\Property(type: 'string')]
    protected ?string $discountAmount = null;

    /**
     * Whether all items must use same currency
     */
    #[OA\Property(type: 'boolean')]
    protected ?bool $requiredCurrencyConsistency = null;

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
    protected ?array $mixedItems = null;

    public function getItemId(): ?string
    {
        return $this->itemId;
    }

    public function setItemId(?string $itemId): void
    {
        $this->itemId = $itemId;
    }

    public function getOriginalPrice(): ?string
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(?string $originalPrice): void
    {
        $this->originalPrice = $originalPrice;
    }

    public function getCurrentPrice(): ?string
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(?string $currentPrice): void
    {
        $this->currentPrice = $currentPrice;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(?string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getPriceChangeReason(): ?string
    {
        return $this->priceChangeReason;
    }

    public function setPriceChangeReason(?string $priceChangeReason): void
    {
        $this->priceChangeReason = $priceChangeReason;
    }

    public function getPriceIncrease(): ?string
    {
        return $this->priceIncrease;
    }

    public function setPriceIncrease(?string $priceIncrease): void
    {
        $this->priceIncrease = $priceIncrease;
    }

    public function getPriceDecrease(): ?string
    {
        return $this->priceDecrease;
    }

    public function setPriceDecrease(?string $priceDecrease): void
    {
        $this->priceDecrease = $priceDecrease;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function setCouponCode(?string $couponCode): void
    {
        $this->couponCode = $couponCode;
    }

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    public function setUsageLimit(?int $usageLimit): void
    {
        $this->usageLimit = $usageLimit;
    }

    public function getCurrentUsage(): ?int
    {
        return $this->currentUsage;
    }

    public function setCurrentUsage(?int $currentUsage): void
    {
        $this->currentUsage = $currentUsage;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?string $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getMinimumOrderAmount(): ?string
    {
        return $this->minimumOrderAmount;
    }

    public function setMinimumOrderAmount(?string $minimumOrderAmount): void
    {
        $this->minimumOrderAmount = $minimumOrderAmount;
    }

    /**
     * @return ?string[]
     */
    public function getSupportedCurrencies(): ?array
    {
        return $this->supportedCurrencies;
    }

    /**
     * @param ?string[] $supportedCurrencies
     */
    public function setSupportedCurrencies(?array $supportedCurrencies): void
    {
        $this->supportedCurrencies = $supportedCurrencies;
    }

    public function addSupportedCurrency(string $supportedCurrency): void
    {
        $this->supportedCurrencies[] = $supportedCurrency;
    }

    /**
     * @return ?string[]
     */
    public function getFoundCurrencies(): ?array
    {
        return $this->foundCurrencies;
    }

    /**
     * @param ?string[] $foundCurrencies
     */
    public function setFoundCurrencies(?array $foundCurrencies): void
    {
        $this->foundCurrencies = $foundCurrencies;
    }

    public function addFoundCurrency(string $foundCurrency): void
    {
        $this->foundCurrencies[] = $foundCurrency;
    }

    public function getTaxServiceError(): ?string
    {
        return $this->taxServiceError;
    }

    public function setTaxServiceError(?string $taxServiceError): void
    {
        $this->taxServiceError = $taxServiceError;
    }

    public function getCurrentDate(): ?string
    {
        return $this->currentDate;
    }

    public function setCurrentDate(?string $currentDate): void
    {
        $this->currentDate = $currentDate;
    }

    public function getDiscountAmount(): ?string
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?string $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getRequiredCurrencyConsistency(): ?bool
    {
        return $this->requiredCurrencyConsistency;
    }

    public function setRequiredCurrencyConsistency(?bool $requiredCurrencyConsistency): void
    {
        $this->requiredCurrencyConsistency = $requiredCurrencyConsistency;
    }

    /**
     * @return ?list<array{item_id: string, currency: string}>
     */
    public function getMixedItems(): ?array
    {
        return $this->mixedItems;
    }

    public function addMixedItem(string $itemId, string $currency): void
    {
        $this->mixedItems[] = [
            'item_id' => $itemId,
            'currency' => $currency,
        ];
    }

    public function resetMixedItems(): void
    {
        $this->mixedItems = [];
    }
}
