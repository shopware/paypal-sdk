<?php declare(strict_types=1);

/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V1\Webhook\EventType;
use Shopware\PayPalSDK\Struct\V1\Webhook\EventTypeCollection;

#[OA\Schema(schema: 'paypal_v1_webhook')]
class Webhook extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $id;

    #[OA\Property(type: 'string', maxLength: 2048)]
    protected string $url;

    #[OA\Property(type: 'array', items: new OA\Items(ref: EventType::class))]
    protected EventTypeCollection $eventTypes;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected LinkCollection $links;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getEventTypes(): EventTypeCollection
    {
        return $this->eventTypes;
    }

    public function setEventTypes(EventTypeCollection $eventTypes): void
    {
        $this->eventTypes = $eventTypes;
    }

    public function getLinks(): LinkCollection
    {
        return $this->links;
    }

    public function setLinks(LinkCollection $links): void
    {
        $this->links = $links;
    }
}
