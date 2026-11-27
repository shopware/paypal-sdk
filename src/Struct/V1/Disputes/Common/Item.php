<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Money;

#[OA\Schema(
    schema: 'paypal_v1_disputes_common_item',
    required: ['itemId', 'itemDescription', 'itemQuantity', 'partnerTransactionId', 'reason', 'disputeAmount', 'notes']
)]
class Item extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 255)]
    protected string $itemId;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 2000, pattern: Pattern::MULTILINE_TEXT)]
    protected string $itemDescription;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 10, pattern: Pattern::POSITIVE_INTEGER)]
    protected string $itemQuantity;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::ALNUM)]
    protected string $partnerTransactionId;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $reason;

    #[OA\Property(ref: Money::class)]
    protected Money $disputeAmount;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 2000, pattern: Pattern::MULTILINE_TEXT)]
    protected string $notes;

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function setItemId(string $itemId): void
    {
        $this->itemId = $itemId;
    }

    public function getItemDescription(): string
    {
        return $this->itemDescription;
    }

    public function setItemDescription(string $itemDescription): void
    {
        $this->itemDescription = $itemDescription;
    }

    public function getItemQuantity(): string
    {
        return $this->itemQuantity;
    }

    public function setItemQuantity(string $itemQuantity): void
    {
        $this->itemQuantity = $itemQuantity;
    }

    public function getPartnerTransactionId(): string
    {
        return $this->partnerTransactionId;
    }

    public function setPartnerTransactionId(string $partnerTransactionId): void
    {
        $this->partnerTransactionId = $partnerTransactionId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    public function getDisputeAmount(): Money
    {
        return $this->disputeAmount;
    }

    public function setDisputeAmount(Money $disputeAmount): void
    {
        $this->disputeAmount = $disputeAmount;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }
}
