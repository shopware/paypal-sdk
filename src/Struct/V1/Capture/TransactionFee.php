<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Capture;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\V1\Common\Value;

#[OA\Schema(schema: 'paypal_v1_capture_transaction_fee')]
class TransactionFee extends Value
{
}
