<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Util;

use Shopware\PayPalSDK\Exception\ApiException;
use Shopware\PayPalSDK\Exception\ErrorApiException;

/**
 * Tells who has to act on a PayPal error, `null` means none of the categories applies, e.g. a technical error.
 */
final class IssueClassifier
{
    /**
     * One response can carry several issues, so the first matching category wins: most specific on top.
     */
    private const CATEGORIES = [
        IssueCategory::MERCHANT->value => [
            ApiException::CODE_PAYEE_ACCOUNT_RESTRICTED,
            ApiException::CODE_PAYEE_ACCOUNT_LOCKED_OR_CLOSED,
            ApiException::CODE_PAYEE_ACCOUNT_NOT_SUPPORTED,
            ApiException::CODE_PAYEE_ACCOUNT_NOT_VERIFIED,
            ApiException::CODE_PAYEE_NOT_CONSENTED,
            ApiException::CODE_PERMISSION_NOT_GRANTED,
            ApiException::CODE_PARTNER_OR_MERCHANT_RESTRICTED_FOR_COUNTRY,
            ApiException::CODE_TRANSACTION_RECEIVING_LIMIT_EXCEEDED,
            ApiException::CODE_UNSUPPORTED_INTENT_FOR_KYC_INCOMPLETE_PAYEE,
            ApiException::CODE_REFUND_FAILED_INSUFFICIENT_FUNDS,
            // products the account is not enabled for
            ApiException::CODE_NOT_ENABLED_FOR_CARD_PROCESSING,
            ApiException::CODE_PAYEE_NOT_ENABLED_FOR_CARD_PROCESSING,
            ApiException::CODE_NOT_ENABLED_FOR_BANK_PROCESSING,
            ApiException::CODE_PAYEE_NOT_ENABLED_FOR_BANK_PROCESSING,
            ApiException::CODE_SETUP_ERROR_FOR_BANK,
            ApiException::CODE_NOT_ENABLED_FOR_APPLE_PAY,
            ApiException::CODE_NOT_ENABLED_FOR_GOOGLE_PAY,
            ApiException::CODE_NOT_ENABLED_FOR_PAYMENT_SOURCE,
            ApiException::CODE_NOT_ENABLED_TO_VAULT_PAYMENT_SOURCE,
            ApiException::CODE_AUTH_CAPTURE_NOT_ENABLED,
            ApiException::CODE_DOMAIN_NOT_REGISTERED,
            ApiException::CODE_NOT_ENABLED_FOR_PUI_PROCESSING,
            ApiException::CODE_MERCHANT_MISSING_SUBSCRIPTION,
            ApiException::CODE_DOMAIN_REGISTERED_WITH_ANOTHER_MERCHANT,
            ApiException::CODE_DOMAIN_VERIFICATION_FAILED_BY_PROVIDER,
            ApiException::CODE_FAILED_TO_PULL_DOMAIN_VERIFICATION_FILE,
            ApiException::CODE_INCORRECT_DOMAIN_VERIFICATION_FILE,
            ApiException::CODE_CLOSED_USER,
            ApiException::CODE_RISK_DECLINE,
        ],
        IssueCategory::BUYER_RESOLVABLE->value => [
            ApiException::CODE_PAYER_ACTION_REQUIRED,
            ApiException::CODE_ORDER_NOT_APPROVED,
            ApiException::CODE_REDIRECT_PAYER_FOR_ALTERNATE_FUNDING,
            ApiException::CODE_INVALID_SECURITY_CODE,
            ApiException::CODE_INVALID_SECURITY_CODE_LENGTH,
            ApiException::CODE_PAYMENT_SOURCE_INFO_CANNOT_BE_VERIFIED,
            ApiException::CODE_POSTAL_CODE_REQUIRED,
            ApiException::CODE_INVALID_POSTAL_CODE,
            ApiException::CODE_INVALID_POSTAL_CODE_LENGTH,
            ApiException::CODE_CITY_REQUIRED,
            ApiException::CODE_SHIPPING_ADDRESS_INVALID,
            ApiException::CODE_INVALID_SHIPPING_ADDRESS,
            ApiException::CODE_BILLING_ADDRESS_INVALID,
            ApiException::CODE_INVALID_BILLING_ADDRESS,
            ApiException::CODE_ADDRESS_VALIDATION_ERROR,
        ],
        IssueCategory::BUYER->value => [
            // payment method
            ApiException::CODE_INSTRUMENT_DECLINED,
            ApiException::CODE_PAYMENT_SOURCE_DECLINED_BY_PROCESSOR,
            ApiException::CODE_PAYMENT_SOURCE_CANNOT_BE_USED,
            ApiException::CODE_CONTINGENCY_NOT_SUCCESSFUL,
            ApiException::CODE_CARD_EXPIRED,
            ApiException::CODE_REFERENCED_CARD_EXPIRED,
            ApiException::CODE_CARD_BRAND_NOT_SUPPORTED,
            ApiException::CODE_CARD_TYPE_NOT_SUPPORTED,
            ApiException::CODE_CARD_COUNTRY_NOT_SUPPORTED,
            ApiException::CODE_CARD_ISSUER_COUNTRY_NOT_SUPPORTED,
            ApiException::CODE_CARD_DATA_NOT_FOUND,
            ApiException::CODE_CURRENCY_NOT_SUPPORTED_FOR_CARD_TYPE,
            ApiException::CODE_CURRENCY_NOT_SUPPORTED_FOR_CARD_BRAND,
            // payer account and risk checks, including the merchant's own fraud rules
            ApiException::CODE_PAYER_CANNOT_PAY,
            ApiException::CODE_PAYER_NOT_SUPPORTED_BY_PAYMENT_SOURCE,
            ApiException::CODE_PAYER_ACCOUNT_LOCKED_OR_CLOSED,
            ApiException::CODE_PAYER_ACCOUNT_RESTRICTED,
            ApiException::CODE_DOMESTIC_TRANSACTION_REQUIRED,
            ApiException::CODE_MAX_NUMBER_OF_PAYMENT_ATTEMPTS_EXCEEDED,
            ApiException::CODE_TRANSACTION_LIMIT_EXCEEDED,
            ApiException::CODE_TRANSACTION_REFUSED,
            ApiException::CODE_PAYMENT_DENIED,
            ApiException::CODE_COMPLIANCE_VIOLATION,
            ApiException::CODE_PAYEE_BLOCKED_TRANSACTION,
            ApiException::CODE_TRANSACTION_BLOCKED_BY_PAYEE,
        ],
        IssueCategory::UNRESOLVABLE->value => [
            ApiException::CODE_ORDER_EXPIRED,
            ApiException::CODE_AUTHORIZATION_EXPIRED,
            ApiException::CODE_PREVIOUS_TRANSACTION_REFERENCE_HAS_CHARGEBACK,
            ApiException::CODE_PNREF_EXPIRED,
            ApiException::CODE_PAYPAL_TRANSACTION_ID_EXPIRED,
        ],
        IssueCategory::LOGIC->value => [
            // amounts
            ApiException::CODE_AMOUNT_MISMATCH,
            ApiException::CODE_ITEM_TOTAL_MISMATCH,
            ApiException::CODE_TAX_TOTAL_MISMATCH,
            ApiException::CODE_DISCOUNT_TOTAL_MISMATCH,
            ApiException::CODE_ITEM_TOTAL_REQUIRED,
            ApiException::CODE_TAX_TOTAL_REQUIRED,
            ApiException::CODE_APPLE_PAY_AMOUNT_MISMATCH,
            ApiException::CODE_DECIMAL_PRECISION,
            ApiException::CODE_DECIMALS_NOT_SUPPORTED,
            ApiException::CODE_CANNOT_BE_ZERO_OR_NEGATIVE,
            ApiException::CODE_CANNOT_BE_NEGATIVE,
            ApiException::CODE_MAX_VALUE_EXCEEDED,
            ApiException::CODE_MULTI_CURRENCY_ORDER,
            // shipping options
            ApiException::CODE_SHIPPING_OPTION_NOT_SELECTED,
            ApiException::CODE_MULTIPLE_SHIPPING_OPTION_SELECTED,
            ApiException::CODE_DUPLICATE_SHIPPING_OPTION_ID,
            ApiException::CODE_PREFERRED_SHIPPING_OPTION_AMOUNT_MISMATCH,
            // parameters
            ApiException::CODE_MISSING_REQUIRED_PARAMETER,
            ApiException::CODE_INVALID_PARAMETER_VALUE,
            ApiException::CODE_INCOMPATIBLE_PARAMETER_VALUE,
            ApiException::CODE_PAYPAL_REQUEST_ID_REQUIRED,
            ApiException::CODE_PAYEE_ACCOUNT_INVALID,
            ApiException::CODE_INVALID_PAYEE_ACCOUNT,
            // actions the state of the order or authorization rules out, e.g. a second capture
            ApiException::CODE_ORDER_ALREADY_CAPTURED,
            ApiException::CODE_ORDER_ALREADY_AUTHORIZED,
            ApiException::CODE_ORDER_ALREADY_COMPLETED,
            ApiException::CODE_ORDER_COMPLETED_OR_VOIDED,
            ApiException::CODE_PREVIOUSLY_CAPTURED,
            ApiException::CODE_PREVIOUS_TRANSACTION_REFERENCE_VOIDED,
            // error names of malformed requests, after the issues as they also carry buyer issues
            ApiException::CODE_INVALID_REQUEST,
            ApiException::CODE_MEDIA_TYPE_NOT_ACCEPTABLE,
            ApiException::CODE_UNSUPPORTED_MEDIA_TYPE,
            ApiException::CODE_METHOD_NOT_SUPPORTED,
        ],
    ];

    public static function classify(ApiException $e): ?IssueCategory
    {
        $issues = $e instanceof ErrorApiException ? $e->getDetails()->getIssues() : [];

        return self::classifyCodes($e->getErrorCode(), ...$issues);
    }

    /**
     * For integrations that read the error from the response body themselves.
     *
     * @param string|null ...$codes error name and issues of one response, e.g. `UNPROCESSABLE_ENTITY` and `INSTRUMENT_DECLINED`
     */
    public static function classifyCodes(?string ...$codes): ?IssueCategory
    {
        foreach (self::CATEGORIES as $category => $categoryCodes) {
            if (\array_intersect($codes, $categoryCodes)) {
                return IssueCategory::from($category);
            }
        }

        return null;
    }
}
