<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Context;

use Shopware\PayPalSDK\Struct\V1\Token;

/**
 * A context used for authenticating against the token endpoint.
 */
interface OAuthContextInterface
{
    /**
     * Context identity for a cached {@see Token}. The gateway namespaces this key
     * for the serialized token format using {@see \Shopware\PayPalSDK\Util\TokenCacheKey}.
     *
     * @return string|null If `null` is returned, this context is not cacheable.
     */
    public function getCacheKey(ApiContextInterface $context): ?string;

    /**
     * Body to send for requesting a {@see Token}.
     *
     * @return array<string, mixed>
     */
    public function getBody(): array;

    /**
     * Headers to send for requesting a {@see Token}.
     *
     * @return array<string, string>
     */
    public function getHeaders(): array;
}
