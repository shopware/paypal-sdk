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
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\Process;
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\ProcessCollection;

#[OA\Schema(schema: 'paypal_v1_webhook_events_managed_accounts')]
class ManagedAccounts extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected LinkCollection $links;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $externalId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $accountId = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Capability::class))]
    protected ?CapabilityCollection $capabilities = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Process::class))]
    protected ?ProcessCollection $processCollection = null;

    public function getLinks(): LinkCollection
    {
        return $this->links;
    }

    public function setLinks(LinkCollection $links): void
    {
        $this->links = $links;
    }

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

    public function getProcessCollection(): ?ProcessCollection
    {
        return $this->processCollection;
    }

    public function setProcessCollection(?ProcessCollection $processCollection): void
    {
        $this->processCollection = $processCollection;
    }
}
