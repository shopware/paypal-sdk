<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_common_phone_number', required: ['nationalNumber'])]
class PhoneNumber extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 14, pattern: Pattern::PHONE_NATIONAL_NUMBER)]
    protected string $nationalNumber;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 3, pattern: Pattern::PHONE_COUNTRY_CODE)]
    protected ?string $countryCode = null;

    public function getNationalNumber(): string
    {
        return $this->nationalNumber;
    }

    public function setNationalNumber(string $nationalNumber): void
    {
        $this->nationalNumber = $nationalNumber;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }
}
