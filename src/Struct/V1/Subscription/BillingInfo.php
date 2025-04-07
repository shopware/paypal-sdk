<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Subscription;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Subscription\BillingInfo\CycleExecution;
use Shopware\PayPalSDK\Struct\V1\Subscription\BillingInfo\CycleExecutionCollection;
use Shopware\PayPalSDK\Struct\V1\Subscription\BillingInfo\LastPayment;
use Shopware\PayPalSDK\Struct\V1\Subscription\BillingInfo\OutstandingBalance;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v1_subscription_billing_info')]
class BillingInfo extends Struct
{
    #[OA\Property(ref: OutstandingBalance::class)]
    protected OutstandingBalance $outstandingBalance;

    #[OA\Property(type: 'array', items: new OA\Items(ref: CycleExecution::class))]
    protected CycleExecutionCollection $cycleExecutions;

    #[OA\Property(ref: LastPayment::class)]
    protected LastPayment $lastPayment;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $nextBillingTime = null;

    #[OA\Property(type: 'integer')]
    protected int $failedPaymentsCount;

    public function getOutstandingBalance(): OutstandingBalance
    {
        return $this->outstandingBalance;
    }

    public function setOutstandingBalance(OutstandingBalance $outstandingBalance): void
    {
        $this->outstandingBalance = $outstandingBalance;
    }

    public function getCycleExecutions(): CycleExecutionCollection
    {
        return $this->cycleExecutions;
    }

    public function setCycleExecutions(CycleExecutionCollection $cycleExecutions): void
    {
        $this->cycleExecutions = $cycleExecutions;
    }

    public function getLastPayment(): LastPayment
    {
        return $this->lastPayment;
    }

    public function setLastPayment(LastPayment $lastPayment): void
    {
        $this->lastPayment = $lastPayment;
    }

    public function getNextBillingTime(): ?string
    {
        return $this->nextBillingTime;
    }

    public function setNextBillingTime(?string $nextBillingTime): void
    {
        $this->nextBillingTime = $nextBillingTime;
    }

    public function getFailedPaymentsCount(): int
    {
        return $this->failedPaymentsCount;
    }

    public function setFailedPaymentsCount(int $failedPaymentsCount): void
    {
        $this->failedPaymentsCount = $failedPaymentsCount;
    }
}
