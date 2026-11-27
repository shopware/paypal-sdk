<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_p24', required: ['email'])]
class P24 extends AbstractAPMPaymentSource
{
    #[OA\Property(type: 'string', minLength: 3, maxLength: 254, pattern: Pattern::EMAIL_ADDRESS)]
    protected string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
