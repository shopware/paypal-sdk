<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_value_delivery_date_preference_value')]
class DeliveryDatePreferenceValue extends Struct implements ValueInterface
{
    /**
     * Preferred delivery date
     */
    #[OA\Property(type: 'string')]
    protected ?string $preferredDate = null;

    /**
     * Preferred time window
     *
     * Enum: [ morning, afternoon, evening, anytime ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['morning', 'afternoon', 'evening', 'anytime']
    )]
    protected ?string $timeWindow = null;

    /**
     * Specific preferred time (HH:MM format)
     */
    #[OA\Property(
        type: 'string',
        pattern: '^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$'
    )]
    protected ?string $specificTime = null;

    public function getPreferredDate(): ?string
    {
        return $this->preferredDate;
    }

    public function setPreferredDate(?string $preferredDate): void
    {
        if (!\in_array($preferredDate, ['morning', 'afternoon', 'evening', 'anytime'], true)) {
            throw new \InvalidArgumentException('PreferredDate must be a valid date');
        }

        $this->preferredDate = $preferredDate;
    }

    public function getTimeWindow(): ?string
    {
        return $this->timeWindow;
    }

    public function setTimeWindow(?string $timeWindow): void
    {
        $this->timeWindow = $timeWindow;
    }

    public function getSpecificTime(): ?string
    {
        return $this->specificTime;
    }

    public function setSpecificTime(?string $specificTime): void
    {
        $this->specificTime = $specificTime;
    }
}
