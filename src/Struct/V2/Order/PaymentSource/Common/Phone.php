<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Common\PhoneNumber;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_common_phone')]
class Phone extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $phoneType;

    #[OA\Property(ref: PhoneNumber::class)]
    protected PhoneNumber $phoneNumber;

    public function getPhoneType(): string
    {
        return $this->phoneType;
    }

    public function setPhoneType(string $phoneType): void
    {
        $this->phoneType = $phoneType;
    }

    public function getPhoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
}
