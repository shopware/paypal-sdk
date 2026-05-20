<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Reporting\Money;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_incentive_detail')]
class IncentiveDetail extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $incentiveType = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $incentiveCode = null;

    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $incentiveAmount = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $incentiveProgramCode = null;

    public function getIncentiveType(): ?string
    {
        return $this->incentiveType;
    }

    public function setIncentiveType(?string $incentiveType): void
    {
        $this->incentiveType = $incentiveType;
    }

    public function getIncentiveCode(): ?string
    {
        return $this->incentiveCode;
    }

    public function setIncentiveCode(?string $incentiveCode): void
    {
        $this->incentiveCode = $incentiveCode;
    }

    public function getIncentiveAmount(): ?Money
    {
        return $this->incentiveAmount;
    }

    public function setIncentiveAmount(?Money $incentiveAmount): void
    {
        $this->incentiveAmount = $incentiveAmount;
    }

    public function getIncentiveProgramCode(): ?string
    {
        return $this->incentiveProgramCode;
    }

    public function setIncentiveProgramCode(?string $incentiveProgramCode): void
    {
        $this->incentiveProgramCode = $incentiveProgramCode;
    }
}
