<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V3\ManagedAccount\IndividualOwner;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v3_managed_account_individual_owner_identification_document')]
class IdentificationDocument extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $id;

    #[OA\Property(type: 'string')]
    protected string $identificationNumber;

    #[OA\Property(type: 'string')]
    protected string $issuingCountryCode;

    #[OA\Property(type: 'string')]
    protected string $type;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getIdentificationNumber(): string
    {
        return $this->identificationNumber;
    }

    public function setIdentificationNumber(string $identificationNumber): void
    {
        $this->identificationNumber = $identificationNumber;
    }

    public function getIssuingCountryCode(): string
    {
        return $this->issuingCountryCode;
    }

    public function setIssuingCountryCode(string $issuingCountryCode): void
    {
        $this->issuingCountryCode = $issuingCountryCode;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
