<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Exception;

use Psr\Http\Message\ResponseInterface;
use Shopware\PayPalSDK\Struct\Error\Error;

class ExceptionFactory
{
    protected static function createOAuthFromResponse(ResponseInterface $response): OAuthApiException
    {
        $content = \json_decode((string) $response->getBody(), true) ?: [];
        $error = (new Error())->assign(\is_array($content) ? $content : []);

        return new OAuthApiException(
            $error->getError() ?? OAuthApiException::CODE_INVALID_CLIENT,
            $error->getErrorDescription() ?? 'Client authentication failed.',
            $response,
        );
    }

    public static function createFromResponse(ResponseInterface $response): ApiException
    {
        if ($response->getStatusCode() === 401) {
            return self::createOAuthFromResponse($response);
        }

        $content = \json_decode((string) $response->getBody(), true) ?: [];
        $error = (new Error())->assign(\is_array($content) ? $content : []);

        if ($error->getError() && $error->getErrorDescription()) {
            return self::createOAuthFromResponse($response);
        }

        if ($error->getName() && $error->getMessage()) {
            if ($error->getName() === ApiException::CODE_RATE_LIMIT_REACHED) {
                return RetryAfterApiException::fromErrorResponse(
                    $error->getName(),
                    $error->getMessage(),
                    $response,
                    $error->getDebugId() ?? '',
                    $error->getLinks(),
                    $error->getDetails(),
                );
            }

            return new ErrorApiException(
                $error->getName(),
                $error->getMessage(),
                $response,
                $error->getDebugId() ?? '',
                $error->getLinks(),
                $error->getDetails(),
            );
        }

        if ($response->getStatusCode() === 501) {
            $code = ApiException::CODE_INTERNAL_SERVER_ERROR;
            $reason = 'An internal server error at PayPal has occurred.';
        } elseif ($response->getStatusCode() === 503) {
            $code = ApiException::CODE_SERVICE_UNAVAILABLE;
            $reason = 'PayPal is unavailable.';
        }

        return new ApiException(
            $code ?? ApiException::CODE_UNKNOWN,
            $reason ?? (string) $response->getBody() ?: $response->getReasonPhrase(),
            $response,
        );
    }
}
