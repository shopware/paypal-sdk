<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Gateway;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Context\ApiContext;
use Shopware\PayPalSDK\Context\CredentialsOAuthContext;
use Shopware\PayPalSDK\Gateway\ReportingGateway;
use Shopware\PayPalSDK\Struct\V1\Reporting\Balance;
use Shopware\PayPalSDK\Struct\V1\Reporting\BalanceSearch;
use Shopware\PayPalSDK\Struct\V1\Reporting\Money;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions\CheckoutOption;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions\ItemDetail;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions\PayerInfo;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions\TaxAmount;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions\TransactionInfo;
use Shopware\PayPalSDK\Struct\V1\Reporting\TransactionSearch;
use Shopware\PayPalSDK\Test\Gateway\TestGateways;
use Shopware\PayPalSDK\Test\Request\TestClient;

/**
 * @internal
 */
#[CoversClass(ReportingGateway::class)]
class ReportingGatewayTest extends TestCase
{
    protected TestClient $client;

    protected TestGateways $gateways;

    protected function setUp(): void
    {
        $this->client = new TestClient();
        $this->gateways = new TestGateways($this->client);
    }

    public function testListTransactions(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $search = (new TransactionSearch())->assign([
            'transaction_status' => 'S',
            'fields' => 'all',
            'page_size' => 20,
            'page' => 2,
        ]);
        $search->setStartDate(new \DateTimeImmutable('2026-01-01T00:00:00Z'));
        $search->setEndDate(new \DateTimeImmutable('2026-01-31T23:59:59+02:00'));
        $body = [
            'transaction_details' => [[
                'transaction_info' => [
                    'paypal_account_id' => 'PAYPAL-ACCOUNT-ID',
                    'transaction_id' => 'TRANSACTION-ID',
                    'paypal_reference_id' => 'REFERENCE-ID',
                    'paypal_reference_id_type' => 'TXN',
                    'transaction_event_code' => 'T0006',
                    'transaction_initiation_date' => '2026-01-02T12:00:00Z',
                    'transaction_updated_date' => '2026-01-02T12:01:00Z',
                    'transaction_amount' => ['currency_code' => 'EUR', 'value' => '10.00'],
                    'fee_amount' => ['currency_code' => 'EUR', 'value' => '0.35'],
                    'ending_balance' => ['currency_code' => 'EUR', 'value' => '99.65'],
                    'transaction_status' => 'S',
                    'invoice_id' => 'INVOICE-ID',
                    'payment_method_type' => 'CREDIT_CARD',
                    'instrument_type' => 'VISA',
                ],
                'payer_info' => [
                    'account_id' => 'PAYER-ID',
                    'email_address' => 'payer@example.com',
                    'phone_number' => [
                        'country_code' => '49',
                        'national_number' => '123456789',
                        'extension_number' => '99',
                    ],
                    'payer_name' => [
                        'given_name' => 'Max',
                        'surname' => 'Mustermann',
                        'full_name' => 'Max Mustermann',
                    ],
                    'country_code' => 'DE',
                    'address' => [
                        'line1' => 'Main Street 1',
                        'city' => 'Berlin',
                        'country_code' => 'DE',
                        'postal_code' => '10115',
                    ],
                ],
                'shipping_info' => [
                    'name' => 'Max Mustermann',
                    'method' => 'DHL',
                    'address' => [
                        'line1' => 'Shipping Street 2',
                        'city' => 'Berlin',
                        'country_code' => 'DE',
                        'postal_code' => '10115',
                    ],
                ],
                'cart_info' => [
                    'item_details' => [[
                        'item_code' => 'SKU-1',
                        'item_name' => 'Example item',
                        'item_quantity' => '1',
                        'item_unit_price' => ['currency_code' => 'EUR', 'value' => '10.00'],
                        'tax_amounts' => [[
                            'tax_amount' => ['currency_code' => 'EUR', 'value' => '1.90'],
                        ]],
                        'checkout_options' => [[
                            'checkout_option_name' => 'color',
                            'checkout_option_value' => 'blue',
                        ]],
                    ]],
                    'tax_inclusive' => true,
                    'paypal_invoice_id' => 'PAYPAL-INVOICE-ID',
                ],
                'store_info' => [
                    'store_id' => 'STORE-ID',
                    'terminal_id' => 'TERMINAL-ID',
                ],
                'auction_info' => [
                    'auction_site' => 'auction.example',
                    'auction_buyer_id' => 'BUYER-ID',
                ],
                'incentive_info' => [
                    'incentive_details' => [[
                        'incentive_type' => 'coupon',
                        'incentive_amount' => ['currency_code' => 'EUR', 'value' => '2.00'],
                    ]],
                ],
            ]],
            'account_number' => 'ACCOUNT123',
            'start_date' => '2026-01-01T00:00:00Z',
            'end_date' => '2026-01-31T23:59:59Z',
            'last_refreshed_datetime' => '2026-01-31T23:59:59Z',
            'page' => 2,
            'total_items' => 1,
            'total_pages' => 1,
            'links' => [['href' => 'https://api-m.sandbox.paypal.com/v1/reporting/transactions', 'rel' => 'self']],
        ];

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->reportingGateway()->listTransactions($search, $context);

        static::assertSame('ACCOUNT123', $response->getAccountNumber());
        static::assertNotNull($response->getTransactionDetails());
        static::assertCount(1, $response->getTransactionDetails());
        $transactionDetail = $response->getTransactionDetails()->first();
        static::assertNotNull($transactionDetail);
        $transactionInfo = $transactionDetail->getTransactionInfo();
        static::assertInstanceOf(TransactionInfo::class, $transactionInfo);
        static::assertSame('TRANSACTION-ID', $transactionInfo->getTransactionId());
        $transactionAmount = $transactionInfo->getTransactionAmount();
        static::assertInstanceOf(Money::class, $transactionAmount);
        static::assertSame('10.00', $transactionAmount->getValue());
        $payerInfo = $transactionDetail->getPayerInfo();
        static::assertInstanceOf(PayerInfo::class, $payerInfo);
        static::assertSame('99', $payerInfo->getPhoneNumber()?->getExtensionNumber());
        static::assertSame('Main Street 1', $payerInfo->getAddress()?->getLine1());
        static::assertSame('DHL', $transactionDetail->getShippingInfo()?->getMethod());
        $itemDetail = $transactionDetail->getCartInfo()?->getItemDetails()->first();
        static::assertInstanceOf(ItemDetail::class, $itemDetail);
        static::assertSame('Example item', $itemDetail->getItemName());
        $taxAmount = $itemDetail->getTaxAmounts()->first();
        static::assertInstanceOf(TaxAmount::class, $taxAmount);
        static::assertSame('1.90', $taxAmount->getTaxAmount()?->getValue());
        $checkoutOption = $itemDetail->getCheckoutOptions()->first();
        static::assertInstanceOf(CheckoutOption::class, $checkoutOption);
        static::assertSame('blue', $checkoutOption->getCheckoutOptionValue());
        static::assertSame('STORE-ID', $transactionDetail->getStoreInfo()?->getStoreId());
        static::assertSame('BUYER-ID', $transactionDetail->getAuctionInfo()?->getAuctionBuyerId());
        static::assertSame('coupon', $transactionDetail->getIncentiveInfo()?->getIncentiveDetails()->first()?->getIncentiveType());

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/reporting/transactions', $last->getRequest()->getUri()->getPath());
        \parse_str($last->getRequest()->getUri()->getQuery(), $query);
        $expectedQuery = [
            'start_date' => '2026-01-01T00:00:00+00:00',
            'end_date' => '2026-01-31T23:59:59+02:00',
            'transaction_status' => 'S',
            'fields' => 'all',
            'page_size' => '20',
            'page' => '2',
        ];
        \ksort($expectedQuery);
        \ksort($query);
        static::assertSame($expectedQuery, $query);
    }

    public function testListTransactionsRequiresSearchDates(): void
    {
        $method = new \ReflectionMethod(ReportingGateway::class, 'listTransactions');
        $searchParameter = $method->getParameters()[0] ?? null;
        static::assertNotNull($searchParameter);
        static::assertFalse($searchParameter->allowsNull());

        $search = new \ReflectionClass(TransactionSearch::class);

        foreach (['startDate', 'endDate'] as $propertyName) {
            $property = $search->getProperty($propertyName);
            $type = $property->getType();
            static::assertInstanceOf(\ReflectionNamedType::class, $type);
            static::assertSame(\DateTimeInterface::class, $type->getName());
            static::assertFalse($type->allowsNull());
        }
    }

    public function testListBalances(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $search = (new BalanceSearch())->assign(['currency_code' => 'EUR']);
        $search->setAsOfTime(new \DateTimeImmutable('2026-01-31T23:59:59Z'));
        $body = [
            'balances' => [[
                'currency' => 'EUR',
                'total_balance' => ['currency_code' => 'EUR', 'value' => '100.00'],
                'primary' => true,
                'available_balance' => ['currency_code' => 'EUR', 'value' => '95.00'],
                'withheld_balance' => ['currency_code' => 'EUR', 'value' => '5.00'],
            ]],
            'account_id' => 'ACCOUNT-ID',
            'as_of_time' => '2026-01-31T23:59:59Z',
            'last_refresh_time' => '2026-01-31T22:00:00Z',
        ];

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->reportingGateway()->listBalances($search, $context);

        static::assertSame('ACCOUNT-ID', $response->getAccountId());
        static::assertSame('2026-01-31T23:59:59Z', $response->getAsOfTime());
        $balance = $response->getBalances()->first();
        static::assertInstanceOf(Balance::class, $balance);
        static::assertSame('EUR', $balance->getCurrency());
        static::assertTrue($balance->isPrimary());
        static::assertSame('100.00', $balance->getTotalBalance()->getValue());
        static::assertSame('95.00', $balance->getAvailableBalance()->getValue());
        static::assertSame('5.00', $balance->getWithheldBalance()->getValue());

        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('GET', $last->getRequest()->getMethod());
        static::assertSame('/v1/reporting/balances', $last->getRequest()->getUri()->getPath());
        \parse_str($last->getRequest()->getUri()->getQuery(), $query);
        static::assertSame([
            'as_of_time' => '2026-01-31T23:59:59+00:00',
            'currency_code' => 'EUR',
        ], $query);
    }

    public function testListBalancesWithoutSearch(): void
    {
        $context = new ApiContext(new CredentialsOAuthContext('client-id', 'client-secret'), true, 'merchant-id');
        $body = [
            'balances' => [],
            'account_id' => 'ACCOUNT-ID',
            'as_of_time' => '2026-01-31T23:59:59Z',
            'last_refresh_time' => '2026-01-31T22:00:00Z',
        ];

        $this->gateways->setCachedToken($context);
        $this->client->addResponse(new Response(body: \json_encode($body, \JSON_THROW_ON_ERROR)));

        $response = $this->gateways->reportingGateway()->listBalances(null, $context);

        static::assertSame('ACCOUNT-ID', $response->getAccountId());
        $last = $this->client->getLast();
        static::assertNotNull($last);
        static::assertSame('/v1/reporting/balances', $last->getRequest()->getUri()->getPath());
        static::assertSame('', $last->getRequest()->getUri()->getQuery());
    }
}
