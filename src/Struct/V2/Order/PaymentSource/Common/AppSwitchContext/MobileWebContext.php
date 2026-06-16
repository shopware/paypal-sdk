<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\AppSwitchContext;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_common_app_switch_context_mobile_web_context')]
class MobileWebContext extends Struct
{
    public const RETURN_FLOW_AUTO = 'AUTO';
    public const RETURN_FLOW_MANUAL = 'MANUAL';
    public const RETURN_FLOWS = [
        self::RETURN_FLOW_AUTO,
        self::RETURN_FLOW_MANUAL,
    ];

    #[OA\Property(type: 'string', default: self::RETURN_FLOW_AUTO, enum: self::RETURN_FLOWS)]
    protected string $returnFlow = self::RETURN_FLOW_AUTO;

    #[OA\Property(type: 'string', maxLength: 512, minLength: 1)]
    protected string $buyerUserAgent;

    public function getReturnFlow(): string
    {
        return $this->returnFlow;
    }

    public function setReturnFlow(string $returnFlow): void
    {
        $this->returnFlow = $returnFlow;
    }

    public function getBuyerUserAgent(): string
    {
        return $this->buyerUserAgent;
    }

    public function setBuyerUserAgent(string $buyerUserAgent): void
    {
        $this->buyerUserAgent = $buyerUserAgent;
    }
}
