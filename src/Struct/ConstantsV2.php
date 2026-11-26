<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct;

class ConstantsV2
{
    public const string INTENT_CAPTURE = 'CAPTURE';
    public const string INTENT_AUTHORIZE = 'AUTHORIZE';

    public const string ORDER_CREATED = 'CREATED';
    public const string ORDER_SAVED = 'SAVED';
    public const string ORDER_APPROVED = 'APPROVED';
    public const string ORDER_VOIDED = 'VOIDED';
    public const string ORDER_COMPLETED = 'COMPLETED';
    public const string ORDER_PAYER_ACTION_REQUIRED = 'PAYER_ACTION_REQUIRED';
    /**
     * Order status for Pay Upon Invoice: payer checkout is done, but PayPal approval is still pending.
     * Distinct from capture/authorization pending states ({@see self::ORDER_CAPTURE_PENDING}, {@see self::ORDER_AUTHORIZATION_PENDING}).
     */
    public const string ORDER_PENDING_APPROVAL = 'PENDING_APPROVAL';

    public const string ORDER_CAPTURE_COMPLETED = 'COMPLETED';
    public const string ORDER_CAPTURE_DECLINED = 'DECLINED';
    public const string ORDER_CAPTURE_PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED';
    public const string ORDER_CAPTURE_PENDING = 'PENDING';
    public const string ORDER_CAPTURE_REFUNDED = 'REFUNDED';
    public const string ORDER_CAPTURE_FAILED = 'FAILED';

    public const string ORDER_AUTHORIZATION_CREATED = 'CREATED';
    public const string ORDER_AUTHORIZATION_CAPTURED = 'CAPTURED';
    public const string ORDER_AUTHORIZATION_DENIED = 'DENIED';
    public const string ORDER_AUTHORIZATION_PARTIALLY_CAPTURED = 'PARTIALLY_CAPTURED';
    public const string ORDER_AUTHORIZATION_VOIDED = 'VOIDED';
    public const string ORDER_AUTHORIZATION_PENDING = 'PENDING';

    public const string ORDER_REFUND_CANCELLED = 'CANCELLED';
    public const string ORDER_REFUND_FAILED = 'FAILED';
    public const string ORDER_REFUND_PENDING = 'PENDING';
    public const string ORDER_REFUND_COMPLETED = 'COMPLETED';
}
