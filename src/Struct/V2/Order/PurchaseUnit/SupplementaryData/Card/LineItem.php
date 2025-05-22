<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\SupplementaryData\Card;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Money;
use Shopware\PayPalSDK\Struct\V2\Common\Upc;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_supplementary_data_card_line_item')]
class LineItem extends Struct
{
    #[OA\Property(type: 'string', maxLength: 127, minLength: 1)]
    protected string $name;

    #[OA\Property(type: 'integer')]
    protected int $quantity;

    #[OA\Property(type: 'string', maxLength: 2048)]
    protected string $description;

    #[OA\Property(type: 'string', maxLength: 127)]
    protected string $sku;

    #[OA\Property(type: 'string', maxLength: 2048, minLength: 1)]
    protected string $url;

    #[OA\Property(type: 'string', maxLength: 2048, minLength: 1)]
    protected string $imageUrl;

    #[OA\Property(ref: Upc::class)]
    protected Upc $upc;

    #[OA\Property(ref: Money::class)]
    protected Money $unitAmount;

    #[OA\Property(ref: Money::class)]
    protected Money $tax;

    #[OA\Property(type: 'string', maxLength: 12, minLength: 1)]
    protected string $commodityCode;

    #[OA\Property(ref: Money::class)]
    protected Money $discountAmount;

    #[OA\Property(ref: Money::class)]
    protected Money $totalAmount;

    #[OA\Property(type: 'string', maxLength: 12, minLength: 1)]
    protected string $unitOfMeasure;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getUpc(): Upc
    {
        return $this->upc;
    }

    public function setUpc(Upc $upc): void
    {
        $this->upc = $upc;
    }

    public function getUnitAmount(): Money
    {
        return $this->unitAmount;
    }

    public function setUnitAmount(Money $unitAmount): void
    {
        $this->unitAmount = $unitAmount;
    }

    public function getTax(): Money
    {
        return $this->tax;
    }

    public function setTax(Money $tax): void
    {
        $this->tax = $tax;
    }

    public function getCommodityCode(): string
    {
        return $this->commodityCode;
    }

    public function setCommodityCode(string $commodityCode): void
    {
        $this->commodityCode = $commodityCode;
    }

    public function getDiscountAmount(): Money
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(Money $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getTotalAmount(): Money
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(Money $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getUnitOfMeasure(): string
    {
        return $this->unitOfMeasure;
    }

    public function setUnitOfMeasure(string $unitOfMeasure): void
    {
        $this->unitOfMeasure = $unitOfMeasure;
    }
}
