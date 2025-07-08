<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

// TODO: should this be GiftOption? singular?
#[OA\Schema(schema: 'paypal_agentic_commerce_v1_gift_options')]
class GiftOptions extends Struct
{
    /**
     * Whether this is a gift
     */
    #[OA\Property(type: 'boolean')]
    protected bool $isGift;

    /**
     * Gift recipient information
     *
     * @var array{name: string, email: string, phone: string}
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['name', 'email', 'phone'],
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'phone', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected array $recipient;

    /**
     * Scheduled delivery date in RFC3339 format. Seconds are required while fractional seconds are optional.
     *
     * minLength: 20
     * maxLength: 64
     * pattern: ^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$
     * example: 2024-12-25T09:00:00Z
     */
    #[OA\Property(
        type: 'string',
        maxLength: 64,
        minLength: 20,
        pattern: '^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$'
    )]
    protected string $deliveryDate;

    /**
     * Name of gift sender
     */
    #[OA\Property(type: 'string')]
    protected string $senderName;

    /**
     * Personal message (max 500 characters)
     */
    #[OA\Property(
        type: 'string',
        maxLength: 500,
    )]
    protected string $giftMessage;

    /**
     * Whether to include gift wrapping
     */
    #[OA\Property(type: 'boolean')]
    protected bool $giftWrap;
}
