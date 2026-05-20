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

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_tax_amount')]
class TaxAmount extends Struct
{
    #[OA\Property(ref: Money::class, nullable: true)]
    protected ?Money $taxAmount = null;

    public function getTaxAmount(): ?Money
    {
        return $this->taxAmount;
    }

    public function setTaxAmount(?Money $taxAmount): void
    {
        $this->taxAmount = $taxAmount;
    }
}
