<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Money;

#[OA\Schema(schema: 'paypal_v1_disputes_common_transaction')]
class Transaction extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: '^[A-Za-z0-9-]+$')]
    protected string $buyerTransactionId;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: '^[A-Za-z0-9-]+$')]
    protected string $sellerTransactionId;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255)]
    protected string $referenceId;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$')]
    protected string $createTime;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: '^[0-9A-Z_]+$')]
    protected string $transactionStatus;

    #[OA\Property(ref: Money::class)]
    protected Money $grossAmount;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 127, pattern: '^[A-Za-z0-9:\\-|]+$')]
    protected string $invoiceNumber;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 2000, pattern: '^(.|\\r?\\n)*$')]
    protected string $custom;

    #[OA\Property(ref: Buyer::class)]
    protected Buyer $buyer;

    #[OA\Property(ref: Seller::class)]
    protected Seller $seller;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Item::class), minItems: 1, maxItems: 100)]
    protected ItemCollection $items;

    public function getBuyerTransactionId(): string
    {
        return $this->buyerTransactionId;
    }

    public function setBuyerTransactionId(string $buyerTransactionId): void
    {
        $this->buyerTransactionId = $buyerTransactionId;
    }

    public function getSellerTransactionId(): string
    {
        return $this->sellerTransactionId;
    }

    public function setSellerTransactionId(string $sellerTransactionId): void
    {
        $this->sellerTransactionId = $sellerTransactionId;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function setReferenceId(string $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getTransactionStatus(): string
    {
        return $this->transactionStatus;
    }

    public function setTransactionStatus(string $transactionStatus): void
    {
        $this->transactionStatus = $transactionStatus;
    }

    public function getGrossAmount(): Money
    {
        return $this->grossAmount;
    }

    public function setGrossAmount(Money $grossAmount): void
    {
        $this->grossAmount = $grossAmount;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function getCustom(): string
    {
        return $this->custom;
    }

    public function setCustom(string $custom): void
    {
        $this->custom = $custom;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function setBuyer(Buyer $buyer): void
    {
        $this->buyer = $buyer;
    }

    public function getSeller(): Seller
    {
        return $this->seller;
    }

    public function setSeller(Seller $seller): void
    {
        $this->seller = $seller;
    }

    public function getItems(): ItemCollection
    {
        return $this->items;
    }

    public function setItems(ItemCollection $items): void
    {
        $this->items = $items;
    }
}
