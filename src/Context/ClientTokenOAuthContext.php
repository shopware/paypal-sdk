<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Context;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;

/**
 * A context used to receive a client token.
 * Used to safely interact on the client side.
 */
class ClientTokenOAuthContext extends CredentialsOAuthContext
{
    /**
     * @param array<string> $domains
     */
    public function __construct(
        #[\SensitiveParameter]
        private readonly string $clientId,
        #[\SensitiveParameter]
        private readonly string $clientSecret,
        private readonly array $domains = [],
    ) {
        parent::__construct($clientId, $clientSecret);
    }

    public function getCacheKey(ApiContextInterface $context): ?string
    {
        return \hash('xxh128', 'client-token-%s' . parent::getCacheKey($context));
    }

    /**
     * @return array{grant_type: string, response_type: string, 'domains[]'?: string}
     */
    public function getBody(): array
    {
        return \array_merge(
            parent::getBody(),
            ['response_type' => 'client_token'],
            $this->domains ? ['domains[]' => \implode(',', $this->domains)] : [],
        );
    }

    /**
     * @experimental - domain filtering and handling may change
     *
     * Sets domains to be associated with the client token.
     * Ignores invalid domain formats like IPs or domains without TLD.
     *
     * @throws \InvalidArgumentException if any of the given domains is invalid.
     */
    public function withDomains(string ...$domains): self
    {
        foreach ($domains as $key => $domain) {
            if (\filter_var($domain, \FILTER_VALIDATE_DOMAIN) === false) {
                throw new \InvalidArgumentException(\sprintf('Domain "%s" is not valid (FILTER_VALIDATE_DOMAIN)', $domain));
            }

            // for parse_url to extract the host properly, a scheme must be present
            $domain = \parse_url($domain, \PHP_URL_SCHEME) ? $domain : 'https://' . $domain;
            $domain = \parse_url($domain, \PHP_URL_HOST);

            if (!$domain) {
                throw new \InvalidArgumentException(\sprintf('Domain "%s" is not valid (parse_url)', $domains[$key]));
            }

            // remove IPv6 brackets if present
            $domain = \trim($domain, '[]');

            if (\filter_var($domain, \FILTER_VALIDATE_IP)) {
                // IPs are not allowed
                unset($domains[$key]);
                continue;
            }

            // domains without top-level domain like `localhost` are not allowed
            if (\substr_count($domain, '.') < 1) {
                unset($domains[$key]);
                continue;
            }

            $domains[$key] = $domain;
        }

        return new self(
            $this->clientId,
            $this->clientSecret,
            \array_values(\array_unique($domains)),
        );
    }

    /**
     * Domains associated with the client token.
     *
     * @return array<string>
     */
    public function getDomains(): array
    {
        return $this->domains;
    }
}
