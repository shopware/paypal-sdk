<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
