<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Util;

final class DateTimeFormatter
{
    public static function formatQueryDateTime(\DateTimeInterface $dateTime): string
    {
        if ($dateTime->getOffset() === 0) {
            return $dateTime->format('Y-m-d\TH:i:s\Z');
        }

        return $dateTime->format('Y-m-d\TH:i:sP');
    }
}
