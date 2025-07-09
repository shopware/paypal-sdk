<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

use OpenApi\Attributes as OA;
use Shopware\Contract\PayPalSDK\Struct\AgenticCommerce\V1\Value\ValueInterface;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_agentic_commerce_v1_value_custom_sizing_info_value')]
class CustomSizingInfoValue extends Struct implements ValueInterface
{
    /**
     * Body measurements
     *
     * @var array{chest: string, waist: string, height: string, weight: string}
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['chest', 'waist', 'height', 'weight'],
            properties: [
                new OA\Property(property: 'chest', type: 'string'),
                new OA\Property(property: 'waist', type: 'string'),
                new OA\Property(property: 'height', type: 'string'),
                new OA\Property(property: 'weight', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected ?array $measurements = null;

    /**
     * Fit preference
     *
     * Enum: [ tight, regular, loose ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['tight', 'regular', 'loose']
    )]
    protected ?string $sizePreference = null;

    /**
     * Special sizing requirements
     */
    #[OA\Property(type: 'string')]
    protected ?string $specialRequirements = null;

    /**
     * @return ?array{chest: string, waist: string, height: string, weight: string}
     */
    public function getMeasurements(): ?array
    {
        return $this->measurements;
    }

    /**
     * @param ?array{chest: string, waist: string, height: string, weight: string} $measurements
     */
    public function setMeasurements(?array $measurements): void
    {
        $this->measurements = $measurements;
    }

    public function resetMeasurements(): void
    {
        $this->measurements = null;
    }

    public function getSizePreference(): ?string
    {
        return $this->sizePreference;
    }

    public function setSizePreference(?string $sizePreference): void
    {
        $this->sizePreference = $sizePreference;
    }

    public function getSpecialRequirements(): ?string
    {
        return $this->specialRequirements;
    }

    public function setSpecialRequirements(?string $specialRequirements): void
    {
        $this->specialRequirements = $specialRequirements;
    }
}
