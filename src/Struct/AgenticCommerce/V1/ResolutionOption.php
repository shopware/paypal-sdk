<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_resolution_option',
    required: ['action', 'label']
)]
class ResolutionOption
{
    /**
     * Machine-readable action identifier
     *
     * Enum: [ REDIRECT_TO_MERCHANT, MODIFY_CART, ACCEPT_NEW_PRICE, ACCEPT_BACK_ORDER, SUGGEST_ALTERNATIVE, REMOVE_ITEM, UPDATE_ADDRESS, PROVIDE_MISSING_FIELD, USE_DIFFERENT_PAYMENT, SPLIT_ORDER, CONTACT_SUPPORT, RETRY_LATER, REQUEST_APPROVAL, WAIT_FOR_RESTOCK, USE_DIFFERENT_CURRENCY, ACCEPT_PRE_ORDER, UPDATE_SHIPPING_METHOD, ACCEPT_TERMS, VERIFY_ACCOUNT, APPLY_DIFFERENT_COUPON, REMOVE_COUPON, CHOOSE_DIFFERENT_VARIANT ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['REDIRECT_TO_MERCHANT', 'MODIFY_CART', 'ACCEPT_NEW_PRICE', 'ACCEPT_BACK_ORDER', 'SUGGEST_ALTERNATIVE', 'REMOVE_ITEM', 'UPDATE_ADDRESS', 'PROVIDE_MISSING_FIELD', 'USE_DIFFERENT_PAYMENT', 'SPLIT_ORDER', 'CONTACT_SUPPORT', 'RETRY_LATER', 'REQUEST_APPROVAL', 'WAIT_FOR_RESTOCK', 'USE_DIFFERENT_CURRENCY', 'ACCEPT_PRE_ORDER', 'UPDATE_SHIPPING_METHOD', 'ACCEPT_TERMS', 'VERIFY_ACCOUNT', 'APPLY_DIFFERENT_COUPON', 'REMOVE_COUPON', 'CHOOSE_DIFFERENT_VARIANT']
    )]
    protected string $action;

    /**
     * Human-readable action label
     */
    #[OA\Property(type: 'string')]
    protected string $label;

    /**
     * URL to redirect to for resolution
     */
    #[OA\Property(type: 'string')]
    protected string $url;

    /**
     * Additional action metadata
     *
     * @var array{cost_impact?: string, priority?: string, auto_applicable?: bool, estimated_time?: string, redirect_requeired?: bool} $metadata
     */
    #[OA\Property(type: 'array')]
    // TODO: OA Property array structure
    protected array $metadata;
}
