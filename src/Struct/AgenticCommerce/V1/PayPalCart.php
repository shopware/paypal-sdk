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
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_pay_pal_cart',
    required: ['items', 'paymentMethod']
)]
class PayPalCart extends Struct
{
    public const STATUS__CREATED = 'CREATED';
    public const STATUS__INCOMPLETE = 'INCOMPLETE';
    public const STATUS__READY = 'READY';
    public const STATUS__COMPLETE = 'COMPLETE';

    /**
     * Cart data is complete and valid, ready for checkout
     *
     * All items are available with current pricing
     * Required information is complete (shipping address, customer data)
     * No business rule violations
     * validation_issues array is empty
     * Can proceed directly to checkout
     */
    public const VALIDATION_STATUS__VALID = 'VALID';

    /**
     * Cart has data issues that prevent checkout
     *
     * Items out of stock, price changes, business rule violations
     * Invalid or incomplete data that needs correction
     * validation_issues array contains specific problems
     * Customer/AI agent must resolve issues before checkout
     */
    public const VALIDATION_STATUS__INVALID = 'INVALID';

    /**
     * Cart needs more data but is otherwise valid
     *
     * Missing optional but required fields (shipping address, checkout fields)
     * Age verification, custom fields, delivery preferences needed
     * Items and pricing are valid, just needs customer input
     * validation_issues array indicates what information is needed
     */
    public const VALIDATION_STATUS__REQUIRES_ADDITIONAL_INFORMATION = 'REQUIRES_ADDITIONAL_INFORMATION';

    private const STATUSES = [self::STATUS__CREATED, self::STATUS__COMPLETE, self::STATUS__READY, self::STATUS__INCOMPLETE];
    private const VALIDATION_STATUSES = [self::VALIDATION_STATUS__VALID, self::VALIDATION_STATUS__INVALID, self::VALIDATION_STATUS__REQUIRES_ADDITIONAL_INFORMATION];

    #[OA\Property(type: 'string', readOnly: true)]
    // TODO: need to be readonly?
    protected string $id;

    #[OA\Property(
        type: 'string',
        enum: self::STATUSES,
        readOnly: true,
    )]
    // TODO: need to be readonly?
    protected string $status;

    #[OA\Property(
        type: 'string',
        enum: self::VALIDATION_STATUSES,
        readOnly: true,
    )]
    // TODO: need to be readonly?
    protected string $validationStatus;

    /**
     * List of issues preventing checkout (empty = ready)
     *
     * @var ValidationIssue[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: ValidationIssue::class),
        readOnly: true,
    )]
    // TODO: need to be readonly?
    protected array $validationIssues;

    #[OA\Property(ref: CartTotals::class)]
    protected ?CartTotals $totals = null;

    /**
     * Successfully applied coupons (server-calculated)
     *
     * @var AppliedCoupon[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: AppliedCoupon::class),
        readOnly: true,
    )]
    // TODO: need to be readonly?
    protected array $appliedCoupons;

    /**
     * Available shipping methods with selection state
     *
     * @var ShippingOption[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: ShippingOption::class),
    )]
    protected ?array $availableShippingOptions = null;

    /**
     * HATEOAS navigation links for cart operations
     *
     * @var Link[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: Link::class),
        readOnly: true,
    )]
    // TODO: need to be readonly?
    protected array $links;

    /**
     * Products in the cart
     *
     * minItems: 1
     *
     * @var non-empty-list<CartItem>
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: CartItem::class),
        minItems: 1
    )]
    protected array $items;

    #[OA\Property(ref: Customer::class)]
    protected ?Customer $customer = null;

    #[OA\Property(ref: ShippingAddress::class)]
    protected ?ShippingAddress $shippingAddress = null;

    #[OA\Property(ref: BillingAddress::class)]
    protected ?BillingAddress $billingAddress = null;

    #[OA\Property(ref: PaymentMethod::class)]
    protected ?PaymentMethod $paymentMethod = null;

    /**
     * Custom checkout fields (age verification, etc.)
     *
     * @var CheckoutField[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: CheckoutField::class)
    )]
    protected ?array $checkoutFields = null;

    /**
     * Discount coupons to apply or remove from cart
     *
     * @var Coupon[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: Coupon::class)
    )]
    protected ?array $coupons = null;

    #[OA\Property(ref: GeoCoordinates::class)]
    protected ?GeoCoordinates $geoCoordinates = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getValidationStatus(): string
    {
        return $this->validationStatus;
    }

    public function setValidationStatus(string $validationStatus): void
    {
        $this->validationStatus = $validationStatus;
    }

    /**
     * @return ValidationIssue[]
     */
    public function getValidationIssues(): array
    {
        return $this->validationIssues;
    }

    /**
     * @param ValidationIssue[] $validationIssues
     */
    public function setValidationIssues(array $validationIssues): void
    {
        $this->validationIssues = $validationIssues;
    }

    /**
     * @return AppliedCoupon[]
     */
    public function getAppliedCoupons(): array
    {
        return $this->appliedCoupons;
    }

    /**
     * @param AppliedCoupon[] $appliedCoupons
     */
    public function setAppliedCoupons(array $appliedCoupons): void
    {
        $this->appliedCoupons = $appliedCoupons;
    }

    public function getTotals(): ?CartTotals
    {
        return $this->totals;
    }

    public function setTotals(?CartTotals $totals): void
    {
        $this->totals = $totals;
    }

    /**
     * @return ?ShippingOption[]
     */
    public function getAvailableShippingOptions(): ?array
    {
        return $this->availableShippingOptions;
    }

    /**
     * @param ?ShippingOption[] $availableShippingOptions
     */
    public function setAvailableShippingOptions(?array $availableShippingOptions): void
    {
        $this->availableShippingOptions = $availableShippingOptions;
    }

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param Link[] $links
     */
    public function setLinks(array $links): void
    {
        $this->links = $links;
    }

    /**
     * @return non-empty-list<CartItem>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param non-empty-list<CartItem> $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getShippingAddress(): ?ShippingAddress
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?ShippingAddress $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getBillingAddress(): ?BillingAddress
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?BillingAddress $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return ?CheckoutField[]
     */
    public function getCheckoutFields(): ?array
    {
        return $this->checkoutFields;
    }

    /**
     * @param ?CheckoutField[] $checkoutFields
     */
    public function setCheckoutFields(?array $checkoutFields): void
    {
        $this->checkoutFields = $checkoutFields;
    }

    /**
     * @return ?Coupon[]
     */
    public function getCoupons(): ?array
    {
        return $this->coupons;
    }

    /**
     * @param ?Coupon[] $coupons
     */
    public function setCoupons(?array $coupons): void
    {
        $this->coupons = $coupons;
    }

    public function getGeoCoordinates(): ?GeoCoordinates
    {
        return $this->geoCoordinates;
    }

    public function setGeoCoordinates(?GeoCoordinates $geoCoordinates): void
    {
        $this->geoCoordinates = $geoCoordinates;
    }
}
