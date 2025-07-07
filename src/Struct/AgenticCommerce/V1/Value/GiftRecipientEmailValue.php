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
    schema: 'paypal_agentic_commerce_v1_value_gift_recipient_email_value',
    required: ['email']
)]
class GiftRecipientEmailValue extends Struct implements ValueInterface
{
    /**
     * Recipient's email address
     */
    #[OA\Property(type: 'string')]
    protected string $email;

    /**
     * Whether email was verified
     */
    #[OA\Property(type: 'boolean')]
    protected bool $verified;
}
