<?php declare(strict_types=1);

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Klarna;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v2_order_payment_source_klarna_authorization_context')]
class AuthorizationContext extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $authorizationExpiry;

    public function getAuthorizationExpiry(): string
    {
        return $this->authorizationExpiry;
    }

    public function setAuthorizationExpiry(string $authorizationExpiry): void
    {
        $this->authorizationExpiry = $authorizationExpiry;
    }
}
