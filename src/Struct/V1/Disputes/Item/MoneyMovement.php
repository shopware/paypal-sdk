<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Money;

#[OA\Schema(
    schema: 'paypal_v1_disputes_item_money_movement',
    required: ['affectedParty', 'amount', 'initiatedTime', 'type', 'reason']
)]
class MoneyMovement extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $affectedParty;

    #[OA\Property(ref: Money::class)]
    protected Money $amount;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected string $initiatedTime;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $type;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $reason;

    public function getAffectedParty(): string
    {
        return $this->affectedParty;
    }

    public function setAffectedParty(string $affectedParty): void
    {
        $this->affectedParty = $affectedParty;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    public function getInitiatedTime(): string
    {
        return $this->initiatedTime;
    }

    public function setInitiatedTime(string $initiatedTime): void
    {
        $this->initiatedTime = $initiatedTime;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }
}
