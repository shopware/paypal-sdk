<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo\TrackingInfo;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo\TrackingInfoCollection;

#[OA\Schema(schema: 'paypal_v1_disputes_item_evidence_evidence_info')]
class EvidenceInfo extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: TrackingInfo::class), minItems: 1, maxItems: 10)]
    protected TrackingInfoCollection $trackingInfo;

    /** @var string[] */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'), minItems: 1, maxItems: 100)]
    protected array $refundIds = [];

    public function getTrackingInfo(): TrackingInfoCollection
    {
        return $this->trackingInfo;
    }

    public function setTrackingInfo(TrackingInfoCollection $trackingInfo): void
    {
        $this->trackingInfo = $trackingInfo;
    }

    /**
     * @return string[]
     */
    public function getRefundIds(): array
    {
        return $this->refundIds;
    }

    /**
     * @param string[] $refundIds
     */
    public function setRefundIds(array $refundIds): void
    {
        $this->refundIds = $refundIds;
    }
}
