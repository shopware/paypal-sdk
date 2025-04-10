<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Context;

/**
 * A context used to make a request against the PayPal API.
 *
 * @template T of OAuthContextInterface = OAuthContextInterface
 */
interface ApiContextInterface
{
    /**
     * A context used for authenticating against the token endpoint.
     *
     * @return T
     */
    public function getOAuthContext(): OAuthContextInterface;

    /**
     * Whether sandbox is enabled or not.
     */
    public function isSandbox(): bool;

    /**
     * A merchant ID if available. Is an empty string if unset.
     */
    public function getMerchantId(): string;

    /**
     * Whether third party is enabled or not.
     * In a third party context some additional headers have to be set.
     */
    public function isThirdParty(): bool;

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array;

    /**
     * @return ?string Value of header or null if unset.
     */
    public function getHeader(string $name): ?string;

    /**
     * @return array<string, string>
     */
    public function getQueryParameters(): array;

    /**
     * @return ?string Value of header or null if unset.
     */
    public function getQueryParameter(string $name): ?string;

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

    public function withQueryParameter(string $name, ?string $value): static;

    public function withThirdParty(bool $thirdParty): static;
}
