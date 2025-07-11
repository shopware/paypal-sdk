<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use Shopware\PayPalSDK\Struct\Collection;

/**
 * @experimental
 *
 * @extends Collection<Coupon>
 */
class CouponCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return Coupon::class;
    }
}
