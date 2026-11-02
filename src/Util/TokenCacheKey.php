<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Util;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;

final class TokenCacheKey
{
    public static function fromContext(ApiContextInterface $context): ?string
    {
        $key = $context->getOAuthContext()->getCacheKey($context);

        if (!$key) {
            return null;
        }

        // Isolate the serialized token format from SDK 2.x, including custom OAuth contexts.
        // Hash the namespaced key to stay within the 64 characters guaranteed by PSR-16.
        return \hash('sha256', 'paypal-sdk-token-v3-' . $key);
    }
}
