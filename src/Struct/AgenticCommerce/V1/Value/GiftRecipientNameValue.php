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
    schema: 'paypal_agentic_commerce_v1_value_gift_recipient_name_value',
    required: ['name']
)]
class GiftRecipientNameValue extends Struct implements ValueInterface
{
    /**
     * Recipient's full name
     */
    #[OA\Property(type: 'string')]
    protected string $name;

    /**
     * Recipient's first name
     */
    #[OA\Property(type: 'string')]
    protected string $firstName;

    /**
     * Recipient's last name
     */
    #[OA\Property(type: 'string')]
    protected string $lastName;
}
