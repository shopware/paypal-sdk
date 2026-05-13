<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_eligible_methods_ideal', type: 'object')]
class Ideal extends Struct
{
}
