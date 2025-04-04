<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Payment\Transaction\ItemList;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\V1\Common\Address;

#[OA\Schema(schema: 'paypal_v1_payment_transaction_item_list_shipping_address')]
class ShippingAddress extends Address
{
    #[OA\Property(type: 'string')]
    protected string $recipientName;

    public function getRecipientName(): string
    {
        return $this->recipientName;
    }

    public function setRecipientName(string $recipientName): void
    {
        $this->recipientName = $recipientName;
    }
}
