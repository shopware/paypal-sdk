<?php

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Money;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data_card_level_2')]
class Level2 extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $invoiceId;

    #[OA\Property(ref: Money::class)]
    protected Money $taxTotal;
}