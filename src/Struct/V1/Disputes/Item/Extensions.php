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
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\BillingDisputeProperties;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Extensions\MerchandizeDisputeProperties;

#[OA\Schema(
    schema: 'paypal_v1_disputes_item_extensions',
    required: ['merchantContacted', 'billingDisputeProperties']
)]
class Extensions extends Struct
{
    #[OA\Property(type: 'boolean')]
    protected bool $merchantContacted;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected ?string $merchantContactedOutcome = null;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected ?string $merchantContactedTime = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected ?string $merchantContactedMode = null;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected ?string $buyerContactedTime = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255)]
    protected ?string $buyerContactedChannel = null;

    #[OA\Property(ref: BillingDisputeProperties::class)]
    protected BillingDisputeProperties $billingDisputeProperties;

    #[OA\Property(ref: MerchandizeDisputeProperties::class)]
    protected ?MerchandizeDisputeProperties $merchandizeDisputeProperties = null;

    public function isMerchantContacted(): bool
    {
        return $this->merchantContacted;
    }

    public function setMerchantContacted(bool $merchantContacted): void
    {
        $this->merchantContacted = $merchantContacted;
    }

    public function getMerchantContactedOutcome(): ?string
    {
        return $this->merchantContactedOutcome;
    }

    public function setMerchantContactedOutcome(?string $merchantContactedOutcome): void
    {
        $this->merchantContactedOutcome = $merchantContactedOutcome;
    }

    public function getMerchantContactedTime(): ?string
    {
        return $this->merchantContactedTime;
    }

    public function setMerchantContactedTime(?string $merchantContactedTime): void
    {
        $this->merchantContactedTime = $merchantContactedTime;
    }

    public function getMerchantContactedMode(): ?string
    {
        return $this->merchantContactedMode;
    }

    public function setMerchantContactedMode(?string $merchantContactedMode): void
    {
        $this->merchantContactedMode = $merchantContactedMode;
    }

    public function getBuyerContactedTime(): ?string
    {
        return $this->buyerContactedTime;
    }

    public function setBuyerContactedTime(?string $buyerContactedTime): void
    {
        $this->buyerContactedTime = $buyerContactedTime;
    }

    public function getBuyerContactedChannel(): ?string
    {
        return $this->buyerContactedChannel;
    }

    public function setBuyerContactedChannel(?string $buyerContactedChannel): void
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

    public function getMerchandizeDisputeProperties(): ?MerchandizeDisputeProperties
    {
        return $this->merchandizeDisputeProperties;
    }

    public function setMerchandizeDisputeProperties(?MerchandizeDisputeProperties $merchandizeDisputeProperties): void
    {
        $this->merchandizeDisputeProperties = $merchandizeDisputeProperties;
    }
}
