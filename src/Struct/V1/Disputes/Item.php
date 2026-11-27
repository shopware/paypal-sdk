<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Adjudication;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\AdjudicationCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\CommunicationDetails;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\DisputeAmount;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\DisputedTransaction;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\DisputedTransactionCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\DisputeOutcome;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\EvidenceCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Message;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\MessageCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\MoneyMovement;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\MoneyMovementCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Offer;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\PartnerAction;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\PartnerActionCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\RefundDetails;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\SupportingInfo;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\SupportingInfoCollection;

#[OA\Schema(
    schema: 'paypal_v1_disputes_item',
    required: [
        'disputeId',
        'createTime',
        'updateTime',
        'reason',
        'status',
        'disputeAmount',
        'disputeLifeCycleStage',
        'extensions',
    ]
)]
class Item extends Struct
{
    public const string DISPUTE_STATE_REQUIRED_ACTION = 'REQUIRED_ACTION';
    public const string DISPUTE_STATE_REQUIRED_OTHER_PARTY_ACTION = 'REQUIRED_OTHER_PARTY_ACTION';
    public const string DISPUTE_STATE_UNDER_PAYPAL_REVIEW = 'UNDER_PAYPAL_REVIEW';
    public const string DISPUTE_STATE_RESOLVED = 'RESOLVED';
    public const string DISPUTE_STATE_OPEN_INQUIRIES = 'OPEN_INQUIRIES';
    public const string DISPUTE_STATE_APPEALABLE = 'APPEALABLE';

    public const array DISPUTE_STATES = [
        self::DISPUTE_STATE_REQUIRED_ACTION,
        self::DISPUTE_STATE_REQUIRED_OTHER_PARTY_ACTION,
        self::DISPUTE_STATE_UNDER_PAYPAL_REVIEW,
        self::DISPUTE_STATE_RESOLVED,
        self::DISPUTE_STATE_OPEN_INQUIRIES,
        self::DISPUTE_STATE_APPEALABLE,
    ];

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::ALNUM_HYPHEN)]
    protected string $disputeId;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected string $createTime;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected string $updateTime;

    #[OA\Property(type: 'array', items: new OA\Items(ref: DisputedTransaction::class), minItems: 1, maxItems: 1000)]
    protected ?DisputedTransactionCollection $disputedTransactions = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $reason;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $status;

    #[OA\Property(type: 'string', enum: self::DISPUTE_STATES, minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected ?string $disputeState = null;

    #[OA\Property(ref: DisputeAmount::class)]
    protected DisputeAmount $disputeAmount;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 2000, pattern: Pattern::ALNUM_DOT_UNDERSCORE_HYPHEN)]
    protected ?string $externalReasonCode = null;

    #[OA\Property(ref: DisputeOutcome::class)]
    protected ?DisputeOutcome $disputeOutcome = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Adjudication::class), minItems: 1, maxItems: 10)]
    protected ?AdjudicationCollection $adjudications = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: MoneyMovement::class), minItems: 1, maxItems: 50)]
    protected ?MoneyMovementCollection $moneyMovements = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $disputeLifeCycleStage;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::DISPUTE_CHANNEL)]
    protected ?string $disputeChannel = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Message::class), minItems: 1, maxItems: 1000)]
    protected ?MessageCollection $messages = null;

    #[OA\Property(ref: Extensions::class)]
    protected Extensions $extensions;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Evidence::class), minItems: 1, maxItems: 100)]
    protected ?EvidenceCollection $evidences = null;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected ?string $buyerResponseDueDate = null;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected ?string $sellerResponseDueDate = null;

    #[OA\Property(ref: Offer::class)]
    protected ?Offer $offer = null;

    #[OA\Property(ref: RefundDetails::class)]
    protected ?RefundDetails $refundDetails = null;

    #[OA\Property(ref: CommunicationDetails::class)]
    protected ?CommunicationDetails $communicationDetails = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: PartnerAction::class))]
    protected ?PartnerActionCollection $partnerActions = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: SupportingInfo::class), minItems: 1, maxItems: 100)]
    protected ?SupportingInfoCollection $supportingInfo = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class), minItems: 1, maxItems: 10)]
    protected ?LinkCollection $links = null;

    public function getDisputeId(): string
    {
        return $this->disputeId;
    }

    public function setDisputeId(string $disputeId): void
    {
        $this->disputeId = $disputeId;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getDisputedTransactions(): DisputedTransactionCollection
    {
        return $this->disputedTransactions ??= new DisputedTransactionCollection();
    }

    public function setDisputedTransactions(?DisputedTransactionCollection $disputedTransactions): void
    {
        $this->disputedTransactions = $disputedTransactions;
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

    public function getDisputeAmount(): DisputeAmount
    {
        return $this->disputeAmount;
    }

    public function setDisputeAmount(DisputeAmount $disputeAmount): void
    {
        $this->disputeAmount = $disputeAmount;
    }

    public function getExternalReasonCode(): ?string
    {
        return $this->externalReasonCode;
    }

    public function setExternalReasonCode(?string $externalReasonCode): void
    {
        $this->externalReasonCode = $externalReasonCode;
    }

    public function getDisputeOutcome(): ?DisputeOutcome
    {
        return $this->disputeOutcome;
    }

    public function setDisputeOutcome(?DisputeOutcome $disputeOutcome): void
    {
        $this->disputeOutcome = $disputeOutcome;
    }

    public function getAdjudications(): AdjudicationCollection
    {
        return $this->adjudications ??= new AdjudicationCollection();
    }

    public function setAdjudications(?AdjudicationCollection $adjudications): void
    {
        $this->adjudications = $adjudications;
    }

    public function getMoneyMovements(): MoneyMovementCollection
    {
        return $this->moneyMovements ??= new MoneyMovementCollection();
    }

    public function setMoneyMovements(?MoneyMovementCollection $moneyMovements): void
    {
        $this->moneyMovements = $moneyMovements;
    }

    public function getDisputeLifeCycleStage(): string
    {
        return $this->disputeLifeCycleStage;
    }

    public function setDisputeLifeCycleStage(string $disputeLifeCycleStage): void
    {
        $this->disputeLifeCycleStage = $disputeLifeCycleStage;
    }

    public function getDisputeChannel(): ?string
    {
        return $this->disputeChannel;
    }

    public function setDisputeChannel(?string $disputeChannel): void
    {
        $this->disputeChannel = $disputeChannel;
    }

    public function getMessages(): MessageCollection
    {
        return $this->messages ??= new MessageCollection();
    }

    public function setMessages(?MessageCollection $messages): void
    {
        $this->messages = $messages;
    }

    public function getExtensions(): Extensions
    {
        return $this->extensions;
    }

    public function setExtensions(Extensions $extensions): void
    {
        $this->extensions = $extensions;
    }

    public function getEvidences(): EvidenceCollection
    {
        return $this->evidences ??= new EvidenceCollection();
    }

    public function setEvidences(?EvidenceCollection $evidences): void
    {
        $this->evidences = $evidences;
    }

    public function getBuyerResponseDueDate(): ?string
    {
        return $this->buyerResponseDueDate;
    }

    public function setBuyerResponseDueDate(?string $buyerResponseDueDate): void
    {
        $this->buyerResponseDueDate = $buyerResponseDueDate;
    }

    public function getSellerResponseDueDate(): ?string
    {
        return $this->sellerResponseDueDate;
    }

    public function setSellerResponseDueDate(?string $sellerResponseDueDate): void
    {
        $this->sellerResponseDueDate = $sellerResponseDueDate;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): void
    {
        $this->offer = $offer;
    }

    public function getRefundDetails(): ?RefundDetails
    {
        return $this->refundDetails;
    }

    public function setRefundDetails(?RefundDetails $refundDetails): void
    {
        $this->refundDetails = $refundDetails;
    }

    public function getCommunicationDetails(): ?CommunicationDetails
    {
        return $this->communicationDetails;
    }

    public function setCommunicationDetails(?CommunicationDetails $communicationDetails): void
    {
        $this->communicationDetails = $communicationDetails;
    }

    public function getPartnerActions(): PartnerActionCollection
    {
        return $this->partnerActions ??= new PartnerActionCollection();
    }

    public function setPartnerActions(?PartnerActionCollection $partnerActions): void
    {
        $this->partnerActions = $partnerActions;
    }

    public function getSupportingInfo(): SupportingInfoCollection
    {
        return $this->supportingInfo ??= new SupportingInfoCollection();
    }

    public function setSupportingInfo(?SupportingInfoCollection $supportingInfo): void
    {
        $this->supportingInfo = $supportingInfo;
    }

    public function getLinks(): LinkCollection
    {
        return $this->links ??= new LinkCollection();
    }

    public function setLinks(?LinkCollection $links): void
    {
        $this->links = $links;
    }
}
