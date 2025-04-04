<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Plan;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @codeCoverageIgnore
 *
 * @experimental
 *
 * This class is experimental and not officially supported.
 * It is currently not used within the plugin itself. Use with caution.
 */
#[OA\Schema(schema: 'paypal_v1_plan_payment_preferences')]
class PaymentPreferences extends Struct
{
    #[OA\Property(type: 'boolean')]
    protected bool $autoBillOutstanding;

    #[OA\Property(type: 'integer')]
    protected int $paymentFailureThreshold;

    public function isAutoBillOutstanding(): bool
    {
        return $this->autoBillOutstanding;
    }

    public function setAutoBillOutstanding(bool $autoBillOutstanding): void
    {
        $this->autoBillOutstanding = $autoBillOutstanding;
    }

    public function getPaymentFailureThreshold(): int
    {
        return $this->paymentFailureThreshold;
    }

    public function setPaymentFailureThreshold(int $paymentFailureThreshold): void
    {
        $this->paymentFailureThreshold = $paymentFailureThreshold;
    }
}
