<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_balances')]
class Balances extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: Balance::class))]
    protected BalanceCollection $balances;

    #[OA\Property(type: 'string')]
    protected string $accountId;

    #[OA\Property(type: 'string')]
    protected string $asOfTime;

    #[OA\Property(type: 'string')]
    protected string $lastRefreshTime;

    public function getBalances(): BalanceCollection
    {
        return $this->balances;
    }

    public function setBalances(BalanceCollection $balances): void
    {
        $this->balances = $balances;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getAsOfTime(): string
    {
        return $this->asOfTime;
    }

    public function setAsOfTime(string $asOfTime): void
    {
        $this->asOfTime = $asOfTime;
    }

    public function getLastRefreshTime(): string
    {
        return $this->lastRefreshTime;
    }

    public function setLastRefreshTime(string $lastRefreshTime): void
    {
        $this->lastRefreshTime = $lastRefreshTime;
    }
}
