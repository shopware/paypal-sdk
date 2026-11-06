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
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\PaymentInstruction;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\ShippingOption;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\ShippingOptionCollection;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit')]
class PurchaseUnit extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $referenceId;

    #[OA\Property(ref: Amount::class, nullable: true)]
    protected ?Amount $amount;

    #[OA\Property(ref: Payee::class, nullable: true)]
    protected ?Payee $payee;

    #[OA\Property(ref: PaymentInstruction::class, nullable: true)]
    protected ?PaymentInstruction $paymentInstruction;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $description;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $customId;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $invoiceId;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Item::class), nullable: true)]
    protected ?ItemCollection $items;

    #[OA\Property(ref: Shipping::class, nullable: true)]
    protected ?Shipping $shipping;

    #[OA\Property(ref: Payments::class, nullable: true)]
    protected ?Payments $payments;

    #[OA\Property(ref: SupplementaryData::class, nullable: true)]
    protected ?SupplementaryData $supplementaryData;

    #[OA\Property(type: 'array', items: new OA\Items(ref: ShippingOption::class), nullable: true)]
    protected ?ShippingOptionCollection $shippingOptions;

    public function getReferenceId(): ?string
    {
        return $this->referenceId ?? null;
    }

    public function setReferenceId(?string $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    public function getAmount(): ?Amount
    {
        return $this->amount ?? null;
    }

    public function setAmount(?Amount $amount): void
    {
        $this->amount = $amount;
    }

    public function getPayee(): ?Payee
    {
        return $this->payee ?? null;
    }

    public function setPayee(?Payee $payee): void
    {
        $this->payee = $payee;
    }

    public function getPaymentInstruction(): ?PaymentInstruction
    {
        return $this->paymentInstruction ?? null;
    }

    public function setPaymentInstruction(?PaymentInstruction $paymentInstruction): void
    {
        $this->paymentInstruction = $paymentInstruction;
    }

    public function getDescription(): ?string
    {
        return $this->description ?? null;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCustomId(): ?string
    {
        return $this->customId ?? null;
    }

    public function setCustomId(?string $customId): void
    {
        $this->customId = $customId;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId ?? null;
    }

    public function setInvoiceId(?string $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function getItems(): ?ItemCollection
    {
        return $this->items ?? null;
    }

    public function setItems(?ItemCollection $items): void
    {
        $this->items = $items;
    }

    public function getShipping(): ?Shipping
    {
        return $this->shipping ?? null;
    }

    public function setShipping(?Shipping $shipping): void
    {
        $this->shipping = $shipping;
    }

    public function getPayments(): ?Payments
    {
        return $this->payments ?? null;
    }

    public function setPayments(?Payments $payments): void
    {
        $this->payments = $payments;
    }

    public function getSupplementaryData(): ?SupplementaryData
    {
        return $this->supplementaryData ?? null;
    }

    public function setSupplementaryData(?SupplementaryData $supplementaryData): void
    {
        $this->supplementaryData = $supplementaryData;
    }

    public function getShippingOptions(): ?ShippingOptionCollection
    {
        return $this->shippingOptions ?? null;
    }

    public function setShippingOptions(?ShippingOptionCollection $shippingOptions): void
    {
        $this->shippingOptions = $shippingOptions;
    }
}
