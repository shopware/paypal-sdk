<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_pay_pal_cart',
    required: ['items', 'paymentMethod']
)]
class PayPalCart extends Struct
{
    /**
     * Cart data is complete and valid, ready for checkout
     *
     * All items are available with current pricing
     * Required information is complete (shipping address, customer data)
     * No business rule violations
     * validation_issues array is empty
     * Can proceed directly to checkout
     */
    public const STATUS_VALID = 'VALID';

    /**
     * Cart has data issues that prevent checkout
     *
     * Items out of stock, price changes, business rule violations
     * Invalid or incomplete data that needs correction
     * validation_issues array contains specific problems
     * Customer/AI agent must resolve issues before checkout
     */
    public const STATUS_INVALID = 'INVALID';

    /**
     * Cart needs more data but is otherwise valid
     *
     * Missing optional but required fields (shipping address, checkout fields)
     * Age verification, custom fields, delivery preferences needed
     * Items and pricing are valid, just needs customer input
     * validation_issues array indicates what information is needed
     */
    public const STATUS_REQUIRES_ADDITIONAL_INFORMATION = 'REQUIRES_ADDITIONAL_INFORMATION';

    #[OA\Property(type: 'string', readOnly: true)]
    protected readonly string $id;

    #[OA\Property(
        type: 'string',
        enum: ['CREATED', 'INCOMPLETE', 'READY', 'COMPLETE'],
        readOnly: true,
    )]
    protected readonly string $status;

    #[OA\Property(
        type: 'string',
        enum: ['VALID', 'INVALID', 'REQUIRES_ADDITIONAL_INFORMATION'],
        readOnly: true,
    )]
    protected readonly string $validationStatus;

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
    protected readonly array $validationIssues;

    #[OA\Property(ref: CartTotals::class)]
    protected CartTotals $totals;

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
    protected readonly array $appliedCoupons;

    /**
     * Available shipping methods with selection state
     *
     * @var ShippingOption[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: ShippingOption::class),
    )]
    protected array $availableShippingOptions;

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
    protected readonly array $links;

    /**
     * Products in the cart
     *
     * minItems: 1
     *
     * @var non-empty-list<Cartitem>;
     */
    #[OA\Property(
        type: 'array',
        minItems: 1,
        items: new OA\Items(ref: Cartitem::class)
    )]
    protected array $items;

    #[OA\Property(ref: Customer::class)]
    protected Customer $customer;

    #[OA\Property(ref: ShippingAddress::class)]
    protected ShippingAddress $shippingAddress;

    #[OA\Property(ref: BillingAddress::class)]
    protected BillingAddress $billingAddress;

    #[OA\Property(ref: PaymentMethod::class)]
    protected PaymentMethod $paymentMethod;

    /**
     * Custom checkout fields (age verification, etc.)
     *
     * @var CheckoutField[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: CheckoutField::class)
    )]
    protected array $checkoutFields;

    /**
     * Discount coupons to apply or remove from cart
     *
     * @var Coupon[];
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: Coupon::class)
    )]
    protected array $coupons;

    #[OA\Property(ref: GeoCoordinates::class)]
    protected GeoCoordinates $geoCoordinates;
}
