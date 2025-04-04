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

class ErrorApiException extends ApiException
{
    public readonly LinkCollection $links;

    public readonly DetailCollection $details;

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
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        $data['meta']['details'] = $this->details->jsonSerialize();
        $data['meta']['links'] = $this->links->jsonSerialize();

        return $data;
    }
}
