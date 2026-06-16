<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\AppSwitchContext;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_common_app_switch_context_native_app_context')]
class NativeAppContext extends Struct
{
    public const OS_TYPE_ANDROID = 'ANDROID';
    public const OS_TYPE_IOS = 'IOS';
    public const OS_TYPE_OTHER = 'OTHER';
    public const OS_TYPES = [
        self::OS_TYPE_ANDROID,
        self::OS_TYPE_IOS,
        self::OS_TYPE_OTHER,
    ];

    #[OA\Property(type: 'string', enum: self::OS_TYPES)]
    protected string $osType;

    #[OA\Property(type: 'string', maxLength: 64, minLength: 1)]
    protected string $osVersion;

    public function getOsType(): string
    {
        return $this->osType;
    }

    public function setOsType(string $osType): void
    {
        $this->osType = $osType;
    }

    public function getOsVersion(): string
    {
        return $this->osVersion;
    }

    public function setOsVersion(string $osVersion): void
    {
        $this->osVersion = $osVersion;
    }
}
