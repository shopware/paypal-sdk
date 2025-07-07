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
    schema: 'paypal_agentic_commerce_v1_value_terms_acceptance_value',
    required: ['accepted', 'termsVersions']
)]
class TermsAcceptanceValue extends Struct implements ValueInterface
{
    /**
     * Whether terms were accepted
     */
    #[OA\Property(type: 'boolean')]
    protected bool $accepted;

    /**
     * Version of terms accepted
     */
    #[OA\Property(type: 'string')]
    protected string $termsVersions;

    /**
     * When terms were accepted
     */
    #[OA\Property(type: 'string')]
    protected string $acceptanceDate;

    /**
     * IP address of acceptance
     */
    #[OA\Property(type: 'string')]
    protected string $ipAddress;
}
