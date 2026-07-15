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
    private readonly ?\DateTimeImmutable $retryAt;

    public function __construct(
        string $errorCode,
        string $reason,
        ResponseInterface $response,
        string $debugId,
        ?LinkCollection $links = null,
        ?DetailCollection $details = null,
        ?string $retryAfter = null,
    ) {
        parent::__construct($errorCode, $reason, $response, $debugId, $links, $details);

        $this->retryAt = self::parseRetryAt($retryAfter);
    }

    public function getRetryAt(): ?\DateTimeImmutable
    {
        return $this->retryAt;
    }

    /**
     * @return int|null Retry delay in milliseconds
     */
    public function getRetryDelay(): ?int
    {
        if ($this->retryAt === null) {
            return null;
        }

        $retryDelay = ($this->retryAt->getTimestamp() - \time()) * 1000;

        return $retryDelay > 0 ? $retryDelay : null;
    }

    private static function parseRetryAt(?string $retryAfter): ?\DateTimeImmutable
    {
        if ($retryAfter === null || ($retryAfter = \trim($retryAfter)) === '') {
            return null;
        }

        if (\ctype_digit($retryAfter)) {
            $seconds = (int) $retryAfter;

            if ($seconds <= 0) {
                return null;
            }

            $retryAt = (new \DateTimeImmutable())->modify(\sprintf('+%d seconds', $seconds));

            return $retryAt ?: null;
        }

        $retryAt = \strtotime($retryAfter);
        if ($retryAt === false || $retryAt <= \time()) {
            return null;
        }

        return (new \DateTimeImmutable())->setTimestamp($retryAt);
    }
}
