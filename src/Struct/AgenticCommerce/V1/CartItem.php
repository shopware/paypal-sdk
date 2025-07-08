<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_cart_item',
    required: ['itemId', 'quantity', 'price']
)]
class CartItem extends Struct
{
    /**
     * Unique product identifier
     */
    #[OA\Property(type: 'string')]
    protected string $itemId;

    /**
     * Product variant identifier (color, size, etc.)
     */
    #[OA\Property(type: 'string')]
    protected ?string $variantId = null;

    /**
     * Number of items
     */
    #[OA\Property(type: 'integer', minimum: 1)]
    protected int $quantity;

    /**
     * Product display name
     */
    #[OA\Property(type: 'string')]
    protected ?string $name = null;

    /**
     * Product description
     */
    #[OA\Property(type: 'string')]
    protected ?string $description = null;

    /**
     * URL for product details page
     */
    #[OA\Property(type: 'string')]
    protected ?string $itemUrl = null;

    #[OA\Property(ref: Money::class)]
    protected Money $price;

    /** @var list<array{name: string, value: string}> */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['name', 'value'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'value', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected ?array $selectedAttributes = null;

    /**
     * TODO: GiftOption as an array?
     */
    #[OA\Property(ref: GiftOptions::class)]
    protected ?GiftOptions $giftOptions = null;

    /** @var list<array{name: string, value: string, price_modifier: string}> */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['name', 'value', 'price_modifier'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'value', type: 'string'),
                new OA\Property(property: 'price_modifier', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected ?array $customOptions = null;

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function setItemId(string $itemId): void
    {
        $this->itemId = $itemId;
    }

    public function getVariantId(): ?string
    {
        return $this->variantId;
    }

    public function setVariantId(?string $variantId): void
    {
        $this->variantId = $variantId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getItemUrl(): ?string
    {
        return $this->itemUrl;
    }

    public function setItemUrl(?string $itemUrl): void
    {
        $this->itemUrl = $itemUrl;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }

    /**
     * @return ?list<array{name: string, value: string}>
     */
    public function getSelectedAttributes(): ?array
    {
        return $this->selectedAttributes;
    }

    public function addSelectedAttribute(string $name, string $value): void
    {
        $this->selectedAttributes[] = [
            'name' => $name,
            'value' => $value,
        ];
    }

    public function resetSelectedAttributes(): void
    {
        $this->selectedAttributes = [];
    }

    public function getGiftOptions(): ?GiftOptions
    {
        return $this->giftOptions;
    }

    public function setGiftOptions(?GiftOptions $giftOptions): void
    {
        $this->giftOptions = $giftOptions;
    }

    /**
     * @return ?list<array{name: string, value: string, price_modifier: string}>
     */
    public function getCustomOptions(): ?array
    {
        return $this->customOptions;
    }

    public function addCustomOption(string $name, string $value, string $priceModifier): void
    {
        $this->customOptions[] = [
            'name' => $name,
            'value' => $value,
            'price_modifier' => $priceModifier,
        ];
    }

    public function resetCustomOptions(): void
    {
        $this->customOptions = [];
    }
}
