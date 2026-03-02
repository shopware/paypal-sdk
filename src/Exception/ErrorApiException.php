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
 * Exception used for (validation) errors.
 */
class ErrorApiException extends ApiException
{
    protected readonly LinkCollection $links;

    protected readonly DetailCollection $details;

    /**
     * @param string $debugId debugId given.
     * @param LinkCollection|null $links Any HATEOAS links given.
     * @param DetailCollection|null $details Any details given about the error. Typically, fields that have an error.
     */
    public function __construct(
        string $errorCode,
        string $reason,
        ResponseInterface $response,
        public readonly string $debugId,
        ?LinkCollection $links = null,
        ?DetailCollection $details = null,
    ) {
        $this->links = $links ?? new LinkCollection();
        $this->details = $details ?? new DetailCollection();

        parent::__construct($errorCode, $reason, $response);

        if ($detailMessage = (string) $this->details) {
            $this->message = $this->getMessage() . ' | ' . $detailMessage;
        }
    }

    public function getDetails(): DetailCollection
    {
        return $this->details;
    }

    public function getLinks(): LinkCollection
    {
        return $this->links;
    }

    public function is(string ...$codes): bool
    {
        $issues = $this->getDetails()->getIssues();

        return parent::is(...$codes) || \array_diff($issues, $codes) !== $issues;
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $data['meta']['details'] = $this->details->jsonSerialize();
        $data['meta']['links'] = $this->links->jsonSerialize();

        return $data;
    }
}
