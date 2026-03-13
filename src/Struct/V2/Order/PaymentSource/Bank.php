<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Bank\SepaDebit;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_bank')]
class Bank extends Struct
{
    #[OA\Property(ref: SepaDebit::class)]
    protected SepaDebit $sepaDebit;

    public function getSepaDebit(): SepaDebit
    {
        return $this->sepaDebit;
    }

    public function setSepaDebit(SepaDebit $sepaDebit): void
    {
        $this->sepaDebit = $sepaDebit;
    }
}
