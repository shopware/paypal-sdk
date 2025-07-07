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
 * Comprehensive cart pricing breakdown calculated by merchant and returned in all cart responses. All fields are merchant-owned and calculated based on business logic, inventory, shipping rules, and tax regulations.
 *
 * Merchant Responsibility:
 *
 * Calculate all totals based on items, shipping address, and business rules
 * Ensure accuracy for tax compliance and customer transparency
 * Handle currency consistency across all money fields
 * Apply discounts, shipping rates, and custom charges appropriately
 *
 * Field Calculation Guidelines:
 *
 * subtotal: Sum of all item prices × quantities (before discounts)
 * discount: Total amount saved from coupons, promotions, and discounts
 * shipping: Calculated shipping cost based on address and selected method
 * tax: Sales tax, VAT, or other applicable taxes based on billing/shipping jurisdiction
 * handling: Processing fees, packaging costs, or handling charges
 * insurance: Optional shipping insurance costs
 * shipping_discount: Discounts applied specifically to shipping costs
 * custom_charges: Additional fees like gift wrapping, expedited processing, etc.
 * total: Final amount customer pays (subtotal - discount + shipping + tax + handling + insurance - shipping_discount + custom_charges)
 *
 * PayPal Orders API Integration: When creating PayPal orders, custom_charges are typically rolled into the handling field or added as separate line items. The total amount must match the PayPal order total for successful payment capture. Fields map to PayPal Orders API breakdown structure where supported.
 */
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_cart_totals',
    required: ['total']
)]
class CartTotals extends Struct
{
    #[OA\Property(ref: Money::class)]
    protected Money $subtotal;

    #[OA\Property(ref: Money::class)]
    protected Money $discount;

    #[OA\Property(ref: Money::class)]
    protected Money $shipping;

    #[OA\Property(ref: Money::class)]
    protected Money $tax;

    #[OA\Property(ref: Money::class)]
    protected Money $handling;

    #[OA\Property(ref: Money::class)]
    protected Money $insurance;

    #[OA\Property(ref: Money::class)]
    protected Money $shippingDiscount;

    #[OA\Property(ref: Money::class)]
    protected Money $customCharges;

    #[OA\Property(ref: Money::class)]
    protected Money $total;
}
