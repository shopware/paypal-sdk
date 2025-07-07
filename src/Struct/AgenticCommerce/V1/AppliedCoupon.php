<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_applied_coupon')]
class AppliedCoupon extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $code;

    #[OA\Property(type: 'string')]
    protected string $description;

    #[OA\Property(ref: Money::class)]
    protected Money $discountAmount;
}
