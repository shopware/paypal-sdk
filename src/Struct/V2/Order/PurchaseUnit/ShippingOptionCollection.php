<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;

use Shopware\PayPalSDK\Struct\Collection;

/**
 * @extends Collection<ShippingOption>
 */
class ShippingOptionCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return ShippingOption::class;
    }
}
