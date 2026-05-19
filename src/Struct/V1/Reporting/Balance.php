<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_balance')]
class Balance extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $currency;

    #[OA\Property(ref: Money::class)]
    protected Money $totalBalance;

    #[OA\Property(type: 'boolean')]
    protected bool $primary;

    #[OA\Property(ref: Money::class)]
    protected Money $availableBalance;

    #[OA\Property(ref: Money::class)]
    protected Money $withheldBalance;

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getTotalBalance(): Money
    {
        return $this->totalBalance;
    }

    public function setTotalBalance(Money $totalBalance): void
    {
        $this->totalBalance = $totalBalance;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    public function setPrimary(bool $primary): void
    {
        $this->primary = $primary;
    }

    public function getAvailableBalance(): Money
    {
        return $this->availableBalance;
    }

    public function setAvailableBalance(Money $availableBalance): void
    {
        $this->availableBalance = $availableBalance;
    }

    public function getWithheldBalance(): Money
    {
        return $this->withheldBalance;
    }

    public function setWithheldBalance(Money $withheldBalance): void
    {
        $this->withheldBalance = $withheldBalance;
    }
}
