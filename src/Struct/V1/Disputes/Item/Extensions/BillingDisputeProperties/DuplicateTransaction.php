<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\BillingDisputeProperties;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Disputes\Common\Transaction;

#[OA\Schema(schema: 'paypal_v1_disputes_item_extensions_billing_dispute_properties_duplicate_transaction')]
class DuplicateTransaction extends Struct
{
    #[OA\Property(type: 'boolean')]
    protected bool $receivedDuplicate;

    #[OA\Property(ref: Transaction::class)]
    protected Transaction $originalTransaction;

    public function isReceivedDuplicate(): bool
    {
        return $this->receivedDuplicate;
    }

    public function setReceivedDuplicate(bool $receivedDuplicate): void
    {
        $this->receivedDuplicate = $receivedDuplicate;
    }

    public function getOriginalTransaction(): Transaction
    {
        return $this->originalTransaction;
    }

    public function setOriginalTransaction(Transaction $originalTransaction): void
    {
        $this->originalTransaction = $originalTransaction;
    }
}
