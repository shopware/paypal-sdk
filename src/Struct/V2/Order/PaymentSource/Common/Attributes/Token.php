<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_common_attributes_token')]
class Token extends Struct
{
    public const TYPE_BANK_REFERENCE_TOKEN = 'BANK_REFERENCE_TOKEN';

    #[OA\Property(type: 'string')]
    protected string $id;

    #[OA\Property(type: 'string', default: self::TYPE_BANK_REFERENCE_TOKEN)]
    protected string $type = self::TYPE_BANK_REFERENCE_TOKEN;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
