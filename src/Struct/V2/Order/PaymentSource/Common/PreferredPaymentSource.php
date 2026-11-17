<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_common_preferred_payment_source')]
class PreferredPaymentSource extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $vaultId;

    public function getVaultId(): string
    {
        return $this->vaultId;
    }

    public function setVaultId(string $vaultId): void
    {
        $this->vaultId = $vaultId;
    }
}
