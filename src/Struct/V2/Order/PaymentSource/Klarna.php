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
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Klarna\AuthorizationContext;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_klarna')]
class Klarna extends AbstractPaymentSource
{
    #[OA\Property(type: 'string')]
    protected string $emailAddress;

    #[OA\Property(type: 'string')]
    protected string $phone;

    #[OA\Property(ref: Name::class)]
    protected Name $name;

    #[OA\Property(ref: Address::class, nullable: true)]
    protected ?Address $billingAddress = null;

    #[OA\Property(type: 'string', maxLength: 2, minLength: 2)]
    protected string $countryCode;

    #[OA\Property(ref: AuthorizationContext::class, nullable: true)]
    protected ?AuthorizationContext $authorizationContext = null;

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
        if ($billingAddress !== null && !$billingAddress->getAdminArea1()) {
            $billingAddress->setAdminArea1($billingAddress->getAdminArea2());
        }
        $this->billingAddress = $billingAddress;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getAuthorizationContext(): ?AuthorizationContext
    {
        return $this->authorizationContext;
    }

    public function setAuthorizationContext(?AuthorizationContext $authorizationContext): void
    {
        $this->authorizationContext = $authorizationContext;
    }
}
