<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Link;
use Shopware\PayPalSDK\Struct\V2\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Item;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\ItemCollection;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_shipping_tracker', required: ['id'])]
class Tracker extends Struct
{
    public const string STATUS_SHIPPED = 'SHIPPED';
    public const string STATUS_CANCELLED = 'CANCELLED';

    #[OA\Property(type: 'string')]
    protected string $id;

    #[OA\Property(type: 'string')]
    protected ?string $status = null;

    #[OA\Property(type: 'boolean')]
    protected ?bool $notifyPayer = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected ?LinkCollection $links = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Item::class))]
    protected ?ItemCollection $items = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function isNotifyPayer(): ?bool
    {
        return $this->notifyPayer;
    }

    public function setNotifyPayer(?bool $notifyPayer): void
    {
        $this->notifyPayer = $notifyPayer;
    }

    public function getLinks(): LinkCollection
    {
        return $this->links ??= new LinkCollection();
    }

    public function setLinks(?LinkCollection $links): void
    {
        $this->links = $links;
    }

    public function getItems(): ItemCollection
    {
        return $this->items ??= new ItemCollection();
    }

    public function setItems(?ItemCollection $items): void
    {
        $this->items = $items;
    }
}
