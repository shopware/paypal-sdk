<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Reporting\Balances;
use Shopware\PayPalSDK\Struct\V1\Reporting\BalanceSearch;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;
use Shopware\PayPalSDK\Struct\V1\Reporting\TransactionSearch;

class ReportingGateway extends AbstractGateway
{
    public const GATEWAY_URL = '/v1/reporting';

    public function listTransactions(ApiContextInterface $context, TransactionSearch $search): Transactions
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/transactions',
            null,
            Transactions::class,
            $this->withQueryParameters($context, $search),
        );
    }

    public function listBalances(ApiContextInterface $context, ?BalanceSearch $search = null): Balances
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/balances',
            null,
            Balances::class,
            $this->withQueryParameters($context, $search),
        );
    }

    private function withQueryParameters(ApiContextInterface $context, ?Struct $search): ApiContextInterface
    {
        if ($search === null) {
            return $context;
        }

        foreach ($search->jsonSerialize() as $name => $value) {
            if ($value === null) {
                continue;
            }

            $value = match (true) {
                \is_bool($value) => $value ? 'true' : 'false',
                \is_int($value), \is_float($value), \is_string($value) => (string) $value,
                default => null,
            };

            if ($value === null) {
                continue;
            }

            $context = $context->withQueryParameter($name, $value);
        }

        return $context;
    }
}
