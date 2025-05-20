<?php

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Address;
use Shopware\PayPalSDK\Struct\V2\Common\Money;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data_card_level_2')]
class Level3 extends Struct
{
    #[OA\Property(ref: Money::class)]
    protected Money $shippingAmount;

    #[OA\Property(ref: Money::class)]
    protected Money $dutyAmount;

    #[OA\Property(ref: Money::class)]
    protected Money $discountAmount;

    #[OA\Property(ref: Address::class)]
    protected Address $shippingAddress;

    #[OA\Property(type: 'string')]
    protected string $shipsFromPostalCode;


    #[OA\Property(type: 'array', items: new OA\Items(ref: LineItem::class))]
    protected LineItemCollection $lineItems;
}