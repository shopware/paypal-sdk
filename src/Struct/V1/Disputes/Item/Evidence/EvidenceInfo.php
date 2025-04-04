<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo\RefundId;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo\RefundIdCollection;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo\TrackingInfo;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item\Evidence\EvidenceInfo\TrackingInfoCollection;

#[OA\Schema(schema: 'paypal_v1_disputes_item_evidence_evidence_info')]
class EvidenceInfo extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: TrackingInfo::class))]
    protected TrackingInfoCollection $trackingInfo;

    #[OA\Property(type: 'array', items: new OA\Items(ref: RefundId::class))]
    protected RefundIdCollection $refundIds;

    public function getTrackingInfo(): TrackingInfoCollection
    {
        return $this->trackingInfo;
    }

    public function setTrackingInfo(TrackingInfoCollection $trackingInfo): void
    {
        $this->trackingInfo = $trackingInfo;
    }

    public function getRefundIds(): RefundIdCollection
    {
        return $this->refundIds;
    }

    public function setRefundIds(RefundIdCollection $refundIds): void
    {
        $this->refundIds = $refundIds;
    }
}
