<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\EligibleMethodsData;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_supplementary_data')]
class SupplementaryData extends Struct
{
    /**
     * ISO 3166-1 alpha-2 country code
     */
    #[OA\Property(type: 'string', maxLength: 2, minLength: 2)]
    protected string $buyerCountryCode;

    public function getBuyerCountryCode(): string
    {
        return $this->buyerCountryCode;
    }

    public function setBuyerCountryCode(string $buyerCountryCode): void
    {
        $this->buyerCountryCode = $buyerCountryCode;
    }
}
