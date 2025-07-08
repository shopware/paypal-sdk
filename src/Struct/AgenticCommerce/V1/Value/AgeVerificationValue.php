<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

/**
 * @experimental
 */
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_value_age_verification_value',
    required: ['confirmed']
)]
class AgeVerificationValue implements ValueInterface
{
    /**
     * Whether age verification was confirmed
     */
    #[OA\Property(type: 'boolean')]
    protected bool $confirmed = false;

    /**
     * Method used for age verification
     */
    #[OA\Property(
        type: 'string',
        enum: ['self_declaration', 'id_verification', 'third_party']
    )]
    protected ?string $verificationMethod = null;

    /**
     * When verification was completed
     */
    #[OA\Property(type: 'string')]
    protected ?string $verificationDate = null;

    public function getConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): void
    {
        $this->confirmed = $confirmed;
    }

    public function getVerificationMethod(): ?string
    {
        return $this->verificationMethod;
    }

    public function setVerificationMethod(?string $verificationMethod): void
    {
        if (!\in_array($verificationMethod, ['self_declaration', 'id_verification', 'third_party'], true)) {
            // TODO: Better one?
            throw new \RuntimeException(\sprintf('Invalid value for verification method "%s".', $verificationMethod));
        }

        $this->verificationMethod = $verificationMethod;
    }

    public function getVerificationDate(): ?string
    {
        return $this->verificationDate;
    }

    public function setVerificationDate(?string $verificationDate): void
    {
        $this->verificationDate = $verificationDate;
    }
}
