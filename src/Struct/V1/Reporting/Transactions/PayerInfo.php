<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Common\Address;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_payer_info')]
class PayerInfo extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $accountId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $emailAddress = null;

    #[OA\Property(ref: PhoneNumber::class, nullable: true)]
    protected ?PhoneNumber $phoneNumber = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $addressStatus = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $payerStatus = null;

    #[OA\Property(ref: Name::class, nullable: true)]
    protected ?Name $payerName = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $countryCode = null;

    #[OA\Property(ref: Address::class, nullable: true)]
    protected ?Address $address = null;

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(?string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAddressStatus(): ?string
    {
        return $this->addressStatus;
    }

    public function setAddressStatus(?string $addressStatus): void
    {
        $this->addressStatus = $addressStatus;
    }

    public function getPayerStatus(): ?string
    {
        return $this->payerStatus;
    }

    public function setPayerStatus(?string $payerStatus): void
    {
        $this->payerStatus = $payerStatus;
    }

    public function getPayerName(): ?Name
    {
        return $this->payerName;
    }

    public function setPayerName(?Name $payerName): void
    {
        $this->payerName = $payerName;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }
}
