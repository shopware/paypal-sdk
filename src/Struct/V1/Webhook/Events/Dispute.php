<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Webhook\Events;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\DisputeAmount;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\DisputedTransaction;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\DisputedTransactionCollection;

#[OA\Schema(schema: 'paypal_v1_webhook_events_dispute')]
class Dispute extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $disputeId;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $merchantId = null;

    #[OA\Property(type: 'string')]
    protected string $reason;

    #[OA\Property(type: 'string')]
    protected string $status;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $disputeState = null;

    #[OA\Property(ref: DisputeAmount::class, nullable: true)]
    protected ?DisputeAmount $disputeAmount = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $sellerResponseDueDate = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $disputeLifeCycleStage = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: DisputedTransaction::class), nullable: true)]
    protected ?DisputedTransactionCollection $disputedTransactions = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected LinkCollection $links;

    public function getDisputeId(): string
    {
        return $this->disputeId;
    }

    public function setDisputeId(string $disputeId): void
    {
        $this->disputeId = $disputeId;
    }

    public function getMerchantId(): ?string
    {
        return $this->merchantId;
    }

    public function setMerchantId(?string $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getDisputeState(): ?string
    {
        return $this->disputeState;
    }

    public function setDisputeState(?string $disputeState): void
    {
        $this->disputeState = $disputeState;
    }

    public function getDisputeAmount(): ?DisputeAmount
    {
        return $this->disputeAmount;
    }

    public function setDisputeAmount(?DisputeAmount $disputeAmount): void
    {
        $this->disputeAmount = $disputeAmount;
    }

    public function getSellerResponseDueDate(): ?string
    {
        return $this->sellerResponseDueDate;
    }

    public function setSellerResponseDueDate(?string $sellerResponseDueDate): void
    {
        $this->sellerResponseDueDate = $sellerResponseDueDate;
    }

    public function getDisputeLifeCycleStage(): ?string
    {
        return $this->disputeLifeCycleStage;
    }

    public function setDisputeLifeCycleStage(?string $disputeLifeCycleStage): void
    {
        $this->disputeLifeCycleStage = $disputeLifeCycleStage;
    }

    public function getDisputedTransactions(): ?DisputedTransactionCollection
    {
        return $this->disputedTransactions;
    }

    public function setDisputedTransactions(?DisputedTransactionCollection $disputedTransactions): void
    {
        $this->disputedTransactions = $disputedTransactions;
    }

    public function getLinks(): LinkCollection
    {
        return $this->links;
    }

    public function setLinks(LinkCollection $links): void
    {
        $this->links = $links;
    }
}
