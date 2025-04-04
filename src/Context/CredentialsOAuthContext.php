<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Context;

use Shopware\PayPalSDK\Contract\Context\OAuthContextInterface;

class CredentialsOAuthContext implements OAuthContextInterface
{
    public function __construct(
        #[\SensitiveParameter]
        protected readonly string $clientId,
        #[\SensitiveParameter]
        protected readonly string $clientSecret,
    ) {}

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getCacheKey(): ?string
    {
        return \hash('xxh128', \sprintf(
            'credentials-%s-%s',
            $this->getClientId(),
            $this->getClientSecret(),
        ));
    }

    public function getBody(): array
    {
        return ['grant_type' => 'client_credentials'];
    }

    public function getHeaders(): array
    {
        return ['Authorization' => \sprintf('Basic %s', base64_encode(\sprintf(
            '%s:%s',
            $this->getClientId(),
            $this->getClientSecret(),
        )))];
    }
}
