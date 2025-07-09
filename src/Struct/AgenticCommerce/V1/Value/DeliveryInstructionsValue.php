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
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_value_delivery_instructions_value',
    required: ['instructions']
)]
class DeliveryInstructionsValue extends Struct implements ValueInterface
{
    /**
     * Special delivery instructions
     */
    #[OA\Property(
        type: 'string',
        maxLength: 200,
    )]
    protected string $instructions;

    /**
     * Building or gate access code
     */
    #[OA\Property(type: 'string')]
    protected ?string $accessCode = null;

    /**
     * Contact phone for delivery
     */
    #[OA\Property(type: 'string')]
    protected ?string $contactPhone = null;

    public function getInstructions(): string
    {
        return $this->instructions;
    }

    public function setInstructions(string $instructions): void
    {
        $this->instructions = $instructions;
    }

    public function getAccessCode(): ?string
    {
        return $this->accessCode;
    }

    public function setAccessCode(?string $accessCode): void
    {
        $this->accessCode = $accessCode;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): void
    {
        $this->contactPhone = $contactPhone;
    }
}
