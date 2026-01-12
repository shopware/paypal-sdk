<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\V2\Common\Address;
use Shopware\PayPalSDK\Struct\V2\Common\Name;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_afterpay')]
class Afterpay extends AbstractPaymentSource
{
    #[OA\Property(ref: Name::class)]
    protected Name $name;

    #[OA\Property(type: 'string')]
    protected string $emailAddress;

    #[OA\Property(type: 'string')]
    protected string $phone;

    #[OA\Property(type: 'string', format: 'date')]
    protected string $birthDate;

    #[OA\Property(ref: Address::class, nullable: true)]
    protected ?Address $billingAddress = null;

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $email): void
    {
        $this->emailAddress = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function setBirthDate(string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function getBillingAddress(): ?Address
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?Address $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }
}
