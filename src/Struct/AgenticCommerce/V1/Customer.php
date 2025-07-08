<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_agentic_commerce_v1_customer')]
class Customer extends Struct
{
    /**
     * minLength: 0
     * maxLength: 140
     *
     * @var array{given_name: string, surname: string}
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            required: ['given_name', 'surname'],
            properties: [
                new OA\Property(property: 'given_name', type: 'string'),
                new OA\Property(property: 'surname', type: 'string'),
            ],
            type: 'object'
        )
    )]
    protected ?array $name = null;

    #[OA\Property(ref: Phone::class)]
    protected ?Phone $phone = null;

    /**
     * The internationalized email address.
     * Note: Up to 64 characters are allowed before and 255 characters are allowed after the @ sign.
     * However, the generally accepted maximum length for an email address is 254 characters.
     * The pattern verifies that an unquoted @ sign exists.
     *
     * minLength: 3
     * maxLength: 254
     * pattern: ^(?:[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$
     */
    #[OA\Property(
        type: 'string',
        maxLength: 254,
        minLength: 3,
        pattern: '^(?:[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[A-Za-z0-9-]*[A-Za-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$'
    )]
    protected ?string $emailAddress = null;

    /**
     * @return ?array{given_name: string, surname: string}
     */
    public function getName(): ?array
    {
        return $this->name;
    }

    public function setName(string $givenName, string $surname): void
    {
        $this->name = [
            'given_name' => $givenName,
            'surname' => $surname,
        ];
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function setPhone(?Phone $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }
}
