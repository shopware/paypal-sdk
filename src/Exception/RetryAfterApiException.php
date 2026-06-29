<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Exception;

use Psr\Http\Message\ResponseInterface;
use Shopware\PayPalSDK\Struct\Error\DetailCollection;
use Shopware\PayPalSDK\Struct\V1\Common\LinkCollection;

/**
 * Exception used for recoverable API errors that provide a retry delay.
 */
class RetryAfterApiException extends ErrorApiException
{
    private const MILLISECONDS_PER_SECOND = 1000;

    public function __construct(
        string $errorCode,
        string $reason,
        ResponseInterface $response,
        string $debugId,
        ?LinkCollection $links = null,
        ?DetailCollection $details = null,
        private readonly ?int $retryDelay = null,
    ) {
        parent::__construct($errorCode, $reason, $response, $debugId, $links, $details);
    }

    /**
     * @return int|null Retry delay in milliseconds
     */
    public function getRetryDelay(): ?int
    {
        return $this->retryDelay;
    }

    public static function fromErrorResponse(
        string $errorCode,
        string $reason,
        ResponseInterface $response,
        string $debugId,
        ?LinkCollection $links = null,
        ?DetailCollection $details = null,
    ): self {
        return new self(
            $errorCode,
            $reason,
            $response,
            $debugId,
            $links,
            $details,
            self::parseRetryDelay($response->getHeaderLine('Retry-After') ?: null),
        );
    }

    private static function parseRetryDelay(?string $retryAfter): ?int
    {
        if ($retryAfter === null || ($retryAfter = \trim($retryAfter)) === '') {
            return null;
        }

        if (\ctype_digit($retryAfter)) {
            $retryDelay = (int) $retryAfter * self::MILLISECONDS_PER_SECOND;

            return $retryDelay > 0 ? $retryDelay : null;
        }

        $retryAt = \strtotime($retryAfter);
        if ($retryAt === false) {
            return null;
        }

        $retryDelay = ($retryAt - \time()) * self::MILLISECONDS_PER_SECOND;

        return $retryDelay > 0 ? $retryDelay : null;
    }
}
