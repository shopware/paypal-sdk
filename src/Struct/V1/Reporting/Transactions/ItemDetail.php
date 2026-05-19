<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Reporting\Money;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_item_detail')]
class ItemDetail extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $itemCode = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $itemName = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $itemDescription = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $itemOptions = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $itemQuantity = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $itemUnitPrice = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $itemAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $discountAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $adjustmentAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $giftWrapAmount = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $taxPercentage = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: TaxAmount::class), nullable: true)]
    protected ?TaxAmountCollection $taxAmounts = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $basicShippingAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $extraShippingAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $handlingAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $insuranceAmount = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $totalItemAmount = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $invoiceNumber = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: CheckoutOption::class), nullable: true)]
    protected ?CheckoutOptionCollection $checkoutOptions = null;

    public function getItemCode(): ?string
    {
        return $this->itemCode;
    }

    public function setItemCode(?string $itemCode): void
    {
        $this->itemCode = $itemCode;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(?string $itemName): void
    {
        $this->itemName = $itemName;
    }

    public function getItemDescription(): ?string
    {
        return $this->itemDescription;
    }

    public function setItemDescription(?string $itemDescription): void
    {
        $this->itemDescription = $itemDescription;
    }

    public function getItemOptions(): ?string
    {
        return $this->itemOptions;
    }

    public function setItemOptions(?string $itemOptions): void
    {
        $this->itemOptions = $itemOptions;
    }

    public function getItemQuantity(): ?string
    {
        return $this->itemQuantity;
    }

    public function setItemQuantity(?string $itemQuantity): void
    {
        $this->itemQuantity = $itemQuantity;
    }

    public function getItemUnitPrice(): ?Money
    {
        return $this->itemUnitPrice;
    }

    public function setItemUnitPrice(?Money $itemUnitPrice): void
    {
        $this->itemUnitPrice = $itemUnitPrice;
    }

    public function getItemAmount(): ?Money
    {
        return $this->itemAmount;
    }

    public function setItemAmount(?Money $itemAmount): void
    {
        $this->itemAmount = $itemAmount;
    }

    public function getDiscountAmount(): ?Money
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?Money $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getAdjustmentAmount(): ?Money
    {
        return $this->adjustmentAmount;
    }

    public function setAdjustmentAmount(?Money $adjustmentAmount): void
    {
        $this->adjustmentAmount = $adjustmentAmount;
    }

    public function getGiftWrapAmount(): ?Money
    {
        return $this->giftWrapAmount;
    }

    public function setGiftWrapAmount(?Money $giftWrapAmount): void
    {
        $this->giftWrapAmount = $giftWrapAmount;
    }

    public function getTaxPercentage(): ?string
    {
        return $this->taxPercentage;
    }

    public function setTaxPercentage(?string $taxPercentage): void
    {
        $this->taxPercentage = $taxPercentage;
    }

    public function getTaxAmounts(): TaxAmountCollection
    {
        return $this->taxAmounts ?? $this->taxAmounts = new TaxAmountCollection();
    }

    public function setTaxAmounts(?TaxAmountCollection $taxAmounts): void
    {
        $this->taxAmounts = $taxAmounts;
    }

    public function getBasicShippingAmount(): ?Money
    {
        return $this->basicShippingAmount;
    }

    public function setBasicShippingAmount(?Money $basicShippingAmount): void
    {
        $this->basicShippingAmount = $basicShippingAmount;
    }

    public function getExtraShippingAmount(): ?Money
    {
        return $this->extraShippingAmount;
    }

    public function setExtraShippingAmount(?Money $extraShippingAmount): void
    {
        $this->extraShippingAmount = $extraShippingAmount;
    }

    public function getHandlingAmount(): ?Money
    {
        return $this->handlingAmount;
    }

    public function setHandlingAmount(?Money $handlingAmount): void
    {
        $this->handlingAmount = $handlingAmount;
    }

    public function getInsuranceAmount(): ?Money
    {
        return $this->insuranceAmount;
    }

    public function setInsuranceAmount(?Money $insuranceAmount): void
    {
        $this->insuranceAmount = $insuranceAmount;
    }

    public function getTotalItemAmount(): ?Money
    {
        return $this->totalItemAmount;
    }

    public function setTotalItemAmount(?Money $totalItemAmount): void
    {
        $this->totalItemAmount = $totalItemAmount;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function getCheckoutOptions(): CheckoutOptionCollection
    {
        return $this->checkoutOptions ?? $this->checkoutOptions = new CheckoutOptionCollection();
    }

    public function setCheckoutOptions(?CheckoutOptionCollection $checkoutOptions): void
    {
        $this->checkoutOptions = $checkoutOptions;
    }
}
