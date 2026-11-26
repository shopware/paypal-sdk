<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tools\OpenApi;

use Shopware\PayPalSDK\Struct\V1\Disputes;
use Shopware\PayPalSDK\Struct\V1\Disputes\Item as DisputeItem;
use Shopware\PayPalSDK\Struct\V1\Patch as V1Patch;
use Shopware\PayPalSDK\Struct\V1\Reporting\Balances;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;
use Shopware\PayPalSDK\Struct\V1\Webhook;
use Shopware\PayPalSDK\Struct\V1\Webhook\Event;
use Shopware\PayPalSDK\Struct\V1\WebhookList;
use Shopware\PayPalSDK\Struct\V2\ConfirmOrder;
use Shopware\PayPalSDK\Struct\V2\EligibleMethodsData;
use Shopware\PayPalSDK\Struct\V2\FindEligibleMethods;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Authorization;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Capture;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payments\Refund;
use Shopware\PayPalSDK\Struct\V2\Order\Tracker;
use Shopware\PayPalSDK\Struct\V2\Patch as V2Patch;
use Shopware\PayPalSDK\Struct\V2\Referral;
use Shopware\PayPalSDK\Struct\V3\PaymentToken;

/**
 * Where the structs meet the spec. Nested structs are paired with schemas by
 * walking from the {@see self::operations()}.
 */
final class Roots
{
    /**
     * Structs no operation reaches, by class or namespace below
     * `Shopware\PayPalSDK\Struct`, with the reason.
     */
    public const array UNCOVERED = [
        'AgenticCommerce' => 'no published spec',
        'ArrayStruct' => 'untyped container',
        'Error' => 'error bodies; only 2xx responses are paired',
        'V1\Capture' => 'v1/payments has no published spec',
        'V1\Common\Amount' => 'v1/payments has no published spec',
        'V1\Common\Details' => 'v1/payments has no published spec',
        'V1\Common\Value' => 'v1/payments has no published spec',
        'V1\DoVoid' => 'v1/payments has no published spec',
        'V1\Payment' => 'v1/payments has no published spec',
        'V1\Refund' => 'v1/payments has no published spec',
        'V1\ClientToken' => 'OAuth has no published spec',
        'V1\Token' => 'OAuth has no published spec',
        'V1\MerchantIntegrations' => 'merchant-integrations has no published spec',
        'V1\MerchantTracking' => 'merchant-integrations has no published spec',
        'V1\WalletDomain' => 'wallet-domains has no published spec',
        'V1\WalletDomains' => 'wallet-domains has no published spec',
        'V3\Common' => 'managed-accounts has no published spec',
        'V3\ManagedAccount' => 'managed-accounts has no published spec',
        'V3\ManagedAccounts' => 'managed-accounts has no published spec',
        'V1\Plan' => 'no gateway calls the billing plans API',
        'V1\Product' => 'no gateway calls the catalog products API',
        'V1\Subscription' => 'no gateway calls the billing subscriptions API',
        'V1\Shipping' => 'no gateway calls the v1 shipment tracking API',
        'V1\Reporting\BalanceSearch' => 'query parameters, not a body',
        'V1\Reporting\TransactionSearch' => 'query parameters, not a body',
        'V1\Webhook\Events' => 'Event::$resource is typed by event type at runtime; the spec leaves it untyped',
        'V1\Webhook\Resource' => 'Event::$resource is typed by event type at runtime; the spec leaves it untyped',
        'V2\OrderShippingCallback' => 'sent by PayPal to the merchant (order-update-callback), not called by the SDK',
        'V2\Order\PaymentSource\Bank\SepaExperienceContext' => 'not referenced by any struct',
        'V2\Order\PurchaseUnit\Payments\Authorization\SellerProtection' => 'not referenced by any struct',
    ];

    /**
     * @return list<Operation>
     */
    public static function operations(): array
    {
        $orders = 'checkout_orders_v2';
        $payments = 'payments_payment_v2';
        $webhooks = 'notifications_webhooks_v1';

        return [
            new Operation($orders, 'POST', '/v2/checkout/orders', sends: Order::class, receives: Order::class),
            new Operation($orders, 'GET', '/v2/checkout/orders/{id}', receives: Order::class),
            new Operation($orders, 'PATCH', '/v2/checkout/orders/{id}', sends: V2Patch::class),
            new Operation($orders, 'POST', '/v2/checkout/orders/{id}/confirm-payment-source', sends: ConfirmOrder::class, receives: Order::class),
            new Operation($orders, 'POST', '/v2/checkout/orders/{id}/authorize', receives: Order::class),
            new Operation($orders, 'POST', '/v2/checkout/orders/{id}/capture', receives: Order::class),
            new Operation($orders, 'POST', '/v2/checkout/orders/{id}/track', sends: Tracker::class, receives: Order::class),
            new Operation($orders, 'PATCH', '/v2/checkout/orders/{id}/trackers/{tracker_id}', sends: V2Patch::class),

            new Operation($payments, 'GET', '/v2/payments/authorizations/{id}', receives: Authorization::class),
            new Operation($payments, 'POST', '/v2/payments/authorizations/{id}/capture', sends: Capture::class, receives: Capture::class),
            new Operation($payments, 'POST', '/v2/payments/authorizations/{id}/void', receives: Authorization::class),
            new Operation($payments, 'GET', '/v2/payments/captures/{id}', receives: Capture::class),
            new Operation($payments, 'POST', '/v2/payments/captures/{id}/refund', sends: Refund::class, receives: Refund::class),
            new Operation($payments, 'GET', '/v2/payments/refunds/{id}', receives: Refund::class),
            new Operation($payments, 'POST', '/v2/payments/find-eligible-methods', sends: FindEligibleMethods::class, receives: EligibleMethodsData::class),

            new Operation('customer_partner_referrals_v2', 'POST', '/v2/customer/partner-referrals', sends: Referral::class, receives: Referral::class),
            new Operation('customer_disputes_v1', 'GET', '/v1/customer/disputes', receives: Disputes::class),
            new Operation('customer_disputes_v1', 'GET', '/v1/customer/disputes/{id}', receives: DisputeItem::class),

            new Operation($webhooks, 'POST', '/v1/notifications/webhooks', sends: Webhook::class, receives: Webhook::class),
            new Operation($webhooks, 'GET', '/v1/notifications/webhooks', receives: WebhookList::class),
            new Operation($webhooks, 'GET', '/v1/notifications/webhooks/{id}', receives: Webhook::class),
            new Operation($webhooks, 'PATCH', '/v1/notifications/webhooks/{id}', sends: V1Patch::class, receives: Webhook::class),
            new Operation($webhooks, 'GET', '/v1/notifications/webhooks-events/{id}', receives: Event::class),

            new Operation('reporting_transactions_v1', 'GET', '/v1/reporting/transactions', receives: Transactions::class),
            new Operation('reporting_transactions_v1', 'GET', '/v1/reporting/balances', receives: Balances::class),

            new Operation('vault_payment_tokens_v3', 'GET', '/v3/vault/payment-tokens/{id}', receives: PaymentToken::class),
        ];
    }
}
