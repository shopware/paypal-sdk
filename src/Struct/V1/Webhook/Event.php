<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Webhook;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V1\Subscription;
use Shopware\PayPalSDK\Struct\V1\Webhook\Events\AccountEntities;
use Shopware\PayPalSDK\Struct\V1\Webhook\Events\Dispute;
use Shopware\PayPalSDK\Struct\V1\Webhook\Events\ManagedAccounts;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Authorization;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Refund;
use Shopware\PayPalSDK\Struct\V3\PaymentToken;

#[OA\Schema(schema: 'paypal_v1_webhook_event')]
class Event extends Struct
{
    public const RESOURCE_TYPE_AUTHORIZATION = 'authorization';
    public const RESOURCE_TYPE_CAPTURE = 'capture';
    public const RESOURCE_TYPE_CHECKOUT_ORDER = 'checkout-order';
    public const RESOURCE_TYPE_REFUND = 'refund';
    public const RESOURCE_TYPE_PAYMENT_TOKEN = 'payment_token';
    public const RESOURCE_TYPE_SUBSCRIPTION = 'subscription';
    public const RESOURCE_TYPE_MANAGED_ACCOUNTS = 'managed-accounts';
    public const RESOURCE_TYPE_ACCOUNT_ENTITIES = 'account-entities';
    public const RESOURCE_TYPE_DISPUTE = 'dispute';

    #[OA\Property(type: 'string')]
    protected string $id;

    #[OA\Property(type: 'string')]
    protected string $resourceType = '';

    #[OA\Property(type: 'string')]
    protected string $eventType;

    #[OA\Property(type: 'string')]
    protected string $summary;

    #[OA\Property(nullable: true, oneOf: [
        new OA\Schema(ref: PaymentToken::class),
        new OA\Schema(ref: Order::class),
        new OA\Schema(ref: Authorization::class),
        new OA\Schema(ref: Capture::class),
        new OA\Schema(ref: Refund::class),
        new OA\Schema(ref: Resource::class),
        new OA\Schema(ref: Subscription::class),
        new OA\Schema(ref: ManagedAccounts::class),
        new OA\Schema(ref: AccountEntities::class),
        new OA\Schema(ref: Dispute::class),
    ])]
    protected ?Struct $resource = null;

    #[OA\Property(type: 'string')]
    protected string $createTime;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected LinkCollection $links;

    #[OA\Property(type: 'string')]
    protected string $eventVersion;

    #[OA\Property(type: 'string')]
    protected string $resourceVersion = '1.0';

    #[OA\Property(ref: ApplicationContext::class)]
    protected ApplicationContext $applicationContext;

    public function assign(array $data): static
    {
        $resourceData = $data['resource'] ?? null;
        unset($data['resource']);
        $webhook = parent::assign($data);

        if (\is_array($resourceData) && $resourceClass = $this->identifyResourceType($this->resourceVersion, $this->resourceType)) {
            $webhook->resource = Struct::from($resourceClass, $resourceData);
        }

        return $webhook;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function setResourceType(string $resourceType): void
    {
        $this->resourceType = $resourceType;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): void
    {
        $this->eventType = $eventType;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    public function getResource(): ?Struct
    {
        return $this->resource;
    }

    public function setResource(?Struct $resource): void
    {
        $this->resource = $resource;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getLinks(): LinkCollection
    {
        return $this->links;
    }

    public function setLinks(LinkCollection $links): void
    {
        $this->links = $links;
    }

    public function getEventVersion(): string
    {
        return $this->eventVersion;
    }

    public function setEventVersion(string $eventVersion): void
    {
        $this->eventVersion = $eventVersion;
    }

    public function getResourceVersion(): string
    {
        return $this->resourceVersion;
    }

    public function setResourceVersion(string $resourceVersion): void
    {
        $this->resourceVersion = $resourceVersion;
    }

    public function getApplicationContext(): ApplicationContext
    {
        return $this->applicationContext;
    }

    public function setApplicationContext(ApplicationContext $applicationContext): void
    {
        $this->applicationContext = $applicationContext;
    }

    /**
     * @return class-string<Struct>|null
     */
    protected function identifyResourceType(string $resourceVersion, string $resourceType): ?string
    {
        return match ($resourceVersion) {
            '3.0' => match ($resourceType) {
                self::RESOURCE_TYPE_PAYMENT_TOKEN => PaymentToken::class,
                default => null,
            },
            '2.0' => match ($resourceType) {
                self::RESOURCE_TYPE_AUTHORIZATION => Authorization::class,
                self::RESOURCE_TYPE_CAPTURE => Capture::class,
                self::RESOURCE_TYPE_CHECKOUT_ORDER => Order::class,
                self::RESOURCE_TYPE_REFUND => Refund::class,
                self::RESOURCE_TYPE_SUBSCRIPTION => Subscription::class,
                default => null,
            },
            '1.0' => match ($resourceType) {
                self::RESOURCE_TYPE_MANAGED_ACCOUNTS => ManagedAccounts::class,
                self::RESOURCE_TYPE_ACCOUNT_ENTITIES => AccountEntities::class,
                self::RESOURCE_TYPE_DISPUTE => Dispute::class,
                default => Resource::class,
            },
            default => match ($resourceType) {
                default => Resource::class,
            },
        };
    }
}
