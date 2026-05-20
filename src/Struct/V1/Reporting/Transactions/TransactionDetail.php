<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_transaction_detail')]
class TransactionDetail extends Struct
{
    #[OA\Property(ref: TransactionInfo::class, nullable: true)]
    protected ?TransactionInfo $transactionInfo = null;

    #[OA\Property(ref: PayerInfo::class, nullable: true)]
    protected ?PayerInfo $payerInfo = null;

    #[OA\Property(ref: ShippingInfo::class, nullable: true)]
    protected ?ShippingInfo $shippingInfo = null;

    #[OA\Property(ref: CartInfo::class, nullable: true)]
    protected ?CartInfo $cartInfo = null;

    #[OA\Property(ref: StoreInfo::class, nullable: true)]
    protected ?StoreInfo $storeInfo = null;

    #[OA\Property(ref: AuctionInfo::class, nullable: true)]
    protected ?AuctionInfo $auctionInfo = null;

    #[OA\Property(ref: IncentiveInfo::class, nullable: true)]
    protected ?IncentiveInfo $incentiveInfo = null;

    public function getTransactionInfo(): ?TransactionInfo
    {
        return $this->transactionInfo;
    }

    public function setTransactionInfo(?TransactionInfo $transactionInfo): void
    {
        $this->transactionInfo = $transactionInfo;
    }

    public function getPayerInfo(): ?PayerInfo
    {
        return $this->payerInfo;
    }

    public function setPayerInfo(?PayerInfo $payerInfo): void
    {
        $this->payerInfo = $payerInfo;
    }

    public function getShippingInfo(): ?ShippingInfo
    {
        return $this->shippingInfo;
    }

    public function setShippingInfo(?ShippingInfo $shippingInfo): void
    {
        $this->shippingInfo = $shippingInfo;
    }

    public function getCartInfo(): ?CartInfo
    {
        return $this->cartInfo;
    }

    public function setCartInfo(?CartInfo $cartInfo): void
    {
        $this->cartInfo = $cartInfo;
    }

    public function getStoreInfo(): ?StoreInfo
    {
        return $this->storeInfo;
    }

    public function setStoreInfo(?StoreInfo $storeInfo): void
    {
        $this->storeInfo = $storeInfo;
    }

    public function getAuctionInfo(): ?AuctionInfo
    {
        return $this->auctionInfo;
    }

    public function setAuctionInfo(?AuctionInfo $auctionInfo): void
    {
        $this->auctionInfo = $auctionInfo;
    }

    public function getIncentiveInfo(): ?IncentiveInfo
    {
        return $this->incentiveInfo;
    }

    public function setIncentiveInfo(?IncentiveInfo $incentiveInfo): void
    {
        $this->incentiveInfo = $incentiveInfo;
    }
}
