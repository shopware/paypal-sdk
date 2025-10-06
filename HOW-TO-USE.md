# How to use
First of all, have a look at the quick start guide of the [README](./README.md#quick-start).

The SDK provides so-called `Gateways` to abstract the communication with PayPal.
To know how to communicate with the PayPal API, a `ApiContext` needs to be created.
To know how to authenticate against the PayPal API, the `ApiContext` carries a `OAuthContextInterface`.

```php
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\AuthorizationCodeOAuthContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\CustomerGateway;

class OnboardingService
{
    public function __construct(
        protected readonly CustomerGateway $customerGateway = new CustomerGateway();
    ) {
    }

    public function onboard(
        string $authCode,
        string $sharedId,
        string $nonce,
        bool $sandbox,
    ): MerchantIntegrations {
        $context = new ApiContext(
            new AuthorizationCodeOAuthContext($authCode, $sharedId, $nonce);
            $sandbox,
        );

        $credentials = $this->customerGateway->getCredentials($context);

        // ... save credentials

        $context = $context->withOAuthContext(new CredentialsOAuthContext(
            $credentials->getClientId(),
            $credentials->getClientSecret(),
            $credentials->getPayerId(),
        )),

        return $this->customerGateway->getMerchantIntegrations(
            '<partner-id>',
            $credentials->getPayerId(),
            $context
        );
    }
}
```

# How to test (PHPUnit)

```php
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Credentials;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;
use PHPUnit\Framework\TestCase;

class OnboardingServiceTest extends TestCase
{
    protected TestClient $client;
    protected TestGateways $gateways;
    protected OnboardingService $onboardingService;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);
        $this->onboardingService = new OnboardingService($this->gateways->customerGateway());
    }

    public function testOnboard(): void
    {
        $credentials = new Credentials();
        $credentials->setClientId('<client-id>');
        $credentials->setClientSecret('<client-secret>');
        $credentials->setPayerId('<payer-id>');
        $this->client->addResponse(new Response(200, [], \json_encode($credentials)));

        $merchantIntegrations = new MerchantIntegrations();
        $merchantIntegrations->setMerchantId('<merchant-id>');
        $this->client->addResponse(new Response(200, [], \json_encode($merchantIntegrations)));

        $newMI = $this->onboardingService->onboard(
            '<auth-code>',
            '<shared-id>',
            '<nonce>',
            false,
        );

        static::assertEquals($merchantIntegrations, $newMI);
    }
}
```

```php
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;
use PHPUnit\Framework\TestCase;

class OnboardingServiceTest extends TestCase
{
    protected TestClient $client;
    protected TestGateways $gateways;
    protected OnboardingService $onboardingService;

    protected function setUp(): void
    {
        $this->client = new TestClient(handler: $this->credentialsHandler(...));
        $this->gateways = new TestGateways($this->client);
        $this->onboardingService = new OnboardingService(
            $this->gateways->customerGateway(),
        );
    }

    public function testOnboard(): void
    {
        $this->onboardingService->onboard(
            '<auth-code>',
            '<shared-id>',
            '<nonce>',
            false,
        );
    }    

    public function testOnboardInvalid(): void
    {
        $this->onboardingService->onboard(
            '<invalid-auth-code>',
            '<invalid-shared-id>',
            '<invalid-nonce>',
            false,
        );
    }    
}
```

```php
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations;
use Shopware\PayPalSDK\Struct\V1\MerchantIntegrations\Credentials;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;
use PHPUnit\Framework\TestCase;

class OnboardingServiceTest extends TestCase
{
    protected TestClient $client;
    protected TestGateways $gateways;
    protected OnboardingService $onboardingService;

    protected function setUp(): void
    {
        $this->client = new TestClient(handler: $this->gatewayHandler(...));
        $this->gateways = new TestGateways($this->client);
        $this->onboardingService = new OnboardingService($this->gateways->customerGateway());
    }

    public function testOnboard(): void
    {
        $credentials = new Credentials();
        $credentials->setClientId('<client-id>');
        $credentials->setClientSecret('<client-secret>');
        $credentials->setPayerId('<payer-id>');
        $this->client->addResponse(new Response(200, [], \json_encode($credentials)));

        $merchantIntegrations = new MerchantIntegrations();
        $merchantIntegrations->setMerchantId('<merchant-id>');
        $this->client->addResponse(new Response(200, [], \json_encode($merchantIntegrations)));

        $newMI = $this->onboardingService->onboard(
            '<auth-code>',
            '<shared-id>',
            '<nonce>',
            false,
        );

        static::assertEquals($merchantIntegrations, $newMI);
    }

    public function gatewayHandler(TestRequestContext $context): Response
    {
        $oauthContext = $context->getContext()->getOAuthContext();

        if ($context->getGatewayMethod() === 'getCredentials') {
            $credentials = new Credentials();
            $credentials->setClientId('<client-id>');
            $credentials->setClientSecret('<client-secret>');
            $credentials->setPayerId('<payer-id>');
            return new Response(200, [], \json_encode($credentials));
        } else if ($context->getGatewayMethod() === 'getCredentials') {
            $merchantIntegrations = new MerchantIntegrations();
            $merchantIntegrations->setMerchantId('<merchant-id>');
            return new Response(200, [], \json_encode($merchantIntegrations));
        }

        return new Response(400, [], \json_encode([
            'name' => '<error-code>',
            'description' => '<error-description>',
        ]));
    }
}
```