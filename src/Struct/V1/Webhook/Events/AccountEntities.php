<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Webhook\Events;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Link;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Capability;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\CapabilityCollection;
use Shopware\PayPalSDK\Struct\V1\Webhook\ApplicationContext;

#[OA\Schema(schema: 'paypal_v1_webhook_events_account_entities')]
class AccountEntities extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $externalId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $accountId = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Capability::class))]
    protected ?CapabilityCollection $capabilities = null;

    #[OA\Property(ref: ApplicationContext::class, nullable: true)]
    protected ?ApplicationContext $applicationContext = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected LinkCollection $links;

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(?string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getCapabilities(): ?CapabilityCollection
    {
        return $this->capabilities;
    }

    public function setCapabilities(?CapabilityCollection $capabilities): void
    {
        $this->capabilities = $capabilities;
    }

    public function getApplicationContext(): ?ApplicationContext
    {
        return $this->applicationContext;
    }

    public function setApplicationContext(?ApplicationContext $applicationContext): void
    {
        $this->applicationContext = $applicationContext;
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
