<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Contract\Struct\V2\Order\PaymentSource\VaultedPaymentSourceInterface;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\V2\Common\Address;
use Shopware\PayPalSDK\Struct\V2\Common\Name;
use Shopware\PayPalSDK\Struct\V2\Common\PhoneNumber;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_paypal')]
class Paypal extends AbstractPaymentSource implements VaultedPaymentSourceInterface
{
    #[OA\Property(type: 'string', minLength: 3, maxLength: 254, pattern: Pattern::CONTAINS_EMAIL_ADDRESS)]
    protected ?string $emailAddress = null;

    #[OA\Property(type: 'string', minLength: 13, maxLength: 13, pattern: Pattern::ACCOUNT_ID)]
    protected ?string $accountId = null;

    #[OA\Property(type: 'string', minLength: 2, maxLength: 128, pattern: Pattern::BILLING_AGREEMENT_ID)]
    protected ?string $billingAgreementId = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::ALNUM_UNDERSCORE_HYPHEN)]
    protected ?string $vaultId = null;

    #[OA\Property(ref: Name::class)]
    protected ?Name $name = null;

    #[OA\Property(ref: PhoneNumber::class)]
    protected ?PhoneNumber $phoneNumber = null;

    #[OA\Property(ref: Address::class)]
    protected ?Address $address = null;

    #[OA\Property(type: 'string', minLength: 10, maxLength: 10, pattern: Pattern::DATE)]
    protected ?string $birthDate = null;

    #[OA\Property(type: 'string')]
    protected ?string $phoneType = null;

    #[OA\Property(ref: Attributes::class)]
    protected ?Attributes $attributes = null;

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(?string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getBillingAgreementId(): ?string
    {
        return $this->billingAgreementId;
    }

    public function setBillingAgreementId(?string $billingAgreementId): void
    {
        $this->billingAgreementId = $billingAgreementId;
    }

    public function getVaultId(): ?string
    {
        return $this->vaultId;
    }

    public function setVaultId(?string $vaultId): void
    {
        $this->vaultId = $vaultId;
    }

    public function getName(): ?Name
    {
        return $this->name;
    }

    public function setName(?Name $name): void
    {
        $this->name = $name;
    }

    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(?string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getPhoneType(): ?string
    {
        return $this->phoneType;
    }

    public function setPhoneType(?string $phoneType): void
    {
        $this->phoneType = $phoneType;
    }

    public function getAttributes(): ?Attributes
    {
        return $this->attributes;
    }

    public function setAttributes(?Attributes $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getVaultIdentifier(): ?string
    {
        return $this->getEmailAddress();
    }
}
