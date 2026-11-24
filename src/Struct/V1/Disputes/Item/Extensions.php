<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\BillingDisputeProperties;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\MerchandizeDisputeProperties;

#[OA\Schema(schema: 'paypal_v1_disputes_item_extensions')]
class Extensions extends Struct
{
    #[OA\Property(type: 'boolean')]
    protected bool $merchantContacted;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: '^[0-9A-Z_]+$')]
    protected string $merchantContactedOutcome;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$')]
    protected string $merchantContactedTime;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: '^[0-9A-Z_]+$')]
    protected string $merchantContactedMode;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$')]
    protected string $buyerContactedTime;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255)]
    protected string $buyerContactedChannel;

    #[OA\Property(ref: BillingDisputeProperties::class)]
    protected BillingDisputeProperties $billingDisputeProperties;

    #[OA\Property(ref: MerchandizeDisputeProperties::class)]
    protected MerchandizeDisputeProperties $merchandizeDisputeProperties;

    public function isMerchantContacted(): bool
    {
        return $this->merchantContacted;
    }

    public function setMerchantContacted(bool $merchantContacted): void
    {
        $this->merchantContacted = $merchantContacted;
    }

    public function getMerchantContactedOutcome(): string
    {
        return $this->merchantContactedOutcome;
    }

    public function setMerchantContactedOutcome(string $merchantContactedOutcome): void
    {
        $this->merchantContactedOutcome = $merchantContactedOutcome;
    }

    public function getMerchantContactedTime(): string
    {
        return $this->merchantContactedTime;
    }

    public function setMerchantContactedTime(string $merchantContactedTime): void
    {
        $this->merchantContactedTime = $merchantContactedTime;
    }

    public function getMerchantContactedMode(): string
    {
        return $this->merchantContactedMode;
    }

    public function setMerchantContactedMode(string $merchantContactedMode): void
    {
        $this->merchantContactedMode = $merchantContactedMode;
    }

    public function getBuyerContactedTime(): string
    {
        return $this->buyerContactedTime;
    }

    public function setBuyerContactedTime(string $buyerContactedTime): void
    {
        $this->buyerContactedTime = $buyerContactedTime;
    }

    public function getBuyerContactedChannel(): string
    {
        return $this->buyerContactedChannel;
    }

    public function setBuyerContactedChannel(string $buyerContactedChannel): void
    {
        $this->buyerContactedChannel = $buyerContactedChannel;
    }

    public function getBillingDisputeProperties(): BillingDisputeProperties
    {
        return $this->billingDisputeProperties;
    }

    public function setBillingDisputeProperties(BillingDisputeProperties $billingDisputeProperties): void
    {
        $this->billingDisputeProperties = $billingDisputeProperties;
    }

    public function getMerchandizeDisputeProperties(): MerchandizeDisputeProperties
    {
        return $this->merchandizeDisputeProperties;
    }

    public function setMerchandizeDisputeProperties(MerchandizeDisputeProperties $merchandizeDisputeProperties): void
    {
        $this->merchandizeDisputeProperties = $merchandizeDisputeProperties;
    }
}
