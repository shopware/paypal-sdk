<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Context;

use Shopware\PayPalSDK\Contract\Context\OAuthContextInterface;

class AuthorizationCodeOAuthContext implements OAuthContextInterface
{
    public function __construct(
        #[\SensitiveParameter]
        protected readonly string $authCode,
        #[\SensitiveParameter]
        protected readonly string $sharedId,
        #[\SensitiveParameter]
        protected readonly string $nonce,
    ) {}

    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    public function getSharedId(): string
    {
        return $this->sharedId;
    }

    public function getNonce(): string
    {
        return $this->nonce;
    }

    public function getCacheKey(): ?string
    {
        return \hash('xxh128', \sprintf(
            'authorization-%s-%s-%s',
            $this->getAuthCode(),
            $this->getSharedId(),
            $this->getNonce(),
        ));
    }

    public function getBody(): array
    {
        return [
            'grant_type' => 'authorization_code',
            'code' => $this->getAuthCode(),
            'code_verifier' => $this->getNonce(),
        ];
    }

    public function getHeaders(): array
    {
        return ['Authorization' => \sprintf('Basic %s', base64_encode(\sprintf('%s:', $this->getSharedId())))];
    }
}
