<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Risk\ParticipantMetadata;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data_risk')]
class Risk extends Struct
{
    #[OA\Property(ref: ParticipantMetadata::class, nullable: true)]
    protected ?ParticipantMetadata $customer;

    public function getCustomer(): ?ParticipantMetadata
    {
        return $this->customer ?? null;
    }

    public function setCustomer(?ParticipantMetadata $customer): void
    {
        $this->customer = $customer;
    }
}
