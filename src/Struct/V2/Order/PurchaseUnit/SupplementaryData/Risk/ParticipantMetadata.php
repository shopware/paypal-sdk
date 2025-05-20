<?php

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Risk;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data_risk_participant_metadata')]
class ParticipantMetadata extends Struct
{
    #[OA\Property(type: 'string', maxLength: 37, minLength: 7)]
    protected string $ipAddress;

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }
}