<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V3;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v3_managed_accounts')]
class ManagedAccounts extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: ManagedAccount::class))]
    protected ManagedAccountCollection $managedAccounts;

    public function getManagedAccounts(): ManagedAccountCollection
    {
        return $this->managedAccounts;
    }

    public function setManagedAccounts(ManagedAccountCollection $managedAccounts): void
    {
        $this->managedAccounts = $managedAccounts;
    }
}
