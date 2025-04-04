<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Boletobancario;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_boletobancario_tax_info')]
class TaxInfo extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $taxId;

    #[OA\Property(type: 'string')]
    protected string $taxIdType;

    public function getTaxId(): string
    {
        return $this->taxId;
    }

    public function setTaxId(string $taxId): void
    {
        $this->taxId = $taxId;
    }

    public function getTaxIdType(): string
    {
        return $this->taxIdType;
    }

    public function setTaxIdType(string $taxIdType): void
    {
        $this->taxIdType = $taxIdType;
    }
}
