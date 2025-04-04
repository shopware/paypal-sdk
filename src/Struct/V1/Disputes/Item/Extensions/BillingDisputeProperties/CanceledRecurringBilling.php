<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\BillingDisputeProperties;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Money;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\BillingDisputeProperties\Common\CancellationDetails;

#[OA\Schema(schema: 'paypal_v1_disputes_item_extensions_billing_dispute_properties_canceled_recurring_billing')]
class CanceledRecurringBilling extends Struct
{
    #[OA\Property(ref: Money::class)]
    protected Money $expectedRefund;

    #[OA\Property(ref: CancellationDetails::class)]
    protected CancellationDetails $cancellationDetails;

    public function getExpectedRefund(): Money
    {
        return $this->expectedRefund;
    }

    public function setExpectedRefund(Money $expectedRefund): void
    {
        $this->expectedRefund = $expectedRefund;
    }

    public function getCancellationDetails(): CancellationDetails
    {
        return $this->cancellationDetails;
    }

    public function setCancellationDetails(CancellationDetails $cancellationDetails): void
    {
        $this->cancellationDetails = $cancellationDetails;
    }
}
