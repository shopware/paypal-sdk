<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\SupplementaryData;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data')]
class EligibleMethodsData extends Struct
{
    #[OA\Property(ref: EligibleMethods::class)]
    protected EligibleMethods $eligibleMethods;

    #[OA\Property(ref: SupplementaryData::class)]
    protected SupplementaryData $supplementaryData;

    public function getEligibleMethods(): EligibleMethods
    {
        return $this->eligibleMethods;
    }

    public function setEligibleMethods(EligibleMethods $eligibleMethods): void
    {
        $this->eligibleMethods = $eligibleMethods;
    }

    public function getSupplementaryData(): SupplementaryData
    {
        return $this->supplementaryData;
    }

    public function setSupplementaryData(SupplementaryData $supplementaryData): void
    {
        $this->supplementaryData = $supplementaryData;
    }
}
