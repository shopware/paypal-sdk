<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\WalletDomain\WalletDomainCollection;

#[OA\Schema(schema: 'paypal_v1_wallet_domains')]
class WalletDomains extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: WalletDomain::class))]
    protected WalletDomainCollection $walletDomains;

    public function getWalletDomains(): WalletDomainCollection
    {
        return $this->walletDomains;
    }

    public function setWalletDomains(WalletDomainCollection $walletDomains): void
    {
        $this->walletDomains = $walletDomains;
    }
}
