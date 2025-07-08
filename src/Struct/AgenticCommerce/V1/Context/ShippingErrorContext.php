<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_shipping_error_context')]
class ShippingErrorContext extends AbstractContext
{
    public const SPECIFIC_ISSUES = [
        'MISSING_SHIPPING_ADDRESS' => 'MISSING_SHIPPING_ADDRESS',
        'SHIPPING_ADDRESS_INVALID' => 'SHIPPING_ADDRESS_INVALID',
        'SHIPPING_TO_PO_BOX_NOT_ALLOWED' => 'SHIPPING_TO_PO_BOX_NOT_ALLOWED',
        'NO_SHIPPING_OPTIONS' => 'NO_SHIPPING_OPTIONS',
        'INTERNATIONAL_SHIPPING_RESTRICTED' => 'INTERNATIONAL_SHIPPING_RESTRICTED',
        'REGION_RESTRICTED' => 'REGION_RESTRICTED',
        'OVERSIZED_ITEM_SHIPPING' => 'OVERSIZED_ITEM_SHIPPING',
        'HAZARDOUS_MATERIAL_SHIPPING' => 'HAZARDOUS_MATERIAL_SHIPPING',
        'SHIPPING_ZONE_NOT_COVERED' => 'SHIPPING_ZONE_NOT_COVERED',
        'MISSING_COORDINATES_FOR_ENHANCED_DELIVERY' => 'MISSING_COORDINATES_FOR_ENHANCED_DELIVERY',
    ];

    public const RESTRICTED_REASONS = [
        'signature_required',
        'age_verification_required',
        'export_controlled',
        'hazardous_material',
        'oversized_item',
        'po_box_restriction',
    ];

    /**
     * Specific address validation failures
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $validationFailures = null;

    /**
     * Suggested address corrections
     *
     * @var array{postal_code: string, address_line_1: string, admin_area_2: string}
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['postal_code', 'address_line_1', 'admin_area_2'],
            properties: [
                new OA\Property(property: 'postal_code', type: 'string'),
                new OA\Property(property: 'address_line_1', type: 'string'),
                new OA\Property(property: 'admin_area_2', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected ?array $suggestedCorrections = null;

    /**
     * Address validation quality score
     *
     * minimum: 0
     * maximum: 1
     */
    #[OA\Property(
        type: 'float',
        maximum: 1,
        minimum: 0,
    )]
    protected ?float $addressQualityScore = null;

    /**
     * Items with shipping restrictions
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $restrictedItems = null;

    /**
     * Reason for shipping restriction
     */
    #[OA\Property(
        type: 'string',
        enum: self::RESTRICTED_REASONS,
    )]
    protected ?string $restrictionReason = null;

    /**
     * Whether PO Box was detected
     */
    #[OA\Property(type: 'boolean')]
    protected ?bool $poBoxDetected = null;

    /**
     * Destination country code
     */
    #[OA\Property(type: 'string')]
    protected ?string $destinationCountry = null;

    /**
     * Restricted region identifier
     */
    #[OA\Property(type: 'string')]
    protected ?string $restrictedRegion = null;

    /**
     * List of supported countries
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $supportedCountries = null;

    /**
     * Address string that failed validation
     */
    #[OA\Property(type: 'string')]
    protected ?string $providedAddress = null;

    /**
     * @return ?string[]
     */
    public function getValidationFailures(): ?array
    {
        return $this->validationFailures;
    }

    /**
     * @param ?string[] $validationFailures
     */
    public function setValidationFailures(?array $validationFailures): void
    {
        $this->validationFailures = $validationFailures;
    }

    public function addValidationFailure(string $validationFailure): void
    {
        $this->validationFailures[] = $validationFailure;
    }

    /**
     * @return ?array{postal_code: string, address_line_1: string, admin_area_2: string}
     */
    public function getSuggestedCorrections(): ?array
    {
        return $this->suggestedCorrections;
    }

    public function setSuggestedCorrections(string $postalCode, string $addressLine1, string $adminArea2): void
    {
        $this->suggestedCorrections = [
            'postal_code' => $postalCode,
            'address_line_1' => $addressLine1,
            'admin_area_2' => $adminArea2,
        ];
    }

    public function getAddressQualityScore(): ?float
    {
        return $this->addressQualityScore;
    }

    public function setAddressQualityScore(?float $addressQualityScore): void
    {
        if ($addressQualityScore < 0 || $addressQualityScore > 1) {
            // TODO: Better one?
            throw new \RuntimeException('Score must be between 0 and 1');
        }

        $this->addressQualityScore = $addressQualityScore;
    }

    /**
     * @return ?string[]
     */
    public function getRestrictedItems(): ?array
    {
        return $this->restrictedItems;
    }

    /**
     * @param ?string[] $restrictedItems
     */
    public function setRestrictedItems(?array $restrictedItems): void
    {
        $this->restrictedItems = $restrictedItems;
    }

    public function addRestrictedItem(string $restrictedItem): void
    {
        $this->restrictedItems[] = $restrictedItem;
    }

    public function getRestrictionReason(): ?string
    {
        return $this->restrictionReason;
    }

    public function setRestrictionReason(string $restrictionReason): void
    {
        if (!\in_array($restrictionReason, self::RESTRICTED_REASONS, true)) {
            throw new \RuntimeException('Restricted reason now allowed: ' . $restrictionReason);
        }

        $this->restrictionReason = $restrictionReason;
    }

    public function getPoBoxDetected(): ?bool
    {
        return $this->poBoxDetected;
    }

    public function setPoBoxDetected(?bool $poBoxDetected): void
    {
        $this->poBoxDetected = $poBoxDetected;
    }

    public function getDestinationCountry(): ?string
    {
        return $this->destinationCountry;
    }

    public function setDestinationCountry(?string $destinationCountry): void
    {
        $this->destinationCountry = $destinationCountry;
    }

    public function getRestrictedRegion(): ?string
    {
        return $this->restrictedRegion;
    }

    public function setRestrictedRegion(?string $restrictedRegion): void
    {
        $this->restrictedRegion = $restrictedRegion;
    }

    /**
     * @return ?string[]
     */
    public function getSupportedCountries(): ?array
    {
        return $this->supportedCountries;
    }

    /**
     * @param ?string[] $supportedCountries
     */
    public function setSupportedCountries(?array $supportedCountries): void
    {
        $this->supportedCountries = $supportedCountries;
    }

    public function addSupportedCountries(string $supportedCountry): void
    {
        $this->supportedCountries[] = $supportedCountry;
    }

    public function getProvidedAddress(): ?string
    {
        return $this->providedAddress;
    }

    public function setProvidedAddress(?string $providedAddress): void
    {
        $this->providedAddress = $providedAddress;
    }
}
