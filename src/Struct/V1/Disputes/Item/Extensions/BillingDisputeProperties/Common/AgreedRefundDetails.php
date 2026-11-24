<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\BillingDisputeProperties\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_disputes_item_extensions_billing_dispute_properties_common_agreed_refund_details')]
class AgreedRefundDetails extends Struct
{
    #[OA\Property(type: 'boolean')]
    protected bool $merchantAgreedRefund;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$')]
    protected string $merchantAgreedRefundTime;

    public function isMerchantAgreedRefund(): bool
    {
        return $this->merchantAgreedRefund;
    }

    public function setMerchantAgreedRefund(bool $merchantAgreedRefund): void
    {
        $this->merchantAgreedRefund = $merchantAgreedRefund;
    }

    public function getMerchantAgreedRefundTime(): string
    {
        return $this->merchantAgreedRefundTime;
    }

    public function setMerchantAgreedRefundTime(string $merchantAgreedRefundTime): void
    {
        $this->merchantAgreedRefundTime = $merchantAgreedRefundTime;
    }
}
