<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Struct\V1\Reporting\Balances;
use Shopware\PayPalSDK\Struct\V1\Reporting\BalanceSearch;
use Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;
use Shopware\PayPalSDK\Struct\V1\Reporting\TransactionSearch;
use Shopware\PayPalSDK\Util\QueryParameterFormatter;

class ReportingGateway extends AbstractGateway
{
    public const GATEWAY_URL = '/v1/reporting';

    public function listTransactions(TransactionSearch $search, ApiContextInterface $context): Transactions
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/transactions',
            null,
            Transactions::class,
            QueryParameterFormatter::withStructQueryParameters($context, $search),
        );
    }

    public function listBalances(?BalanceSearch $search, ApiContextInterface $context): Balances
    {
        return $this->request(
            'GET',
            self::GATEWAY_URL . '/balances',
            null,
            Balances::class,
            $search ? QueryParameterFormatter::withStructQueryParameters($context, $search) : $context,
        );
    }
}
