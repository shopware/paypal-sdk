<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_payment_error_context')]
class PaymentErrorContext extends Struct implements ContextInterface
{
    /**
     * Specific payment issue type
     *
     * Enum: [ PAYMENT_AMOUNT_TOO_LARGE, PAYMENT_AMOUNT_TOO_SMALL, PAYMENT_METHOD_NOT_ACCEPTED, CURRENCY_CONVERSION_FAILED, PAYMENT_PROCESSOR_UNAVAILABLE, MERCHANT_ACCOUNT_ISSUE, PAYMENT_DECLINED, PAYMENT_INSUFFICIENT_FUNDS, PAYMENT_EXPIRED, PAYMENT_FRAUD_DETECTED ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['PAYMENT_AMOUNT_TOO_LARGE', 'PAYMENT_AMOUNT_TOO_SMALL', 'PAYMENT_METHOD_NOT_ACCEPTED', 'CURRENCY_CONVERSION_FAILED', 'PAYMENT_PROCESSOR_UNAVAILABLE', 'MERCHANT_ACCOUNT_ISSUE', 'PAYMENT_DECLINED', 'PAYMENT_INSUFFICIENT_FUNDS', 'PAYMENT_EXPIRED', 'PAYMENT_FRAUD_DETECTED']
    )]
    protected string $specificIssue;

    /**
     * Total order amount
     */
    #[OA\Property(type: 'string')]
    protected string $orderTotal;

    /**
     * Maximum payment limit
     */
    #[OA\Property(type: 'string')]
    protected string $paymentLimit;

    /**
     * Minimum payment amount
     */
    #[OA\Property(type: 'string')]
    protected string $minimumAmount;

    /**
     * Amount exceeding limit
     */
    #[OA\Property(type: 'string')]
    protected string $excessAmount;

    /**
     * Payment method being used
     */
    #[OA\Property(type: 'string')]
    protected string $paymentMethod;

    /**
     * Transaction currency
     */
    #[OA\Property(type: 'string')]
    protected string $currencyCode;

    /**
     * Source currency for conversion
     */
    #[OA\Property(type: 'string')]
    protected string $fromCurrency;

    /**
     * Target currency for conversion
     */
    #[OA\Property(type: 'string')]
    protected string $toCurrency;

    /**
     * Currency conversion service status
     */
    #[OA\Property(type: 'string')]
    protected string $conversionService;

    /**
     * List of supported payment methods
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $supportedPaymentMethods;

    /**
     * Payment processor specific error code
     */
    #[OA\Property(type: 'string')]
    protected string $processorErrorCode;

    /**
     * Reason for payment decline
     */
    #[OA\Property(type: 'string')]
    protected string $declineReason;

    /**
     * Payment token that was declined
     */
    #[OA\Property(type: 'string')]
    protected string $paymentToken;
}
