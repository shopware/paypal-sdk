<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Money;

#[OA\Schema(schema: 'paypal_v1_disputes_item_dispute_outcome')]
class DisputeOutcome extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $outcomeCode;

    #[OA\Property(ref: Money::class)]
    protected Money $amountRefunded;

    public function getOutcomeCode(): string
    {
        return $this->outcomeCode;
    }

    public function setOutcomeCode(string $outcomeCode): void
    {
        $this->outcomeCode = $outcomeCode;
    }

    public function getAmountRefunded(): Money
    {
        return $this->amountRefunded;
    }

    public function setAmountRefunded(Money $amountRefunded): void
    {
        $this->amountRefunded = $amountRefunded;
    }
}
