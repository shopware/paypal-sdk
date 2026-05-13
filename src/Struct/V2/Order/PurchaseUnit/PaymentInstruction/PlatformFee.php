<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\PaymentInstruction;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\Money;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payee;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_payment_instruction_platform_fee')]
class PlatformFee extends Struct
{
    #[OA\Property(ref: Money::class)]
    protected Money $amount;

    #[OA\Property(ref: Payee::class)]
    protected Payee $payee;

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    public function getPayee(): Payee
    {
        return $this->payee;
    }

    public function setPayee(Payee $payee): void
    {
        $this->payee = $payee;
    }
}
