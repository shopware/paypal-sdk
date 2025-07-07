<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_value_allergy_information_value')]
class AllergyInformationValue extends Struct implements ValueInterface
{
    /**
     * List of known allergies
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $allergies;

    /**
     * Allergy severity level
     *
     * Enum: [ mild, moderate, severe, life_threatening ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['mild', 'moderate', 'severe', 'life_threatening']
    )]
    protected string $severity;

    /**
     * Medications to avoid
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $medications;

    /**
     * Emergency contact information
     *
     * example: +1-555-999-8888
     */
    #[OA\Property(type: 'string')]
    protected string $emergencyContact;
}
