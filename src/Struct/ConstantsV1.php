<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct;

class ConstantsV1
{
    public const INTENT_SALE = 'sale';
    public const INTENT_AUTHORIZE = 'authorize';
    public const INTENT_ORDER = 'order';

    public const STATUS_COMPLETED = 'completed';
    public const STATUS_AUTHORIZED = 'authorized';
    public const STATUS_VOIDED = 'voided';
    public const STATUS_CAPTURED = 'captured';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_DENIED = 'denied';
}
