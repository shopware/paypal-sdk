<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_store_info')]
class StoreInfo extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $storeId = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $terminalId = null;

    public function getStoreId(): ?string
    {
        return $this->storeId;
    }

    public function setStoreId(?string $storeId): void
    {
        $this->storeId = $storeId;
    }

    public function getTerminalId(): ?string
    {
        return $this->terminalId;
    }

    public function setTerminalId(?string $terminalId): void
    {
        $this->terminalId = $terminalId;
    }
}
