<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;

#[OA\Schema(schema: 'paypal_v2_confirm_order')]
class ConfirmOrder extends Struct
{
    #[OA\Property(ref: PaymentSource::class)]
    protected PaymentSource $paymentSource;

    public function getPaymentSource(): PaymentSource
    {
        return $this->paymentSource;
    }

    public function setPaymentSource(PaymentSource $paymentSource): void
    {
        $this->paymentSource = $paymentSource;
    }
}
