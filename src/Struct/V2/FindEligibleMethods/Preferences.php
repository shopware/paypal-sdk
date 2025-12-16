<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\FindEligibleMethods;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\ConstantsV2;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\FindEligibleMethods\Preferences\PaymentSourceConstraint;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_find_eligible_methods_preferences')]
class Preferences extends Struct
{
    public const PAYMENT_FLOW_ONE_TIME_PAYMENT = 'ONE_TIME_PAYMENT';

    #[OA\Property(type: 'string', enum: [self::PAYMENT_FLOW_ONE_TIME_PAYMENT])]
    protected string $paymentFlow;

    #[OA\Property(type: 'boolean')]
    protected bool $commit;

    #[OA\Property(type: 'string', enum: [ConstantsV2::INTENT_CAPTURE, ConstantsV2::INTENT_AUTHORIZE])]
    protected string $intent;

    #[OA\Property(type: 'boolean')]
    protected bool $vault;

    #[OA\Property(ref: PaymentSourceConstraint::class)]
    protected PaymentSourceConstraint $paymentSourceConstraint;

    public function getPaymentFlow(): string
    {
        return $this->paymentFlow;
    }

    public function setPaymentFlow(string $paymentFlow): void
    {
        $this->paymentFlow = $paymentFlow;
    }

    public function isCommit(): bool
    {
        return $this->commit;
    }

    public function setCommit(bool $commit): void
    {
        $this->commit = $commit;
    }

    public function getIntent(): string
    {
        return $this->intent;
    }

    public function setIntent(string $intent): void
    {
        $this->intent = $intent;
    }

    public function isVault(): bool
    {
        return $this->vault;
    }

    public function setVault(bool $vault): void
    {
        $this->vault = $vault;
    }

    public function getPaymentSourceConstraint(): PaymentSourceConstraint
    {
        return $this->paymentSourceConstraint;
    }

    public function setPaymentSourceConstraint(PaymentSourceConstraint $paymentSourceConstraint): void
    {
        $this->paymentSourceConstraint = $paymentSourceConstraint;
    }
}
