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
    protected string $variantId;

    /**
     * Number of items
     */
    #[OA\Property(type: 'integer', minimum: 1)]
    protected int $quantity;

    /**
     * Product display name
     */
    #[OA\Property(type: 'string')]
    protected string $name;

    /**
     * Product description
     */
    #[OA\Property(type: 'string')]
    protected string $description;

    /**
     * URL for product details page
     */
    #[OA\Property(type: 'string')]
    protected string $itemUrl;

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
    protected array $selectedAttributes;

    /**
     * TODO: GiftOption as an array?
     */
    #[OA\Property(ref: GiftOptions::class)]
    protected GiftOptions $giftOptions;

    /** @var list<array{name: string, value: string, price_modifier: string}> */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['name', 'value','price_modifier'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'value', type: 'string'),
                new OA\Property(property: 'price_modifier', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected array $customOptions;
}
