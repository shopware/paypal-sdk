<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_incentive_info')]
class IncentiveInfo extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: IncentiveDetail::class), nullable: true)]
    protected ?IncentiveDetailCollection $incentiveDetails = null;

    public function getIncentiveDetails(): IncentiveDetailCollection
    {
        return $this->incentiveDetails ?? $this->incentiveDetails = new IncentiveDetailCollection();
    }

    public function setIncentiveDetails(?IncentiveDetailCollection $incentiveDetails): void
    {
        $this->incentiveDetails = $incentiveDetails;
    }
}
