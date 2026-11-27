<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping\Tracker;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_shipping_tracker_item')]
class Item extends Struct
{
    public const int MAX_LENGTH_SKU = 127;
    public const int MAX_LENGTH_URL = 2048;

    #[OA\Property(type: 'string', minLength: 1)]
    protected ?string $name = null;

    #[OA\Property(type: 'integer')]
    protected ?int $quantity = null;

    #[OA\Property(type: 'string', maxLength: self::MAX_LENGTH_SKU, minLength: 1)]
    protected ?string $sku = null;

    #[OA\Property(type: 'string', maxLength: self::MAX_LENGTH_URL, format: 'uri', minLength: 1)]
    protected ?string $url = null;

    #[OA\Property(type: 'string', maxLength: self::MAX_LENGTH_URL, format: 'uri', minLength: 1)]
    protected ?string $imageUrl = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int|string|null $quantity): void
    {
        $this->quantity = $quantity === null ? null : (int) $quantity;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @throws \LengthException if given parameter is too long
     */
    public function setSku(?string $sku): void
    {
        if ($sku !== null && \mb_strlen($sku) > self::MAX_LENGTH_SKU) {
            throw new \LengthException(
                \sprintf('%s::$sku must not be longer than %s characters', self::class, self::MAX_LENGTH_SKU)
            );
        }

        $this->sku = $sku;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @throws \LengthException if given parameter is too long
     */
    public function setUrl(?string $url): void
    {
        if ($url !== null && \mb_strlen($url) > self::MAX_LENGTH_URL) {
            throw new \LengthException(
                \sprintf('%s::$url must not be longer than %s characters', self::class, self::MAX_LENGTH_URL)
            );
        }

        $this->url = $url;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @throws \LengthException if given parameter is too long
     */
    public function setImageUrl(?string $imageUrl): void
    {
        if ($imageUrl !== null && \mb_strlen($imageUrl) > self::MAX_LENGTH_URL) {
            throw new \LengthException(
                \sprintf('%s::$image_url must not be longer than %s characters', self::class, self::MAX_LENGTH_URL)
            );
        }

        $this->imageUrl = $imageUrl;
    }
}
