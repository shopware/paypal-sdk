<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_shipping_error_context')]
class ShippingErrorContext extends Struct implements ContextInterface
{
    /**
     * Specific shipping issue type
     *
     * Enum: [ MISSING_SHIPPING_ADDRESS, SHIPPING_ADDRESS_INVALID, SHIPPING_TO_PO_BOX_NOT_ALLOWED, NO_SHIPPING_OPTIONS, INTERNATIONAL_SHIPPING_RESTRICTED, REGION_RESTRICTED, OVERSIZED_ITEM_SHIPPING, HAZARDOUS_MATERIAL_SHIPPING, SHIPPING_ZONE_NOT_COVERED, MISSING_COORDINATES_FOR_ENHANCED_DELIVERY ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['MISSING_SHIPPING_ADDRESS', 'SHIPPING_ADDRESS_INVALID', 'SHIPPING_TO_PO_BOX_NOT_ALLOWED', 'NO_SHIPPING_OPTIONS', 'INTERNATIONAL_SHIPPING_RESTRICTED', 'REGION_RESTRICTED', 'OVERSIZED_ITEM_SHIPPING', 'HAZARDOUS_MATERIAL_SHIPPING', 'SHIPPING_ZONE_NOT_COVERED', 'MISSING_COORDINATES_FOR_ENHANCED_DELIVERY']
    )]
    protected string $specificIssue;

    /**
     * Specific address validation failures
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $validationFailures;

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
    protected array $suggestedCorrections;

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
    protected float $addressQualityScore;

    /**
     * Items with shipping restrictions
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $restrictedItems;

    /**
     * Reason for shipping restriction
     *
     * Enum: [ signature_required, age_verification_required, export_controlled, hazardous_material, oversized_item, po_box_restriction ]
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
        enum: ['signature_required', 'age_verification_required', 'export_controlled', 'hazardous_material', 'oversized_item', 'po_box_restriction']
    )]
    protected array $restrictionReason;

    /**
     * Whether PO Box was detected
     */
    #[OA\Property(type: 'boolean')]
    protected bool $poBoxDetected;

    /**
     * Destination country code
     */
    #[OA\Property(type: 'string')]
    protected string $destinationCountry;

    /**
     * Restricted region identifier
     */
    #[OA\Property(type: 'string')]
    protected string $restrictedRegion;

    /**
     * List of supported countries
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $supportedCountries;

    /**
     * Address string that failed validation
     */
    #[OA\Property(type: 'string')]
    protected string $providedAddress;
}
