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
        $error = (new Error())->assign(\is_array($content) ? \array_intersect_key($content, [
            'error' => true,
            'error_description' => true,
        ]) : []);

        return new OAuthApiException(
            $error->getError() ?? OAuthApiException::CODE_INVALID_CLIENT,
            $error->getErrorDescription() ?? $response->getReasonPhrase(),
            $response,
        );
    }

    public static function createFromResponse(ResponseInterface $response): ApiException
    {
        $content = \json_decode((string) $response->getBody(), true) ?: [];
        $content = \is_array($content) ? $content : [];

        if (self::isOAuthError($content)) {
            return self::createOAuthFromResponse($response);
        }

        // Non-OAuth payloads can use these names with unrelated value types.
        unset($content['error'], $content['error_description']);
        $error = (new Error())->assign($content);

        if ($error->getName() && $error->getMessage()) {
            if ($response->getStatusCode() === 429) {
                return new RetryAfterApiException(
                    $error->getName(),
                    $error->getMessage(),
                    $response,
                    $error->getDebugId() ?? '',
                    $error->getLinks(),
                    $error->getDetails(),
                    $response->getHeaderLine('Retry-After') ?: null,
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

    /**
     * RFC 6749 section 5.2 requires an error code; description and URI are optional.
     *
     * @param array<mixed> $content
     */
    private static function isOAuthError(array $content): bool
    {
        return \is_string($content['error'] ?? null)
            && $content['error'] !== ''
            && (!isset($content['error_description']) || \is_string($content['error_description']))
            && (!isset($content['error_uri']) || \is_string($content['error_uri']));
    }
}
