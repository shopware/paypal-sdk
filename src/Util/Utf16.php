<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Util;

/**
 * Measures and cuts strings in UTF-16 code units: an emoji is one character
 * but two units.
 */
final class Utf16
{
    public static function length(string $value): int
    {
        return \intdiv(\strlen(\mb_convert_encoding($value, 'UTF-16LE', 'UTF-8')), 2);
    }

    /**
     * Cuts to at most `$maxUnits` code units without splitting a surrogate pair.
     */
    public static function truncate(string $value, int $maxUnits): string
    {
        if ($maxUnits <= 0) {
            return '';
        }

        $utf16 = \mb_convert_encoding($value, 'UTF-16LE', 'UTF-8');
        if (\strlen($utf16) <= $maxUnits * 2) {
            return $value;
        }

        $cut = \substr($utf16, 0, $maxUnits * 2);

        // a trailing high surrogate is half of a cut pair
        $last = \unpack('v', \substr($cut, -2));
        if (\is_array($last) && $last[1] >= 0xD800 && $last[1] <= 0xDBFF) {
            $cut = \substr($cut, 0, -2);
        }

        return \mb_convert_encoding($cut, 'UTF-8', 'UTF-16LE');
    }
}
