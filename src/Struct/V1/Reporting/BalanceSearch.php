<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_balance_search')]
class BalanceSearch extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $asOfTime = null;

    #[OA\Property(type: 'string', nullable: true, maxLength: 3, minLength: 3)]
    protected ?string $currencyCode = null;

    public function getAsOfTime(): ?string
    {
        return $this->asOfTime;
    }

    public function setAsOfTime(?string $asOfTime): void
    {
        $this->asOfTime = $asOfTime;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(?string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return \array_filter(parent::jsonSerialize(), static fn (mixed $value): bool => $value !== null);
    }
}
