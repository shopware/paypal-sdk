<?php declare(strict_types=1);

/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Webhook;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_webhook_event_type')]
class EventType extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $name;

    #[OA\Property(type: 'string')]
    protected string $description;

    #[OA\Property(type: 'string')]
    protected string $status;

    #[OA\Property(type: 'string')]
    protected string $resourceVersion;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getResourceVersion(): string
    {
        return $this->resourceVersion;
    }

    public function setResourceVersion(string $resourceVersion): void
    {
        $this->resourceVersion = $resourceVersion;
    }
}
