<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * Exception used for any PayPal API related error.
 */
class ApiException extends \Exception implements \JsonSerializable
{
    public const string MESSAGE_DELIMITER = ' | ';

    public const string CODE_UNKNOWN = 'UNKNOWN';
    public const string CODE_SERVICE_UNAVAILABLE = 'SERVICE_UNAVAILABLE';
    public const string CODE_INTERNAL_SERVER_ERROR = 'INTERNAL_SERVER_ERROR';

    // https://developer.paypal.com/api/rest/responses/#link-authorizationerrors
    public const string CODE_INVALID_AUTHZ_CODE = 'INVALID_AUTHZ_CODE';
    public const string CODE_INVALID_CLIENT = 'INVALID_CLIENT';
    public const string CODE_INVALID_REDIRECT_URI = 'INVALID_REDIRECT_URI';
    public const string CODE_INVALID_RESPONSE_TYPE = 'INVALID_RESPONSE_TYPE';
    public const string CODE_INVALID_TOKEN = 'INVALID_TOKEN';
    public const string CODE_INVALID_USER = 'INVALID_USER';
    public const string CODE_NOT_AUTHORIZED = 'NOT_AUTHORIZED';
    public const string CODE_REMEMBER_ME_EXPIRED = 'REMEMBER_ME_EXPIRED';
    public const string CODE_RISK_DECLINE = 'RISK_DECLINE';
    public const string CODE_UNAUTHORIZED_CLIENT = 'UNAUTHORIZED_CLIENT';
    public const string CODE_UNSUPPORTED_GRANT_TYPE = 'UNSUPPORTED_GRANT_TYPE';

    // https://developer.paypal.com/api/rest/responses/#link-failedrequests
    public const string CODE_INVALID_PARAMETER_VALUE = 'INVALID_PARAMETER_VALUE';
    public const string CODE_INVALID_REQUEST = 'INVALID_REQUEST';
    public const string CODE_INVALID_RESOURCE_ID = 'INVALID_RESOURCE_ID';
    public const string CODE_MEDIA_TYPE_NOT_ACCEPTABLE = 'MEDIA_TYPE_NOT_ACCEPTABLE';
    public const string CODE_METHOD_NOT_SUPPORTED = 'METHOD_NOT_SUPPORTED';
    public const string CODE_NOT_AVAILABLE = 'NOT_AVAILABLE';
    public const string CODE_RATE_LIMIT_REACHED = 'RATE_LIMIT_REACHED';
    public const string CODE_RESOURCE_CONFLICT = 'RESOURCE_CONFLICT';
    public const string CODE_RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const string CODE_UNPROCESSABLE_ENTITY = 'UNPROCESSABLE_ENTITY';
    public const string CODE_UNSUPPORTED_MEDIA_TYPE = 'UNSUPPORTED_MEDIA_TYPE';

    public const string CODE_DUPLICATE_ORDER_NUMBER = 'DUPLICATE_TRANSACTION';
    public const string CODE_DUPLICATE_INVOICE_ID = 'DUPLICATE_INVOICE_ID';

    // issues of the error references of the Orders v2, Payments v2 and Vault v3 APIs and of production responses, e.g. https://developer.paypal.com/api/rest/reference/orders/v2/errors/
    // issues the merchant has to fix
    public const string CODE_PAYEE_ACCOUNT_RESTRICTED = 'PAYEE_ACCOUNT_RESTRICTED';
    public const string CODE_PAYEE_ACCOUNT_LOCKED_OR_CLOSED = 'PAYEE_ACCOUNT_LOCKED_OR_CLOSED';
    public const string CODE_PAYEE_ACCOUNT_NOT_SUPPORTED = 'PAYEE_ACCOUNT_NOT_SUPPORTED';
    public const string CODE_PAYEE_ACCOUNT_NOT_VERIFIED = 'PAYEE_ACCOUNT_NOT_VERIFIED';
    public const string CODE_PAYEE_NOT_CONSENTED = 'PAYEE_NOT_CONSENTED';
    public const string CODE_PERMISSION_NOT_GRANTED = 'PERMISSION_NOT_GRANTED';
    public const string CODE_PARTNER_OR_MERCHANT_RESTRICTED_FOR_COUNTRY = 'PARTNER_OR_MERCHANT_RESTRICTED_FOR_COUNTRY';
    public const string CODE_TRANSACTION_RECEIVING_LIMIT_EXCEEDED = 'TRANSACTION_RECEIVING_LIMIT_EXCEEDED';
    public const string CODE_UNSUPPORTED_INTENT_FOR_KYC_INCOMPLETE_PAYEE = 'UNSUPPORTED_INTENT_FOR_KYC_INCOMPLETE_PAYEE';
    public const string CODE_REFUND_FAILED_INSUFFICIENT_FUNDS = 'REFUND_FAILED_INSUFFICIENT_FUNDS';
    public const string CODE_NOT_ENABLED_FOR_CARD_PROCESSING = 'NOT_ENABLED_FOR_CARD_PROCESSING';
    public const string CODE_PAYEE_NOT_ENABLED_FOR_CARD_PROCESSING = 'PAYEE_NOT_ENABLED_FOR_CARD_PROCESSING';
    public const string CODE_NOT_ENABLED_FOR_BANK_PROCESSING = 'NOT_ENABLED_FOR_BANK_PROCESSING';
    public const string CODE_PAYEE_NOT_ENABLED_FOR_BANK_PROCESSING = 'PAYEE_NOT_ENABLED_FOR_BANK_PROCESSING';
    public const string CODE_SETUP_ERROR_FOR_BANK = 'SETUP_ERROR_FOR_BANK';
    public const string CODE_NOT_ENABLED_FOR_APPLE_PAY = 'NOT_ENABLED_FOR_APPLE_PAY';
    public const string CODE_NOT_ENABLED_FOR_GOOGLE_PAY = 'NOT_ENABLED_FOR_GOOGLE_PAY';
    public const string CODE_NOT_ENABLED_FOR_PAYMENT_SOURCE = 'NOT_ENABLED_FOR_PAYMENT_SOURCE';
    public const string CODE_NOT_ENABLED_TO_VAULT_PAYMENT_SOURCE = 'NOT_ENABLED_TO_VAULT_PAYMENT_SOURCE';
    public const string CODE_AUTH_CAPTURE_NOT_ENABLED = 'AUTH_CAPTURE_NOT_ENABLED';
    public const string CODE_DOMAIN_NOT_REGISTERED = 'DOMAIN_NOT_REGISTERED';
    public const string CODE_NOT_ENABLED_FOR_PUI_PROCESSING = 'NOT_ENABLED_FOR_PUI_PROCESSING';
    public const string CODE_MERCHANT_MISSING_SUBSCRIPTION = 'MERCHANT_MISSING_SUBSCRIPTION';
    public const string CODE_DOMAIN_REGISTERED_WITH_ANOTHER_MERCHANT = 'DOMAIN_REGISTERED_WITH_ANOTHER_MERCHANT';
    public const string CODE_DOMAIN_VERIFICATION_FAILED_BY_PROVIDER = 'DOMAIN_VERIFICATION_FAILED_BY_PROVIDER';
    public const string CODE_FAILED_TO_PULL_DOMAIN_VERIFICATION_FILE = 'FAILED_TO_PULL_DOMAIN_VERIFICATION_FILE';
    public const string CODE_INCORRECT_DOMAIN_VERIFICATION_FILE = 'INCORRECT_DOMAIN_VERIFICATION_FILE';
    public const string CODE_CLOSED_USER = 'closed_user';

    // issues the buyer can fix
    public const string CODE_PAYER_ACTION_REQUIRED = 'PAYER_ACTION_REQUIRED';
    public const string CODE_ORDER_NOT_APPROVED = 'ORDER_NOT_APPROVED';
    public const string CODE_REDIRECT_PAYER_FOR_ALTERNATE_FUNDING = 'REDIRECT_PAYER_FOR_ALTERNATE_FUNDING';
    public const string CODE_INVALID_SECURITY_CODE = 'INVALID_SECURITY_CODE';
    public const string CODE_INVALID_SECURITY_CODE_LENGTH = 'INVALID_SECURITY_CODE_LENGTH';
    public const string CODE_PAYMENT_SOURCE_INFO_CANNOT_BE_VERIFIED = 'PAYMENT_SOURCE_INFO_CANNOT_BE_VERIFIED';
    public const string CODE_POSTAL_CODE_REQUIRED = 'POSTAL_CODE_REQUIRED';
    public const string CODE_INVALID_POSTAL_CODE = 'INVALID_POSTAL_CODE';
    public const string CODE_INVALID_POSTAL_CODE_LENGTH = 'INVALID_POSTAL_CODE_LENGTH';
    public const string CODE_CITY_REQUIRED = 'CITY_REQUIRED';
    public const string CODE_SHIPPING_ADDRESS_INVALID = 'SHIPPING_ADDRESS_INVALID';
    public const string CODE_INVALID_SHIPPING_ADDRESS = 'INVALID_SHIPPING_ADDRESS';
    public const string CODE_BILLING_ADDRESS_INVALID = 'BILLING_ADDRESS_INVALID';
    public const string CODE_INVALID_BILLING_ADDRESS = 'INVALID_BILLING_ADDRESS';
    public const string CODE_ADDRESS_VALIDATION_ERROR = 'ADDRESS_VALIDATION_ERROR';

    // issues caused by the buyer that they can't fix in this checkout
    public const string CODE_INSTRUMENT_DECLINED = 'INSTRUMENT_DECLINED';
    public const string CODE_PAYMENT_SOURCE_DECLINED_BY_PROCESSOR = 'PAYMENT_SOURCE_DECLINED_BY_PROCESSOR';
    public const string CODE_PAYMENT_SOURCE_CANNOT_BE_USED = 'PAYMENT_SOURCE_CANNOT_BE_USED';
    public const string CODE_CONTINGENCY_NOT_SUCCESSFUL = 'CONTINGENCY_NOT_SUCCESSFUL';
    public const string CODE_CARD_EXPIRED = 'CARD_EXPIRED';
    public const string CODE_REFERENCED_CARD_EXPIRED = 'REFERENCED_CARD_EXPIRED';
    public const string CODE_CARD_BRAND_NOT_SUPPORTED = 'CARD_BRAND_NOT_SUPPORTED';
    public const string CODE_CARD_TYPE_NOT_SUPPORTED = 'CARD_TYPE_NOT_SUPPORTED';
    public const string CODE_CARD_COUNTRY_NOT_SUPPORTED = 'CARD_COUNTRY_NOT_SUPPORTED';
    public const string CODE_CARD_ISSUER_COUNTRY_NOT_SUPPORTED = 'CARD_ISSUER_COUNTRY_NOT_SUPPORTED';
    public const string CODE_CARD_DATA_NOT_FOUND = 'CARD_DATA_NOT_FOUND';
    public const string CODE_CURRENCY_NOT_SUPPORTED_FOR_CARD_TYPE = 'CURRENCY_NOT_SUPPORTED_FOR_CARD_TYPE';
    public const string CODE_CURRENCY_NOT_SUPPORTED_FOR_CARD_BRAND = 'CURRENCY_NOT_SUPPORTED_FOR_CARD_BRAND';
    public const string CODE_PAYER_CANNOT_PAY = 'PAYER_CANNOT_PAY';
    public const string CODE_PAYER_NOT_SUPPORTED_BY_PAYMENT_SOURCE = 'PAYER_NOT_SUPPORTED_BY_PAYMENT_SOURCE';
    public const string CODE_PAYER_ACCOUNT_LOCKED_OR_CLOSED = 'PAYER_ACCOUNT_LOCKED_OR_CLOSED';
    public const string CODE_PAYER_ACCOUNT_RESTRICTED = 'PAYER_ACCOUNT_RESTRICTED';
    public const string CODE_DOMESTIC_TRANSACTION_REQUIRED = 'DOMESTIC_TRANSACTION_REQUIRED';
    public const string CODE_MAX_NUMBER_OF_PAYMENT_ATTEMPTS_EXCEEDED = 'MAX_NUMBER_OF_PAYMENT_ATTEMPTS_EXCEEDED';
    public const string CODE_TRANSACTION_LIMIT_EXCEEDED = 'TRANSACTION_LIMIT_EXCEEDED';
    public const string CODE_TRANSACTION_REFUSED = 'TRANSACTION_REFUSED';
    public const string CODE_PAYMENT_DENIED = 'PAYMENT_DENIED';
    public const string CODE_COMPLIANCE_VIOLATION = 'COMPLIANCE_VIOLATION';
    public const string CODE_PAYEE_BLOCKED_TRANSACTION = 'PAYEE_BLOCKED_TRANSACTION';
    public const string CODE_TRANSACTION_BLOCKED_BY_PAYEE = 'TRANSACTION_BLOCKED_BY_PAYEE';

    // issues nobody can fix: an expired order or authorization
    public const string CODE_ORDER_EXPIRED = 'ORDER_EXPIRED';
    public const string CODE_AUTHORIZATION_EXPIRED = 'AUTHORIZATION_EXPIRED';

    // issues the integration's code has to fix
    public const string CODE_AMOUNT_MISMATCH = 'AMOUNT_MISMATCH';
    public const string CODE_ITEM_TOTAL_MISMATCH = 'ITEM_TOTAL_MISMATCH';
    public const string CODE_TAX_TOTAL_MISMATCH = 'TAX_TOTAL_MISMATCH';
    public const string CODE_DISCOUNT_TOTAL_MISMATCH = 'DISCOUNT_TOTAL_MISMATCH';
    public const string CODE_ITEM_TOTAL_REQUIRED = 'ITEM_TOTAL_REQUIRED';
    public const string CODE_TAX_TOTAL_REQUIRED = 'TAX_TOTAL_REQUIRED';
    public const string CODE_APPLE_PAY_AMOUNT_MISMATCH = 'APPLE_PAY_AMOUNT_MISMATCH';
    public const string CODE_DECIMAL_PRECISION = 'DECIMAL_PRECISION';
    public const string CODE_DECIMALS_NOT_SUPPORTED = 'DECIMALS_NOT_SUPPORTED';
    public const string CODE_CANNOT_BE_ZERO_OR_NEGATIVE = 'CANNOT_BE_ZERO_OR_NEGATIVE';
    public const string CODE_CANNOT_BE_NEGATIVE = 'CANNOT_BE_NEGATIVE';
    public const string CODE_MAX_VALUE_EXCEEDED = 'MAX_VALUE_EXCEEDED';
    public const string CODE_MULTI_CURRENCY_ORDER = 'MULTI_CURRENCY_ORDER';
    public const string CODE_SHIPPING_OPTION_NOT_SELECTED = 'SHIPPING_OPTION_NOT_SELECTED';
    public const string CODE_MULTIPLE_SHIPPING_OPTION_SELECTED = 'MULTIPLE_SHIPPING_OPTION_SELECTED';
    public const string CODE_DUPLICATE_SHIPPING_OPTION_ID = 'DUPLICATE_SHIPPING_OPTION_ID';
    public const string CODE_PREFERRED_SHIPPING_OPTION_AMOUNT_MISMATCH = 'PREFERRED_SHIPPING_OPTION_AMOUNT_MISMATCH';
    public const string CODE_MISSING_REQUIRED_PARAMETER = 'MISSING_REQUIRED_PARAMETER';
    public const string CODE_INCOMPATIBLE_PARAMETER_VALUE = 'INCOMPATIBLE_PARAMETER_VALUE';
    public const string CODE_PAYPAL_REQUEST_ID_REQUIRED = 'PAYPAL_REQUEST_ID_REQUIRED';
    public const string CODE_PAYEE_ACCOUNT_INVALID = 'PAYEE_ACCOUNT_INVALID';
    public const string CODE_INVALID_PAYEE_ACCOUNT = 'INVALID_PAYEE_ACCOUNT';

    // unclassified: the order or authorization already moved on, e.g. through a retried capture, so the caller refetches it
    public const string CODE_ORDER_ALREADY_CAPTURED = 'ORDER_ALREADY_CAPTURED';
    public const string CODE_ORDER_ALREADY_AUTHORIZED = 'ORDER_ALREADY_AUTHORIZED';
    public const string CODE_ORDER_ALREADY_COMPLETED = 'ORDER_ALREADY_COMPLETED';
    public const string CODE_ORDER_COMPLETED_OR_VOIDED = 'ORDER_COMPLETED_OR_VOIDED';
    public const string CODE_PREVIOUSLY_CAPTURED = 'PREVIOUSLY_CAPTURED';
    // unclassified: the referenced transaction can't be used any more, the caller can reference another one
    public const string CODE_PREVIOUS_TRANSACTION_REFERENCE_VOIDED = 'PREVIOUS_TRANSACTION_REFERENCE_VOIDED';
    public const string CODE_PREVIOUS_TRANSACTION_REFERENCE_HAS_CHARGEBACK = 'PREVIOUS_TRANSACTION_REFERENCE_HAS_CHARGEBACK';
    public const string CODE_PNREF_EXPIRED = 'PNREF_EXPIRED';
    public const string CODE_PAYPAL_TRANSACTION_ID_EXPIRED = 'PAYPAL_TRANSACTION_ID_EXPIRED';

    /**
     * @param string $errorCode error code or name given.
     * @param string $reason reason or message given.
     * @param ResponseInterface $response original response that come back.
     */
    public function __construct(
        protected readonly string $errorCode,
        protected readonly string $reason,
        protected readonly ResponseInterface $response,
    ) {
        parent::__construct(
            \sprintf('The error "%s" occurred with the following message: %s.', $errorCode, \rtrim($reason, '.'))
        );
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getBody(): string
    {
        return (string) $this->response->getBody();
    }

    /**
     * Read from the `PayPal-Debug-Id` header, the only place an OAuth error names it.
     */
    public function getDebugId(): ?string
    {
        return $this->response->getHeaderLine('PayPal-Debug-Id') ?: null;
    }

    /**
     * Is error code or issue one of the given codes/issues?
     *
     * @param self::CODE_*|string $codes
     */
    public function is(string ...$codes): bool
    {
        return \in_array($this->errorCode, $codes, true);
    }

    /**
     * @return array{status: string, code: string, title: string, detail: string, meta: array<string, mixed>}
     */
    public function jsonSerialize(): array
    {
        return [
            'status' => (string) $this->getStatusCode(),
            'code' => $this->errorCode,
            'title' => $this->reason,
            'detail' => $this->getMessage(),
            'meta' => [],
        ];
    }
}
