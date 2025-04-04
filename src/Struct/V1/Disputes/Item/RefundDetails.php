<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Money;

#[OA\Schema(schema: 'paypal_v1_disputes_item_refund_details')]
class RefundDetails extends Struct
{
    #[OA\Property(ref: Money::class)]
    protected Money $allowedRefundAmount;

    public function getAllowedRefundAmount(): Money
    {
        return $this->allowedRefundAmount;
    }

    public function setAllowedRefundAmount(Money $allowedRefundAmount): void
    {
        $this->allowedRefundAmount = $allowedRefundAmount;
    }
}
