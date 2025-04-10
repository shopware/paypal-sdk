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
 * A context typically used to retrieve credentials based on a given authorisation from an onboarding.
 */
class AuthorizationCodeOAuthContext implements OAuthContextInterface
{
    public function __construct(
        #[\SensitiveParameter]
        private readonly string $authCode,
        #[\SensitiveParameter]
        private readonly string $sharedId,
        #[\SensitiveParameter]
        private readonly string $nonce,
    ) {}

    public function getCacheKey(ApiContextInterface $context): ?string
    {
        return \hash('xxh128', \sprintf(
            'authorization-%s-%s-%s-%s',
            $this->authCode,
            $this->sharedId,
            $this->nonce,
            $context->isSandbox() ? 'true' : 'false',
        ));
    }

    /**
     * @return array{grant_type: string, code: string, code_verifier: string}
     */
    public function getBody(): array
    {
        return [
            'grant_type' => 'authorization_code',
            'code' => $this->authCode,
            'code_verifier' => $this->nonce,
        ];
    }

    /**
     * @return array{Authorization: string}
     */
    public function getHeaders(): array
    {
        return ['Authorization' => \sprintf('Basic %s', base64_encode(\sprintf('%s:', $this->sharedId)))];
    }
}
