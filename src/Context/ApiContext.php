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
    /**
     * @param T $oauthContext
     * @param array<string, string> $headers
     */
    public function __construct(
        protected readonly OAuthContextInterface $oauthContext,
        protected readonly bool $sandbox,
        protected readonly string $merchantId = '',
        protected readonly array $headers = [],
        protected readonly bool $thirdParty = false,
    ) {}

    public function getOAuthContext(): OAuthContextInterface
    {
        return $this->oauthContext;
    }

    public function getSandbox(): bool
    {
        return $this->sandbox;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getThirdParty(): bool
    {
        return $this->thirdParty;
    }

    public function getHeaders(): array
    {
        return $this->headers;
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

    public function withMerchantId(string $merchantId): static
    {
        /** @phpstan-ignore-next-line argument.missing - will work */
        return new self(...[...get_object_vars($this), 'merchantId' => $merchantId]);
    }

    public function withHeader(string $name, mixed $value): static
    {
        $headers = [...$this->headers, $name => $value];

        if ($value === null) {
            unset($headers[$name]);
        }

        /** @phpstan-ignore-next-line argument.missing - will work */
        return new self(...[...get_object_vars($this), 'headers' => $headers]);
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
}
