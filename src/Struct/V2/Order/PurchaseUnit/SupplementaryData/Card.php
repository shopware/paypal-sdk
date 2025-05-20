<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card\Level2;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data_card')]
class Card extends Struct
{
    #[OA\Property(ref: Level2::class)]
    protected Level2 $address;

    public function getAddress(): Level2
    {
        return $this->address;
    }

    public function setAddress(Level2 $address): void
    {
        $this->address = $address;
    }
}
