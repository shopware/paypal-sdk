<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_common_attributes_mandate')]
class Mandate extends Struct
{
    public const TYPE_ONE_OFF = 'ONE_OFF';
    public const TYPE_RECURRENT = 'RECURRENT';
    public const MANDATE_TYPES = [
        self::TYPE_ONE_OFF,
        self::TYPE_RECURRENT,
    ];

    #[OA\Property(type: 'string', default: self::TYPE_ONE_OFF, enum: self::MANDATE_TYPES)]
    protected string $type = self::TYPE_ONE_OFF;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
