<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Money;

#[OA\Schema(
    schema: 'paypal_v2_order_purchase_unit_supplementary_data_card_level2',
    required: ['invoiceId', 'taxTotal']
)]
class Level2 extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 127, pattern: Pattern::INVOICE_ID)]
    protected string $invoiceId;

    #[OA\Property(ref: Money::class)]
    protected Money $taxTotal;

    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    public function setInvoiceId(string $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function getTaxTotal(): Money
    {
        return $this->taxTotal;
    }

    public function setTaxTotal(Money $taxTotal): void
    {
        $this->taxTotal = $taxTotal;
    }
}
