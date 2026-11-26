<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct;

class ConstantsV1
{
    public const string INTENT_SALE = 'sale';
    public const string INTENT_AUTHORIZE = 'authorize';
    public const string INTENT_ORDER = 'order';

    public const string STATUS_COMPLETED = 'completed';
    public const string STATUS_AUTHORIZED = 'authorized';
    public const string STATUS_VOIDED = 'voided';
    public const string STATUS_CAPTURED = 'captured';
    public const string STATUS_PENDING = 'pending';
    public const string STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    public const string STATUS_REFUNDED = 'refunded';
    public const string STATUS_DENIED = 'denied';
}
