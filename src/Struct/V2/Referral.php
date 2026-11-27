<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Link;
use Shopware\PayPalSDK\Struct\V2\Common\LinkCollection;
use Shopware\PayPalSDK\Struct\V2\Referral\BusinessEntity;
use Shopware\PayPalSDK\Struct\V2\Referral\LegalConsent;
use Shopware\PayPalSDK\Struct\V2\Referral\LegalConsentCollection;
use Shopware\PayPalSDK\Struct\V2\Referral\Operation;
use Shopware\PayPalSDK\Struct\V2\Referral\OperationCollection;
use Shopware\PayPalSDK\Struct\V2\Referral\PartnerConfigOverride;

#[OA\Schema(schema: 'paypal_v2_referral')]
class Referral extends Struct
{
    public const string PRODUCT_TYPE_PPCP = 'PPCP';
    public const string PRODUCT_TYPE_PAYMENT_METHODS = 'PAYMENT_METHODS';
    public const string PRODUCT_TYPE_ADVANCED_VAULTING = 'ADVANCED_VAULTING';
    public const string PRODUCT_TYPE_KLARNA = 'KLARNA';
    public const string PRODUCT_TYPE_SWISH = 'SWISH';
    public const string PRODUCT_TYPE_SEPA = 'SEPA';

    public const string CAPABILITY_PAYPAL_WALLET_VAULTING_ADVANCED = 'PAYPAL_WALLET_VAULTING_ADVANCED';
    public const string CAPABILITY_PAY_UPON_INVOICE = 'PAY_UPON_INVOICE';
    public const string CAPABILITY_APPLE_PAY = 'APPLE_PAY';
    public const string CAPABILITY_GOOGLE_PAY = 'GOOGLE_PAY';

    #[OA\Property(ref: BusinessEntity::class)]
    protected BusinessEntity $businessEntity;

    #[OA\Property(type: 'string', minLength: 2, maxLength: 10, pattern: Pattern::LANGUAGE_ALPHA_REGION)]
    protected string $preferredLanguageCode;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 127)]
    protected string $trackingId;

    #[OA\Property(ref: PartnerConfigOverride::class)]
    protected PartnerConfigOverride $partnerConfigOverride;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Operation::class), minItems: 1, maxItems: 5)]
    protected ?OperationCollection $operations = null;

    /** @var string[] */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'), minItems: 1, maxItems: 5)]
    protected array $products = [
        self::PRODUCT_TYPE_PPCP,
        self::PRODUCT_TYPE_PAYMENT_METHODS,
        self::PRODUCT_TYPE_ADVANCED_VAULTING,
    ];

    /** @var string[] */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'), minItems: 1, maxItems: 5)]
    protected array $capabilities = [
        self::CAPABILITY_PAY_UPON_INVOICE,
        self::CAPABILITY_PAYPAL_WALLET_VAULTING_ADVANCED,
        self::CAPABILITY_APPLE_PAY,
        self::CAPABILITY_GOOGLE_PAY,
    ];

    #[OA\Property(type: 'array', items: new OA\Items(ref: LegalConsent::class), minItems: 1, maxItems: 5)]
    protected ?LegalConsentCollection $legalConsents = null;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Link::class), minItems: 1, maxItems: 10)]
    protected ?LinkCollection $links = null;

    #[OA\Property(type: 'string', minLength: 2, maxLength: 2, pattern: Pattern::COUNTRY_CODE)]
    protected string $legalCountryCode;

    public function getBusinessEntity(): BusinessEntity
    {
        return $this->businessEntity;
    }

    public function setBusinessEntity(BusinessEntity $businessEntity): void
    {
        $this->businessEntity = $businessEntity;
    }

    public function getPreferredLanguageCode(): string
    {
        return $this->preferredLanguageCode;
    }

    public function setPreferredLanguageCode(string $preferredLanguageCode): void
    {
        $this->preferredLanguageCode = $preferredLanguageCode;
    }

    public function getTrackingId(): string
    {
        return $this->trackingId;
    }

    public function setTrackingId(string $trackingId): void
    {
        $this->trackingId = $trackingId;
    }

    public function getPartnerConfigOverride(): PartnerConfigOverride
    {
        return $this->partnerConfigOverride;
    }

    public function setPartnerConfigOverride(PartnerConfigOverride $partnerConfigOverride): void
    {
        $this->partnerConfigOverride = $partnerConfigOverride;
    }

    public function getOperations(): OperationCollection
    {
        return $this->operations ??= new OperationCollection();
    }

    public function setOperations(?OperationCollection $operations): void
    {
        $this->operations = $operations;
    }

    /**
     * @return string[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param string[] $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function addProduct(string $product): void
    {
        $this->products[] = $product;
    }

    /**
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * @param string[] $capabilities
     */
    public function setCapabilities(array $capabilities): void
    {
        $this->capabilities = $capabilities;
    }

    public function getLegalConsents(): LegalConsentCollection
    {
        return $this->legalConsents ??= new LegalConsentCollection();
    }

    public function setLegalConsents(?LegalConsentCollection $legalConsents): void
    {
        $this->legalConsents = $legalConsents;
    }

    public function getLinks(): LinkCollection
    {
        return $this->links ??= new LinkCollection();
    }

    public function setLinks(?LinkCollection $links): void
    {
        $this->links = $links;
    }

    public function getLegalCountryCode(): string
    {
        return $this->legalCountryCode;
    }

    public function setLegalCountryCode(string $legalCountryCode): void
    {
        $this->legalCountryCode = $legalCountryCode;
    }
}
