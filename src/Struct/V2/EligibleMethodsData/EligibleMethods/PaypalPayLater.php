<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods;

use OpenApi\Attributes as OA;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_eligible_methods_paypal_pay_later')]
class PaypalPayLater extends Paypal
{
    /**
     * ISO 3166-1 alpha-2 country code
     */
    #[OA\Property(type: 'string', maxLength: 2, minLength: 2)]
    protected string $countryCode;

    #[OA\Property(type: 'string')]
    protected string $productCode;

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function setProductCode(string $productCode): void
    {
        $this->productCode = $productCode;
    }
}
