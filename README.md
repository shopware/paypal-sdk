<p align="center">
    <img src=".github/paypal-php-sdk.svg" width="200" alt="PayPal Logo" />
</p>

<p align="center">
    <h1 align="center">PayPal SDK for PHP</h1>
</p>

A PayPal PHP SDK that allows integration of PayPal services into PHP applications.
This SDK provides a simple and easy-to-use interface for working with PayPal's REST APIs, making it easier to accept payments, handle PayPal-related tasks and errors.

> [!WARNING]
> Currently this SDK is an extraction of the implementation within Shopware's [PayPal plugin](https://github.com/shopware/SwagPayPal).
> Only use cases and API responses used within the plugin are covered.

## Quick start

### Upgrading to 3.0.0

- PHP 8.4 or newer is required. CI tests PHP 8.4 and 8.5.
- Missing optional fields in `Order` and `Order\PurchaseUnit` return `null`. `Order::getId()` retains its required `string` type, and `Order::getPurchaseUnits()` still initializes a collection for building requests. `Order` remains the shared request and response model.
- `Token` and `ClientToken` use `DateTimeImmutable` for expiration dates. To change the expiration explicitly, pass the result of `modify()` to `setExpireDateTime()`.
- Clear the persistent token cache before SDK 3.0 handles any requests. Cache keys are unchanged, but serialized 2.x tokens contain `DateTime` values that are incompatible with the new `DateTimeImmutable` properties and can cause a `TypeError` when loaded. A major version upgrade does not clear the cache automatically. During rolling deployments, prevent 2.x processes from repopulating the token cache used by 3.0.
- `RequestService` accepts separate PSR-17 request, stream and URI factories. Replace `new RequestService($factory)` with `new RequestService($factory, $factory, $factory)` when supplying a combined factory; named arguments are now `requestFactory`, `streamFactory` and `uriFactory`. Automatic discovery remains the default.
- REST errors with HTTP 401 retain their API error code, message, debug ID and details as `ErrorApiException`. OAuth errors are identified by their response format, not HTTP status alone; unstructured 401 responses produce `ApiException`.
- Appending with `$collection[] = $element` now adds and validates the element.

- `RequestService` sends `Prefer: return=representation` by default, including for token requests. Override this with `$context->withHeader('Prefer', 'return=minimal')` when a minimal response is needed. Custom implementations of `RequestServiceInterface` must follow the same behavior.
- `ApiContext::withPreferRepresentation()` has been removed; remove these calls from context construction.
- `TokenGatewayInterface::getToken()` now declares `bool $refresh = false` as its second parameter. Update custom implementations and decorators to accept and forward it, bypassing the token cache when it is `true`.
- `PaymentGateway::voidAuthorization()` now returns `?Authorization`: the updated authorization when PayPal returns a representation, or `null` for an empty response such as `204 No Content` with `return=minimal`. No additional GET is performed. Update overridden methods to use the new return type.
- `Webhook\Event::identifyResourceType()` is now private. Custom event subclasses must override `identifyResource(string $eventType, string $resourceVersion, string $resourceType): ?string` instead, and update calls to the parent method. Resource-type fallback remains available for events not identified by their event type.

`Order::intent` no longer defaults to `CAPTURE`. Set the intent explicitly when creating an order; a response without an intent no longer invents one.

`OrderGateway::patchOrder()` still returns `void`: PayPal documents a `204 No Content` response for this endpoint. Fetch the order separately when its updated state is needed.

### Installation
You can install the SDK via Composer. Run the following command in your terminal:

```bash
composer require shopware/paypal-sdk
```

In addition, a PSR client implementation is needed. We recommend [Guzzle HTTP](https://github.com/guzzle/guzzle) or Symfony's [HttpClient](https://symfony.com/doc/current/http_client.html).                                               
```bash
composer require guzzlehttp/psr7 php-http/guzzle7-adapter
```

The [php-http/discovery](https://github.com/php-http/discovery) package is used for this library to discover the PSR client implementation automatically.
Have a look at [their README](https://github.com/php-http/discovery?tab=readme-ov-file#usage-as-a-library-user) to set the client implementation manually.
Alternatively you can provide a gateway with a concrete client of your choice.

### Usage (Simple)
```php
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\OrderGateway;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnitCollection;

$context = new ApiContext(
    new CredentialsOAuthContext('<clientId>', '<clientSecret>'),
    sandbox: true,
);

$orderGateway = new OrderGateway();

$toCreateOrder = new Order();
$toCreateOrder->setIntent('CAPTURE');
$toCreateOrder->setPurchaseUnits(PurchaseUnitCollection::createFromAssociative([
    ['amount' => ['currency_code' => 'EUR', 'value' => '10.00']],
]));
$toCreateOrder->setPaymentSource(...);
$createdOrder = $orderGateway->createOrder($toCreateOrder, $context);

$anotherOrder = $orderGateway->getOrder('<orderId>', $context);
```

### Usage (Advanced)
```php
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\TokenGateway;
use Shopware\PayPalSDK\Gateway\OrderGateway;
use Shopware\PayPalSDK\Struct\V2\Order;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnitCollection;

$context = new ApiContext(
    new CredentialsOAuthContext('<clientId>', '<clientSecret>'),
    sandbox: true,
    merchantId: '<merchantId>', // optional
    headers: ['some-additional-header' => 'value'], // optional
    queryParameters: ['filter' => 'value'], // optional
    thirdParty: false, // optional
);

$client = new Http\Adapter\Guzzle7\Client(new GuzzleHttp\Client([
    // some custom options like adding a logger middleware
]));

$tokenGateway = new TokenGateway($client);
$orderGateway = new OrderGateway($client, $tokenGateway);

$toCreateOrder = new Order();
$toCreateOrder->setIntent('CAPTURE');
$toCreateOrder->setPurchaseUnits(PurchaseUnitCollection::createFromAssociative([
    ['amount' => ['currency_code' => 'EUR', 'value' => '10.00']],
]));
$toCreateOrder->setPaymentSource(...);
$createdOrder = $orderGateway->createOrder($toCreateOrder, $context);

$anotherOrder = $orderGateway->getOrder('<orderId>', $context);
```
