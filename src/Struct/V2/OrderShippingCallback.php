<?php

/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Shopware\PayPalSDK\Struct\V2;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Address;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\ShippingOption;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnitCollection;

#[OA\Schema(schema: 'paypal_v2_order_shipping_callback')]
class OrderShippingCallback extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $id;

    #[OA\Property(ref: Address::class)]
    protected Address $shippingAddress;

    #[OA\Property(ref: ShippingOption::class)]
    protected ?ShippingOption $shippingOption = null;

    #[OA\Property(ref: PurchaseUnitCollection::class)]
    protected PurchaseUnitCollection $purchaseUnits;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(Address $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getShippingOption(): ?ShippingOption
    {
        return $this->shippingOption;
    }

    public function setShippingOption(?ShippingOption $shippingOption): void
    {
        $this->shippingOption = $shippingOption;
    }

    public function getPurchaseUnits(): PurchaseUnitCollection
    {
        return $this->purchaseUnits;
    }

    public function setPurchaseUnits(PurchaseUnitCollection $purchaseUnits): void
    {
        $this->purchaseUnits = $purchaseUnits;
    }
}