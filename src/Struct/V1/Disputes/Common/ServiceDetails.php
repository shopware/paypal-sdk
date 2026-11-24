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

#[OA\Schema(schema: 'paypal_v1_disputes_common_service_details')]
class ServiceDetails extends Struct
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 2000, pattern: Pattern::MULTILINE_TEXT)]
    protected string $description;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 255, pattern: Pattern::UPPER_ALNUM_UNDERSCORE)]
    protected string $serviceStarted;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 2000, pattern: Pattern::MULTILINE_TEXT)]
    protected string $note;

    /** @var string[] */
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'), minItems: 1, maxItems: 10)]
    protected array $subReasons = [];

    #[OA\Property(type: 'string', format: 'uri')]
    protected string $purchaseUrl;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getServiceStarted(): string
    {
        return $this->serviceStarted;
    }

    public function setServiceStarted(string $serviceStarted): void
    {
        $this->serviceStarted = $serviceStarted;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    /**
     * @return string[]
     */
    public function getSubReasons(): array
    {
        return $this->subReasons;
    }

    /**
     * @param string[] $subReasons
     */
    public function setSubReasons(array $subReasons): void
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
}
