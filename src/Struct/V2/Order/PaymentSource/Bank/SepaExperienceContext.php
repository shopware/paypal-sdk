<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Bank;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\ExperienceContext;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_bank_sepa_experience_context')]
class SepaExperienceContext extends ExperienceContext
{
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        return \array_filter([
            'locale' => $data['locale'] ?? null,
            'return_url' => $data['return_url'] ?? null,
            'cancel_url' => $data['cancel_url'] ?? null,
        ]);
    }
}
