<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\BillingDisputeProperties\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_disputes_item_extensions_billing_dispute_properties_common_cancellation_details')]
class CancellationDetails extends Struct
{
    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$')]
    protected string $cancellationDate;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 127)]
    protected string $cancellationNumber;

    #[OA\Property(type: 'boolean')]
    protected bool $cancelled;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: '^[0-9A-Z_]+$')]
    protected string $cancellationMode;

    public function getCancellationDate(): string
    {
        return $this->cancellationDate;
    }

    public function setCancellationDate(string $cancellationDate): void
    {
        $this->cancellationDate = $cancellationDate;
    }

    public function getCancellationNumber(): string
    {
        return $this->cancellationNumber;
    }

    public function setCancellationNumber(string $cancellationNumber): void
    {
        $this->cancellationNumber = $cancellationNumber;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    public function setCancelled(bool $cancelled): void
    {
        $this->cancelled = $cancelled;
    }

    public function getCancellationMode(): string
    {
        return $this->cancellationMode;
    }

    public function setCancellationMode(string $cancellationMode): void
    {
        $this->cancellationMode = $cancellationMode;
    }
}
