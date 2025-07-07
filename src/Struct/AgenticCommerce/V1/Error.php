<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_error',
    required: ['name', 'message']
)]
class Error
{
    /**
     * Error name/type
     */
    #[OA\Property(type: 'string')]
    protected string $name;

    /**
     * Error description
     */
    #[OA\Property(type: 'string')]
    protected string $message;

    /**
     * Unique error identifier for support
     */
    #[OA\Property(type: 'string')]
    protected string $debugId;

    /**
     * Detailed error information
     *
     * @var list<array{field: string, issue: string, description: string}>
     */
    #[OA\Property(type: 'array')]
    // TODO: OA Property array structure
    protected array $details;
}
