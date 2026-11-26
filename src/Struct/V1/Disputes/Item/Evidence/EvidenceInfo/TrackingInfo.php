<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_disputes_item_evidence_evidence_info_tracking_info')]
class TrackingInfo extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $carrierName;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 2000)]
    protected string $carrierNameOther;

    #[OA\Property(type: 'string', format: 'uri')]
    protected string $trackingUrl;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255)]
    protected string $trackingNumber;

    public function getCarrierName(): string
    {
        return $this->carrierName;
    }

    public function setCarrierName(string $carrierName): void
    {
        $this->carrierName = $carrierName;
    }

    public function getCarrierNameOther(): string
    {
        return $this->carrierNameOther;
    }

    public function setCarrierNameOther(string $carrierNameOther): void
    {
        $this->carrierNameOther = $carrierNameOther;
    }

    public function getTrackingUrl(): string
    {
        return $this->trackingUrl;
    }

    public function setTrackingUrl(string $trackingUrl): void
    {
        $this->trackingUrl = $trackingUrl;
    }

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(string $trackingNumber): void
    {
        $this->trackingNumber = $trackingNumber;
    }
}
