<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_money',
    required: ['currencyCode', 'value']
)]
class Money extends Struct
{
    /**
     * minLength: 3
     * maxLength: 3
     * pattern: ^[\S\s]*$
     * example: USD
     *
     * The 3-character ISO-4217 currency code that identifies the currency.
     */
    #[OA\Property(
        type: 'string',
        maxLength: 3,
        minLength: 3,
        pattern: '^[\S\s]*$'
    )]
    protected string $currencyCode;

    /**
     * minLength: 0
     * maxLength: 32
     * pattern: ^((-?[0-9]+)|(-?([0-9]+)?[.][0-9]+))$
     * example: 25.00
     *
     * The value, which might be: An integer for currencies like JPY that are not typically fractional. A decimal fraction for currencies like TND that are subdivided into thousandths. For the required number of decimal places for a currency code, see Currency Codes.
     */
    #[OA\Property(
        type: 'string',
        maxLength: 0,
        minLength: 32,
        pattern: '^((-?[0-9]+)|(-?([0-9]+)?[.][0-9]+))$'
    )]
    protected string $value;
}
