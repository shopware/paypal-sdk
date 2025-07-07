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
    protected array $consentTypes;

    /**
     * Privacy policy version
     */
    #[OA\Property(type: 'string')]
    protected string $policyVersion;

    /**
     * When consent was given
     */
    #[OA\Property(type: 'string')]
    protected string $consentDate;
}
