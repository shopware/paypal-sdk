<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Referral\Operation;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Referral\Operation\ApiIntegrationPreference\RestApiIntegration;

#[OA\Schema(schema: 'paypal_v2_referral_operation_api_integration_preference')]
class ApiIntegrationPreference extends Struct
{
    #[OA\Property(ref: RestApiIntegration::class)]
    protected RestApiIntegration $restApiIntegration;

    public function getRestApiIntegration(): RestApiIntegration
    {
        return $this->restApiIntegration;
    }

    public function setRestApiIntegration(RestApiIntegration $restApiIntegration): void
    {
        $this->restApiIntegration = $restApiIntegration;
    }
}
