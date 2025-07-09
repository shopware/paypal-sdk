<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Contract\Struct\AgenticCommerce\V1\Value\ValueInterface;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_value_custom_engraving_text_value',
    required: ['text']
)]
class CustomEngravingTextValue extends Struct implements ValueInterface
{
    /**
     * Text to be engraved
     *
     * maxLength: 100
     */
    #[OA\Property(
        type: 'string',
        maxLength: 100,
    )]
    protected string $text;

    /**
     * Preferred font style
     *
     * Enum: [ arial, times, script, block ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['arial', 'times', 'script', 'block']
    )]
    protected ?string $font = null;

    /**
     * Text size preference
     *
     * Enum: [ small, medium, large ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['small', 'medium', 'large']
    )]
    protected ?string $size = null;

    /**
     * Engraving position
     *
     * Enum: [ front, back, side, bottom ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['front', 'back', 'side', 'bottom']
    )]
    protected ?string $position = null;

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getFont(): ?string
    {
        return $this->font;
    }

    public function setFont(?string $font): void
    {
        if (!\in_array($font, ['arial', 'times', 'script', 'block'], true)) {
            throw new \InvalidArgumentException('Font must be one of "arial", "times", "script", "block"');
        }

        $this->font = $font;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): void
    {
        if (!\in_array($size, ['small', 'medium', 'large'], true)) {
            throw new \InvalidArgumentException('Size must be one of "small", "medium", "large"');
        }

        $this->size = $size;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        if (!\in_array($position, ['front', 'back', 'side', 'bottom'], true)) {
            throw new \InvalidArgumentException('Position must be one of "front", "back", "side", "bottom"');
        }

        $this->position = $position;
    }
}
