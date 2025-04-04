<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Context;

interface OAuthContextInterface
{
    public function getCacheKey(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getBody(): array;

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array;
}
