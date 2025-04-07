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
        private readonly string $clientId,
        #[\SensitiveParameter]
        private readonly string $clientSecret,
    ) {}

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getCacheKey(): ?string
    {
        return \hash('xxh128', \sprintf(
            'credentials-%s-%s',
            $this->clientId,
            $this->clientSecret,
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
            $this->clientId,
            $this->clientSecret,
        )))];
    }

    public function intoUserIdContext(?string $targetCustomerId = null): UserIdOAuthContext
    {
        return new UserIdOAuthContext($this->clientId, $this->clientSecret, $targetCustomerId);
    }
}
