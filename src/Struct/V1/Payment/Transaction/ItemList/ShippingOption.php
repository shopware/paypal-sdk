<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Payment\Transaction\ItemList;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_v1_payment_transaction_item_list_shipping_option',
    properties: [], // so an empty object will be generated
)]
class ShippingOption extends Struct
{
}
