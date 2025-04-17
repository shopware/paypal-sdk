<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Context;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Contract\Context\OAuthContextInterface;

/**
 * @template T of OAuthContextInterface = OAuthContextInterface
 *
 * @implements ApiContextInterface<T>
 */
class ApiContext implements ApiContextInterface
{
    /** @var array<string, string> */
    protected readonly array $headers;

    /** @var array<string, string> */
    protected readonly array $queryParameters;

    /** @var non-empty-string|null */
    protected readonly ?string $merchantId;

    /**
     * @param T $oauthContext
     * @param array<string, ?string> $headers
     * @param array<string, ?string> $queryParameters
     */
    public function __construct(
        protected readonly OAuthContextInterface $oauthContext,
        protected readonly bool $sandbox,
        ?string $merchantId = null,
        array $headers = [],
        array $queryParameters = [],
        protected readonly bool $thirdParty = false,
    ) {
        $this->merchantId = $merchantId ?: null;
        $this->queryParameters = \array_filter($queryParameters, static fn (?string $value): bool => $value !== null);

        $headers = \array_filter($headers, static fn (?string $value): bool => $value !== null);
        $this->headers = \array_combine(
            \array_map(static fn (string $key): string => \strtolower($key), \array_keys($headers)),
            \array_values($headers),
        );
    }

    public function getOAuthContext(): OAuthContextInterface
    {
        return $this->oauthContext;
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function getMerchantId(): ?string
    {
        return $this->merchantId;
    }

    public function isThirdParty(): bool
    {
        return $this->thirdParty;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    public function withOAuthContext(OAuthContextInterface $oauthContext): static
    {
        /** @phpstan-ignore-next-line argument.missing - will work */
        return new self(...[...get_object_vars($this), 'oauthContext' => $oauthContext]);
    }

    public function withSandbox(bool $sandbox): static
    {
        /** @phpstan-ignore-next-line argument.missing - will work */
        return new self(...[...get_object_vars($this), 'sandbox' => $sandbox]);
    }

    public function withMerchantId(?string $merchantId): static
    {
        /** @phpstan-ignore-next-line argument.missing - will work */
        return new self(...[...get_object_vars($this), 'merchantId' => $merchantId]);
    }

    public function withHeader(string $name, ?string $value): static
    {
        $headers = [...$this->headers, \strtolower($name) => $value];

        /** @phpstan-ignore-next-line argument.missing - will work */
        return new self(...[...get_object_vars($this), 'headers' => $headers]);
    }

    public function withQueryParameter(string $name, ?string $value): static
    {
        $queryParameters = [...$this->queryParameters, $name => $value];

        /** @phpstan-ignore-next-line argument.missing - will work */
        return new self(...[...get_object_vars($this), 'queryParameters' => $queryParameters]);
    }

    public function withThirdParty(bool $thirdParty): static
    {
        /** @phpstan-ignore-next-line argument.missing - will work */
        return new self(...[...get_object_vars($this), 'thirdParty' => $thirdParty]);
    }

    public function withPartnerAttributionId(?string $partnerAttributionId): self
    {
        return $this->withHeader('PayPal-Partner-Attribution-Id', $partnerAttributionId);
    }

    public function withPreferRepresentation(bool $preferRepresentation): self
    {
        return $this->withHeader('Prefer', $preferRepresentation ? 'return=representation' : null);
    }

    public function withRequestId(?string $requestId): self
    {
        return $this->withHeader('PayPal-Request-Id', $requestId);
    }

    public function withClientMetadataId(?string $clientMetadataId): self
    {
        return $this->withHeader('PayPal-Client-Metadata-Id', $clientMetadataId);
    }

    public function getHeader(string $name): ?string
    {
        foreach ($this->headers as $key => $value) {
            if (\strtolower($key) === \strtolower($name)) {
                return $value;
            }
        }

        return null;
    }

    public function getQueryParameter(string $name): ?string
    {
        foreach ($this->queryParameters as $key => $value) {
            if ($key === $name) {
                return $value;
            }
        }

        return null;
    }
}
