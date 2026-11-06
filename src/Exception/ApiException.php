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
