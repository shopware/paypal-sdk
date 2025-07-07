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
 * Payment method information for PayPal Cart API. This API is specifically designed for PayPal's shopping cart service, so only PayPal payment methods are supported.
 *
 * Payment Flow:
 *
 * Cart creation generates a payment token (in payment_method.token)
 * Customer completes PayPal approval (Smart Wallet)
 * PayPal provides token and payer_id for checkout
 * Merchant receives PayPal payment confirmation
 *
 * Billing Address Behavior:
 *
 * PayPal handles all billing address collection internally for payment processing
 * Merchants can optionally collect billing addresses for tax calculation and business purposes
 * Billing address in cart is for merchant use cases, not payment requirements
 * Billing addresses are typically available from customer profile data
 *
 * Note: Other payment methods (credit cards, Apple Pay, etc.) would be handled by separate merchant payment systems outside of this PayPal Cart API.
 */
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_payment_method',
    required: ['type']
)]
class PaymentMethod extends Struct
{
    /**
     * Payment method type - only PayPal is supported by this API
     *
     * Enum: [ paypal ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['paypal']
    )]
    protected string $type;

    /**
     * PayPal payment token from cart creation or customer approval
     */
    #[OA\Property(type: 'string')]
    protected string $token;

    /**
     * PayPal payer identifier provided after customer approval
     */
    #[OA\Property(type: 'string')]
    protected string $payerId;

    /**
     * URL used to inform merchant that the PayPal buyer approved the order
     */
    #[OA\Property(type: 'string')]
    protected string $approvalUrl;
}
