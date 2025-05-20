<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Address;
use Shopware\PayPalSDK\Struct\V2\Common\Money;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data_card_level3')]
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

    public function getShippingAmount(): Money
    {
        return $this->shippingAmount;
    }

    public function setShippingAmount(Money $shippingAmount): void
    {
        $this->shippingAmount = $shippingAmount;
    }

    public function getDutyAmount(): Money
    {
        return $this->dutyAmount;
    }

    public function setDutyAmount(Money $dutyAmount): void
    {
        $this->dutyAmount = $dutyAmount;
    }

    public function getDiscountAmount(): Money
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(Money $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(Address $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function getShipsFromPostalCode(): string
    {
        return $this->shipsFromPostalCode;
    }

    public function setShipsFromPostalCode(string $shipsFromPostalCode): void
    {
        $this->shipsFromPostalCode = $shipsFromPostalCode;
    }

    public function getLineItems(): LineItemCollection
    {
        return $this->lineItems;
    }

    public function setLineItems(LineItemCollection $lineItems): void
    {
        $this->lineItems = $lineItems;
    }
}
