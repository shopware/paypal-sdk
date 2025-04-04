<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Address;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping\Name;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping\Tracker;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping\TrackerCollection;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_shipping')]
class Shipping extends Struct
{
    #[OA\Property(ref: Name::class)]
    protected Name $name;

    #[OA\Property(ref: Address::class)]
    protected Address $address;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Tracker::class), nullable: true)]
    protected ?TrackerCollection $trackers = null;

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getTrackers(): ?TrackerCollection
    {
        return $this->trackers;
    }

    public function setTrackers(?TrackerCollection $trackers): void
    {
        $this->trackers = $trackers;
    }
}
