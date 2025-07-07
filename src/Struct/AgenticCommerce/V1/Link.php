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
    schema: 'paypal_agentic_commerce_v1_link',
    required: ['rel', 'href']
)]
class Link extends Struct
{
    /**
     * Link relationship type
     *
     * Enum: [ self, update, checkout ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['self', 'update', 'checkout']
    )]
    protected string $rel;

    /**
     * Target URL for the link
     *
     * example: https://your-domain.com/api/paypal/v1/merchant-cart/CART-123
     */
    #[OA\Property(type: 'string')]
    protected string $href;

    /**
     * HTTP method for the link
     */
    #[OA\Property(
        type: 'string',
        enum: ['GET', 'POST', 'PUT']
    )]
    protected string $method;

    /**
     * Human-readable description of the link
     */
    #[OA\Property(type: 'string')]
    protected string $title;

    /**
     * Expected content type
     */
    #[OA\Property(type: 'string')]
    protected string $type;
}
