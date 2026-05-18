<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V3;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Capability;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\CapabilityCollection;
use Shopware\PayPalSDK\Struct\V2\Common\Link;
use Shopware\PayPalSDK\Struct\V2\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\BusinessEntity;
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\IndividualOwner;
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\IndividualOwnerCollection;

#[OA\Schema(schema: 'paypal_v3_managed_account')]
class ManagedAccount extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $accountId;

    #[OA\Property(type: 'string')]
    protected string $legalCountryCode;

    #[OA\Property(type: 'array', items: new OA\Items(ref: IndividualOwner::class))]
    protected IndividualOwnerCollection $individualOwners;

    #[OA\Property(ref: BusinessEntity::class)]
    protected BusinessEntity $businessEntity;

    #[OA\Property(type: 'string')]
    protected string $externalId;

    #[OA\Property(type: 'string')]
    protected string $organization;

    #[OA\Property(type: 'string')]
    protected string $primaryCurrencyCode;

    #[OA\Property(type: 'string')]
    protected string $softDescriptor;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Capability::class))]
    protected CapabilityCollection $capabilities;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class))]
    protected LinkCollection $links;

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getLegalCountryCode(): string
    {
        return $this->legalCountryCode;
    }

    public function setLegalCountryCode(string $legalCountryCode): void
    {
        $this->legalCountryCode = $legalCountryCode;
    }

    public function getIndividualOwners(): IndividualOwnerCollection
    {
        return $this->individualOwners;
    }

    public function setIndividualOwners(IndividualOwnerCollection $individualOwners): void
    {
        $this->individualOwners = $individualOwners;
    }

    public function getBusinessEntity(): BusinessEntity
    {
        return $this->businessEntity;
    }

    public function setBusinessEntity(BusinessEntity $businessEntity): void
    {
        $this->businessEntity = $businessEntity;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getOrganization(): string
    {
        return $this->organization;
    }

    public function setOrganization(string $organization): void
    {
        $this->organization = $organization;
    }

    public function getPrimaryCurrencyCode(): string
    {
        return $this->primaryCurrencyCode;
    }

    public function setPrimaryCurrencyCode(string $primaryCurrencyCode): void
    {
        $this->primaryCurrencyCode = $primaryCurrencyCode;
    }

    public function getSoftDescriptor(): string
    {
        return $this->softDescriptor;
    }

    public function setSoftDescriptor(string $softDescriptor): void
    {
        $this->softDescriptor = $softDescriptor;
    }

    public function getCapabilities(): CapabilityCollection
    {
        return $this->capabilities;
    }

    public function setCapabilities(CapabilityCollection $capabilities): void
    {
        $this->capabilities = $capabilities;
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
