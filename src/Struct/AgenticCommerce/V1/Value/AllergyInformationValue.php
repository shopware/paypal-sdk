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
    protected ?array $allergies = null;

    /**
     * Allergy severity level
     *
     * Enum: [ mild, moderate, severe, life_threatening ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['mild', 'moderate', 'severe', 'life_threatening']
    )]
    protected ?string $severity = null;

    /**
     * Medications to avoid
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $medications = null;

    /**
     * Emergency contact information
     *
     * example: +1-555-999-8888
     */
    #[OA\Property(type: 'string')]
    protected ?string $emergencyContact = null;

    /**
     * @return ?string[]
     */
    public function getAllergies(): ?array
    {
        return $this->allergies;
    }

    /**
     * @param ?string[] $allergies
     */
    public function setAllergies(?array $allergies): void
    {
        $this->allergies = $allergies;
    }

    public function addAllergy(string $allergy): void
    {
        $this->allergies[] = $allergy;
    }

    public function getSeverity(): ?string
    {
        return $this->severity;
    }

    public function setSeverity(?string $severity): void
    {
        if (!\in_array($severity, ['mild', 'moderate', 'severe', 'life_threatening'], true)) {
            throw new \RuntimeException(\sprintf('Invalid value for severity: %s', $severity));
        }

        $this->severity = $severity;
    }

    /**
     * @return ?string[]
     */
    public function getMedications(): ?array
    {
        return $this->medications;
    }

    /**
     * @param ?string[] $medications
     */
    public function setMedications(?array $medications): void
    {
        $this->medications = $medications;
    }

    public function addMedication(string $medication): void
    {
        $this->medications[] = $medication;
    }

    public function getEmergencyContact(): ?string
    {
        return $this->emergencyContact;
    }

    public function setEmergencyContact(?string $emergencyContact): void
    {
        $this->emergencyContact = $emergencyContact;
    }
}
