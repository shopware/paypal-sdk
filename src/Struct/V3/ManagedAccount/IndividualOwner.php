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
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\IndividualOwner\BirthDetails;
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\IndividualOwner\IdentificationDocument;
use Shopware\PayPalSDK\Struct\V3\ManagedAccount\IndividualOwner\IdentificationDocumentCollection;

#[OA\Schema(schema: 'paypal_v3_managed_account_individual_owner')]
class IndividualOwner extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $id;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Name::class))]
    protected NameCollection $names;

    #[OA\Property(ref: Address::class)]
    protected Address $primaryResidence;

    #[OA\Property(type: 'array', items: new OA\Items(ref: PhoneNumber::class))]
    protected PhoneNumberCollection $phoneNumbers;

    #[OA\Property(ref: BirthDetails::class)]
    protected BirthDetails $birthDetails;

    #[OA\Property(type: 'array', items: new OA\Items(ref: IdentificationDocument::class))]
    protected IdentificationDocumentCollection $identificationDocuments;

    #[OA\Property(type: 'array', items: new OA\Items(ref: Email::class))]
    protected EmailCollection $emails;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getNames(): NameCollection
    {
        return $this->names;
    }

    public function setNames(NameCollection $names): void
    {
        $this->names = $names;
    }

    public function getPrimaryResidence(): Address
    {
        return $this->primaryResidence;
    }

    public function setPrimaryResidence(Address $primaryResidence): void
    {
        $this->primaryResidence = $primaryResidence;
    }

    public function getPhoneNumbers(): PhoneNumberCollection
    {
        return $this->phoneNumbers;
    }

    public function setPhoneNumbers(PhoneNumberCollection $phoneNumbers): void
    {
        $this->phoneNumbers = $phoneNumbers;
    }

    public function getBirthDetails(): BirthDetails
    {
        return $this->birthDetails;
    }

    public function setBirthDetails(BirthDetails $birthDetails): void
    {
        $this->birthDetails = $birthDetails;
    }

    public function getIdentificationDocuments(): IdentificationDocumentCollection
    {
        return $this->identificationDocuments;
    }

    public function setIdentificationDocuments(IdentificationDocumentCollection $identificationDocuments): void
    {
        $this->identificationDocuments = $identificationDocuments;
    }

    public function getEmails(): EmailCollection
    {
        return $this->emails;
    }

    public function setEmails(EmailCollection $emails): void
    {
        $this->emails = $emails;
    }
}
