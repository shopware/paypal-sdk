<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_cart_info')]
class CartInfo extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: ItemDetail::class), nullable: true)]
    protected ?ItemDetailCollection $itemDetails = null;

    #[OA\Property(type: 'boolean', nullable: true)]
    protected ?bool $taxInclusive = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $paypalInvoiceId = null;

    public function getItemDetails(): ItemDetailCollection
    {
        return $this->itemDetails ?? $this->itemDetails = new ItemDetailCollection();
    }

    public function setItemDetails(?ItemDetailCollection $itemDetails): void
    {
        $this->itemDetails = $itemDetails;
    }

    public function getTaxInclusive(): ?bool
    {
        return $this->taxInclusive;
    }

    public function setTaxInclusive(?bool $taxInclusive): void
    {
        $this->taxInclusive = $taxInclusive;
    }

    public function getPaypalInvoiceId(): ?string
    {
        return $this->paypalInvoiceId;
    }

    public function setPaypalInvoiceId(?string $paypalInvoiceId): void
    {
        $this->paypalInvoiceId = $paypalInvoiceId;
    }
}
