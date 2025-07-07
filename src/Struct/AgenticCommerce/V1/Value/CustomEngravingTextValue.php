<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

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
    protected string $font;

    /**
     * Text size preference
     *
     * Enum: [ small, medium, large ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['small', 'medium', 'large']
    )]
    protected string $size;

    /**
     * Engraving position
     *
     * Enum: [ front, back, side, bottom ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['front', 'back', 'side', 'bottom']
    )]
    protected string $position;
}
