<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_payments_capture_processor_response')]
class ProcessorResponse extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $avsCode = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $cvvCode = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $responseCode = null;

    public function getAvsCode(): ?string
    {
        return $this->avsCode;
    }

    public function setAvsCode(?string $avsCode): void
    {
        $this->avsCode = $avsCode;
    }

    public function getCvvCode(): ?string
    {
        return $this->cvvCode;
    }

    public function setCvvCode(?string $cvvCode): void
    {
        $this->cvvCode = $cvvCode;
    }

    public function getResponseCode(): ?string
    {
        return $this->responseCode;
    }

    public function setResponseCode(?string $responseCode): void
    {
        $this->responseCode = $responseCode;
    }
}
