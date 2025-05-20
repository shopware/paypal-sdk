<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Amount;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Item;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\ItemCollection;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payee;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit')]
class PurchaseUnit extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $referenceId;

    #[OA\Property(ref: Amount::class)]
    protected Amount $amount;

    #[OA\Property(ref: Payee::class)]
    protected Payee $payee;

    #[OA\Property(type: 'string')]
    protected string $description;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $customId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $invoiceId = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Item::class), nullable: true)]
    protected ?ItemCollection $items = null;

    #[OA\Property(ref: Shipping::class)]
    protected Shipping $shipping;

    #[OA\Property(ref: Payments::class, nullable: true)]
    protected ?Payments $payments = null;

    protected SupplementaryData $supplementaryData;

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function setReferenceId(string $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function setAmount(Amount $amount): void
    {
        $this->amount = $amount;
    }

    public function getPayee(): Payee
    {
        return $this->payee;
    }

    public function setPayee(Payee $payee): void
    {
        $this->payee = $payee;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function setCustomId(?string $customId): void
    {
        $this->customId = $customId;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId;
    }

    public function setInvoiceId(?string $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function getItems(): ?ItemCollection
    {
        return $this->items;
    }

    public function setItems(?ItemCollection $items): void
    {
        $this->items = $items;
    }

    public function getShipping(): Shipping
    {
        return $this->shipping;
    }

    public function setShipping(Shipping $shipping): void
    {
        $this->shipping = $shipping;
    }

    public function getPayments(): ?Payments
    {
        return $this->payments;
    }

    public function setPayments(?Payments $payments): void
    {
        $this->payments = $payments;
    }

    public function getSupplementaryData(): SupplementaryData
    {
        return $this->supplementaryData;
    }

    public function setSupplementaryData(SupplementaryData $supplementaryData): void
    {
        $this->supplementaryData = $supplementaryData;
    }
}
