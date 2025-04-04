<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments;

use Shopware\PayPalSDK\Struct\Collection;

/**
 * @extends Collection<Capture>
 */
class CaptureCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return Capture::class;
    }
}
