<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value;

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
    protected bool $confirmed;

    /**
     * Method used for age verification
     *
     * Enum: [ self_declaration, id_verification, third_party ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['self_declaration', 'id_verification', 'third_party']
    )]
    protected string $verificationMethod;

    /**
     * When verification was completed
     */
    #[OA\Property(type: 'string')]
    protected string $verificationDate;
}
