<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Disputes\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_disputes_common_product_details')]
class ProductDetails extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $productReceived;

    #[OA\Property(type: 'string', format: 'date-time', minLength: 20, maxLength: 64, pattern: Pattern::DATE_TIME)]
    protected string $productReceivedTime;

    /** @var string[] */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'), minItems: 1, maxItems: 10)]
    protected ?array $subReasons = null;

    #[OA\Property(type: 'string', format: 'uri')]
    protected string $purchaseUrl;

    #[OA\Property(ref: ReturnDetails::class)]
    protected ReturnDetails $returnDetails;

    public function getProductReceived(): string
    {
        return $this->productReceived;
    }

    public function setProductReceived(string $productReceived): void
    {
        $this->productReceived = $productReceived;
    }

    public function getProductReceivedTime(): string
    {
        return $this->productReceivedTime;
    }

    public function setProductReceivedTime(string $productReceivedTime): void
    {
        $this->productReceivedTime = $productReceivedTime;
    }

    /**
     * @return string[]
     */
    public function getSubReasons(): array
    {
        return $this->subReasons ?? [];
    }

    /**
     * @param string[] $subReasons
     */
    public function setSubReasons(?array $subReasons): void
    {
        $this->subReasons = $subReasons;
    }

    public function getPurchaseUrl(): string
    {
        return $this->purchaseUrl;
    }

    public function setPurchaseUrl(string $purchaseUrl): void
    {
        $this->purchaseUrl = $purchaseUrl;
    }

    public function getReturnDetails(): ReturnDetails
    {
        return $this->returnDetails;
    }

    public function setReturnDetails(ReturnDetails $returnDetails): void
    {
        $this->returnDetails = $returnDetails;
    }
}
