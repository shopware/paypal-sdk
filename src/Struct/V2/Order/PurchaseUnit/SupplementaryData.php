<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Risk;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data')]
class SupplementaryData extends Struct
{
    #[OA\Property(ref: Card::class)]
    protected Card $card;

    #[OA\Property(ref: Risk::class)]
    protected Risk $risk;

    public function getCard(): Card
    {
        return $this->card;
    }

    public function setCard(Card $card): void
    {
        $this->card = $card;
    }

    public function getRisk(): Risk
    {
        return $this->risk;
    }

    public function setRisk(Risk $risk): void
    {
        $this->risk = $risk;
    }
}
