<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Card;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Card\AuthenticationResult\ThreeDSecure;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_card_authentication_result')]
class AuthenticationResult extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $liabilityShift = null;

    #[OA\Property(ref: ThreeDSecure::class, nullable: true)]
    protected ?ThreeDSecure $threeDSecure = null;

    public function getLiabilityShift(): ?string
    {
        return $this->liabilityShift;
    }

    public function setLiabilityShift(?string $liabilityShift): void
    {
        $this->liabilityShift = $liabilityShift;
    }

    public function getThreeDSecure(): ?ThreeDSecure
    {
        return $this->threeDSecure;
    }

    public function setThreeDSecure(?ThreeDSecure $threeDSecure): void
    {
        $this->threeDSecure = $threeDSecure;
    }
}
