<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\WalletDomain;

use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\V1\WalletDomain;

/**
 * @extends Collection<WalletDomain>
 */
class WalletDomainCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return WalletDomain::class;
    }
}
