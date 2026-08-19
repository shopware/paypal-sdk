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
use Shopware\PayPalSDK\Struct\V1\Webhook\Events\PaymentApprovalReversed;
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

    private const EVENT_FAMILY_PAYMENT_AUTHORIZATION = 'PAYMENT.AUTHORIZATION.';
    private const EVENT_FAMILY_PAYMENT_CAPTURE = 'PAYMENT.CAPTURE.';
    private const EVENT_FAMILY_CHECKOUT_ORDER = 'CHECKOUT.ORDER.';
    private const EVENT_FAMILY_BILLING_SUBSCRIPTION = 'BILLING.SUBSCRIPTION.';
    private const EVENT_FAMILY_VAULT_PAYMENT_TOKEN = 'VAULT.PAYMENT-TOKEN.';
    private const EVENT_FAMILY_CUSTOMER_DISPUTE = 'CUSTOMER.DISPUTE.';
    private const EVENT_FAMILY_CUSTOMER_MANAGED_ACCOUNT = 'CUSTOMER.MANAGED-ACCOUNT.';
    private const EVENT_FAMILY_CUSTOMER_ACCOUNT_ENTITIES = 'CUSTOMER.ACCOUNT-ENTITIES.';

    #[OA\Property(type: 'string')]
    protected string $id;

    #[OA\Property(type: 'string')]
    protected string $resourceType = '';

    /** @var WebhookEventTypes::* */
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
        new OA\Schema(ref: PaymentApprovalReversed::class),
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

        if (!\is_array($resourceData)) {
            return $webhook;
        }

        // The event type is only set when it was part of the payload, so guard the access.
        $eventType = $webhook->isset('eventType') ? $webhook->getEventType() : '';
        $resourceClass = $this->identifyResource($eventType, $this->resourceVersion, $this->resourceType);

        if ($resourceClass !== null) {
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

    /**
     * @return WebhookEventTypes::*
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * @param WebhookEventTypes::* $eventType
     */
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
     * Identifies the struct the event resource is deserialized into.
     *
     * The event type is the primary discriminator, as it is the only field PayPal sends on every
     * event and each event type has exactly one resource body. The resource version selects the
     * schema revision within an event family. The resource type names a REST API object and is
     * optional, events whose resource is not a REST API object omit it, so it only resolves
     * events this SDK does not model explicitly.
     *
     * @return class-string<Struct>|null
     */
    protected function identifyResource(string $eventType, string $resourceVersion, string $resourceType): ?string
    {
        return $this->identifyResourceByEventType($eventType, $resourceVersion)
            ?? $this->identifyResourceType($resourceVersion, $resourceType);
    }

    /**
     * @deprecated tag:v3.0.0 - Will be removed and is replaced by {@see self::identifyResource()}
     *
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

    /**
     * @return class-string<Struct>|null
     */
    private function identifyResourceByEventType(string $eventType, string $resourceVersion): ?string
    {
        // The resource is not a REST API object, so PayPal sends neither a resource type nor a resource version for it.
        if ($eventType === WebhookEventTypes::CHECKOUT_PAYMENT_APPROVAL_REVERSED) {
            return PaymentApprovalReversed::class;
        }

        return match ($resourceVersion) {
            '3.0' => match (true) {
                \str_starts_with($eventType, self::EVENT_FAMILY_VAULT_PAYMENT_TOKEN) => PaymentToken::class,
                default => null,
            },
            '2.0' => match (true) {
                // A refunded or reversed capture carries a refund body, not a capture body.
                $eventType === WebhookEventTypes::PAYMENT_CAPTURE_REFUNDED,
                $eventType === WebhookEventTypes::PAYMENT_CAPTURE_REVERSED => Refund::class,
                \str_starts_with($eventType, self::EVENT_FAMILY_PAYMENT_CAPTURE) => Capture::class,
                \str_starts_with($eventType, self::EVENT_FAMILY_PAYMENT_AUTHORIZATION) => Authorization::class,
                \str_starts_with($eventType, self::EVENT_FAMILY_CHECKOUT_ORDER) => Order::class,
                \str_starts_with($eventType, self::EVENT_FAMILY_BILLING_SUBSCRIPTION) => Subscription::class,
                default => null,
            },
            '1.0' => match (true) {
                \str_starts_with($eventType, self::EVENT_FAMILY_CUSTOMER_DISPUTE) => Dispute::class,
                \str_starts_with($eventType, self::EVENT_FAMILY_CUSTOMER_MANAGED_ACCOUNT) => ManagedAccounts::class,
                \str_starts_with($eventType, self::EVENT_FAMILY_CUSTOMER_ACCOUNT_ENTITIES) => AccountEntities::class,
                default => null,
            },
            default => null,
        };
    }
}
