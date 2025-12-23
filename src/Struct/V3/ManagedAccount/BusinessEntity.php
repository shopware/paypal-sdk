<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V3\ManagedAccount;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Address;
use Shopware\PayPalSDK\Struct\V3\Common\Email;
use Shopware\PayPalSDK\Struct\V3\Common\EmailCollection;
use Shopware\PayPalSDK\Struct\V3\Common\Name;
use Shopware\PayPalSDK\Struct\V3\Common\NameCollection;
use Shopware\PayPalSDK\Struct\V3\Common\PhoneNumber;
use Shopware\PayPalSDK\Struct\V3\Common\PhoneNumberCollection;
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\BusinessEntity\IncorporationDetails;

#[OA\Schema(schema: 'paypal_v3_managed_account_business_entity')]
class BusinessEntity extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $type;

    #[OA\Property(type: 'string')]
    protected string $merchantCategoryCode;

    #[OA\Property(ref: IncorporationDetails::class)]
    protected IncorporationDetails $incorporationDetails;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Name::class))]
    protected NameCollection $names;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Email::class))]
    protected EmailCollection $emails;

    #[OA\Property(type: 'string')]
    protected string $website;

    #[OA\Property(ref: Address::class)]
    protected Address $registeredBusinessAddress;

    #[OA\Property(type: 'array', items: new OA\Items(ref: PhoneNumber::class))]
    protected PhoneNumberCollection $phoneNumbers;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getMerchantCategoryCode(): string
    {
        return $this->merchantCategoryCode;
    }

    public function setMerchantCategoryCode(string $merchantCategoryCode): void
    {
        $this->merchantCategoryCode = $merchantCategoryCode;
    }

    public function getIncorporationDetails(): IncorporationDetails
    {
        return $this->incorporationDetails;
    }

    public function setIncorporationDetails(IncorporationDetails $incorporationDetails): void
    {
        $this->incorporationDetails = $incorporationDetails;
    }

    public function getNames(): NameCollection
    {
        return $this->names;
    }

    public function setNames(NameCollection $names): void
    {
        $this->names = $names;
    }

    public function getEmails(): EmailCollection
    {
        return $this->emails;
    }

    public function setEmails(EmailCollection $emails): void
    {
        $this->emails = $emails;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getRegisteredBusinessAddress(): Address
    {
        return $this->registeredBusinessAddress;
    }

    public function setRegisteredBusinessAddress(Address $registeredBusinessAddress): void
    {
        $this->registeredBusinessAddress = $registeredBusinessAddress;
    }

    public function getPhoneNumbers(): PhoneNumberCollection
    {
        return $this->phoneNumbers;
    }

    public function setPhoneNumbers(PhoneNumberCollection $phoneNumbers): void
    {
        $this->phoneNumbers = $phoneNumbers;
    }
}
