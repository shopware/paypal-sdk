<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\Error;

use Shopware\PayPalSDK\Struct\Collection;

/**
 * @extends Collection<Detail>
 */
class DetailCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return Detail::class;
    }
}
