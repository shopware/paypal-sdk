<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_phone_number')]
class PhoneNumber extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $countryCode = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $nationalNumber = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $extensionNumber = null;

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getNationalNumber(): ?string
    {
        return $this->nationalNumber;
    }

    public function setNationalNumber(?string $nationalNumber): void
    {
        $this->nationalNumber = $nationalNumber;
    }

    public function getExtensionNumber(): ?string
    {
        return $this->extensionNumber;
    }

    public function setExtensionNumber(?string $extensionNumber): void
    {
        $this->extensionNumber = $extensionNumber;
    }
}
