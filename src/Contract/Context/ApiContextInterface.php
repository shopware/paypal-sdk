<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Context;

/**
 * @template T of OAuthContextInterface = OAuthContextInterface
 */
interface ApiContextInterface
{
    /**
     * @return T
     */
    public function getOAuthContext(): OAuthContextInterface;

    public function getSandbox(): bool;

    public function getMerchantId(): string;

    public function getThirdParty(): bool;

    /**
     * @return array<string, string> $headers
     */
    public function getHeaders(): array;

    /**
     * @return ?string Value of header or null if unset
     */
    public function getHeader(string $name): ?string;

    /**
     * @template I of OAuthContextInterface
     *
     * @param I $oauthContext
     *
     * @return static<I>
     */
    public function withOAuthContext(OAuthContextInterface $oauthContext): static;

    public function withSandbox(bool $sandbox): static;

    public function withMerchantId(string $merchantId): static;

    public function withHeader(string $name, ?string $value): static;

    public function withThirdParty(bool $thirdParty): static;
}
