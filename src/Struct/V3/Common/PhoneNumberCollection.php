<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V3\Common;

use Shopware\PayPalSDK\Struct\Collection;
use Shopware\PayPalSDK\Struct\V2\Common\PhoneNumber;

/**
 * @extends Collection<PhoneNumber>
 */
class PhoneNumberCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return PhoneNumber::class;
    }
}
