<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\AppSwitchContext\MobileWebContext;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\AppSwitchContext\NativeAppContext;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_common_app_switch_context')]
class AppSwitchContext extends Struct
{
    #[OA\Property(ref: NativeAppContext::class, nullable: true)]
    protected ?NativeAppContext $nativeApp = null;

    #[OA\Property(ref: MobileWebContext::class, nullable: true)]
    protected ?MobileWebContext $mobileWeb = null;

    public function getNativeApp(): ?NativeAppContext
    {
        return $this->nativeApp;
    }

    public function setNativeApp(?NativeAppContext $nativeApp): void
    {
        $this->nativeApp = $nativeApp;
    }

    public function getMobileWeb(): ?MobileWebContext
    {
        return $this->mobileWeb;
    }

    public function setMobileWeb(?MobileWebContext $mobileWeb): void
    {
        $this->mobileWeb = $mobileWeb;
    }
}
