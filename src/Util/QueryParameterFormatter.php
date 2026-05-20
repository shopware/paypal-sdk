<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Util;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\Struct;

final class QueryParameterFormatter
{
    public static function withStructQueryParameters(ApiContextInterface $context, Struct $query): ApiContextInterface
    {
        foreach ($query->jsonSerialize() as $name => $value) {
            if ($value === null) {
                continue;
            }

            $value = match (true) {
                \is_bool($value) => $value ? 'true' : 'false',
                \is_int($value), \is_float($value), \is_string($value) => (string) $value,
                default => null,
            };

            if ($value === null) {
                continue;
            }

            $context = $context->withQueryParameter($name, $value);
        }

        return $context;
    }
}
