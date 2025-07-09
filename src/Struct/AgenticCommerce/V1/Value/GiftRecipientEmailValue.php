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
    protected bool $verified = false;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): void
    {
        $this->verified = $verified;
    }
}
