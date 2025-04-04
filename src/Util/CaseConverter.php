<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Util;

final class CaseConverter
{
    /**
     * Convert from snake_case to camelCase
     */
    public static function normalize(string $propertyName): string
    {
        /** @phpstan-ignore-next-line argument.type */
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($propertyName)));
    }

    /**
     * Convert from camelCase to snake_case
     */
    public static function denormalize(string $propertyName): string
    {
        /** @phpstan-ignore-next-line argument.type */
        return lcfirst(preg_replace_callback('/(^|_|\.)+(.)/', fn ($match) => ($match[1] === '.' ? '_' : '') . strtoupper($match[2]), $propertyName));
    }
}
