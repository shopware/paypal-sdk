<p align="center">
    <img src="https://www.paypalobjects.com/webstatic/de_DE/i/de-pp-logo-200px.png" width="150" alt="PayPal Logo" />
</p>
<p align="center">
    <b>+</b>
</p>
<p align="center">
    <img src="https://www.php.net/images/logos/new-php-logo.png" width="80" alt="PHP Logo" />
</p>

<p align="center">
    <h1 align="center">PayPal SDK for PHP</h1>
</p>

A PayPal SDK for PHP that allows you to integrate PayPal services into your PHP applications.
This SDK provides a simple and easy-to-use interface for working with PayPal's REST APIs, making it easier to accept payments, handle PayPal-related tasks and errors.

> [!WARNING]
> Currently this SDK is an extraction of the implementation within Shopware's [PayPal plugin](https://github.com/shopware/SwagPayPal).
> Only use cases and API responses used within the plugin are covered.

## Quick start
### Installation
You can install the SDK via Composer. Run the following command in your terminal:

```bash
composer require shopware/paypal-sdk
```

Additionally, a PSR client implementation is needed. Install [Guzzle HTTP](https://github.com/guzzle/guzzle) for example:
```bash
composer require guzzlehttp/psr7 php-http/guzzle7-adapter
```

The [php-http/discovery](https://github.com/php-http/discovery) package is used for this library to discover the PSR client implementation automatically.
Have a look at [their README](https://github.com/php-http/discovery?tab=readme-ov-file#usage-as-a-library-user) to set the client implementation manually.
Alternatively provide a gateway with a proper client.

### Usage (Simple)
```php
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\OrderGateway;
use Shopware\PayPalSDK\Struct\V2\Order;

$context = new ApiContext(
    new CredentialsOAuthContext('<clientId>', '<clientSecret>'),
    sandbox: true,
);

$orderGateway = new OrderGateway();

$toCreateOrder = new Order();
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

$context = new ApiContext(
    new CredentialsOAuthContext('<clientId>', '<clientSecret>'),
    sandbox: true,
    merchantId: '<merchantId>', // optional
    headers: ['some-additional-header' => 'value'], // optional
    queryParameters: ['filter' => 'value'], // optional
    thirdParty: false, // optional
)

$client = new Http\Adapter\Guzzle7\Client(new GuzzleHttp\Client([
    // some custom options like adding a logger middleware
]));

$tokenGateway = new TokenGateway($client);
$orderGateway = new OrderGateway($client, $tokenGateway);

$toCreateOrder = new Order();
$toCreateOrder->setPaymentSource(...);
$createdOrder = $orderGateway->createOrder($toCreateOrder, $context);

$anotherOrder = $orderGateway->getOrder('<orderId>', $context);

```
