<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Risk;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_v2_order_purchase_unit_supplementary_data_risk_participant_metadata',
    required: ['ipAddress']
)]
class ParticipantMetadata extends Struct
{
    #[OA\Property(type: 'string', minLength: 7, maxLength: 39, pattern: Pattern::IP_ADDRESS_OR_HOST_NAME)]
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
