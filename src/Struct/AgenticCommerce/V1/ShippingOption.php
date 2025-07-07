<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_shipping_option',
    required: ['price', 'isSelected']
)]
class ShippingOption extends Struct
{
    /**
     * Unique shipping option identifier
     */
    #[OA\Property(type: 'string')]
    protected string $id;

    /**
     * Display name
     */
    #[OA\Property(type: 'string')]
    protected string $name;

    /**
     * Detailed description
     */
    #[OA\Property(type: 'string')]
    protected string $description;

    #[OA\Property(ref: Money::class)]
    protected Money $price;

    /**
     * Whether this shipping option is currently selected
     */
    #[OA\Property(type: 'boolean')]
    protected bool $isSelected;

    /**
     * Estimated delivery date in YYYY-MM-DD format
     *
     * pattern: ^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$
     */
    #[OA\Property(
        type: 'string',
        pattern: '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$'
    )]
    protected string $estimatedDelivery;
}
