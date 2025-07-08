<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_value_privacy_consent_value',
    required: ['consented']
)]
class PrivacyConsentValue extends Struct implements ValueInterface
{
    /**
     * Whether privacy policy was consented to
     */
    #[OA\Property(type: 'boolean')]
    protected bool $consented;

    /**
     * Types of consent given
     *
     * Enum: [ data_processing, marketing, third_party_sharing, analytics ]
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
        enum: ['data_processing', 'marketing', 'third_party_sharing', 'analytics']
    )]
    protected ?array $consentTypes = null;

    /**
     * Privacy policy version
     */
    #[OA\Property(type: 'string')]
    protected ?string $policyVersion = null;

    /**
     * When consent was given
     */
    #[OA\Property(type: 'string')]
    protected ?string $consentDate = null;

    public function isConsented(): bool
    {
        return $this->consented;
    }

    public function setConsented(bool $consented): void
    {
        $this->consented = $consented;
    }

    /**
     * @return ?string[]
     */
    public function getConsentTypes(): ?array
    {
        return $this->consentTypes;
    }

    public function addConsentType(string $consentType): void
    {
        if (!\in_array($consentType, ['data_processing', 'marketing', 'third_party_sharing', 'analytics'], true)) {
            throw new \InvalidArgumentException(\sprintf('Consent type "%s" is not a valid consent type.', $consentType));
        }

        $this->consentTypes[] = $consentType;
    }

    public function getPolicyVersion(): ?string
    {
        return $this->policyVersion;
    }

    public function setPolicyVersion(?string $policyVersion): void
    {
        $this->policyVersion = $policyVersion;
    }

    public function getConsentDate(): ?string
    {
        return $this->consentDate;
    }

    public function setConsentDate(?string $consentDate): void
    {
        $this->consentDate = $consentDate;
    }
}
