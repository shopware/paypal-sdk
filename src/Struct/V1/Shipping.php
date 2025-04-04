<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V1\Shipping\Tracker;
use Shopware\PayPalSDK\Struct\V1\Shipping\TrackerCollection;

#[OA\Schema(schema: 'paypal_v1_shipping')]
class Shipping extends Struct
{
    #[OA\Property(type: 'array', items: new OA\Items(ref: Tracker::class))]
    protected TrackerCollection $trackers;

    public function getTrackers(): TrackerCollection
    {
        return $this->trackers;
    }

    public function setTrackers(TrackerCollection $trackers): void
    {
        $this->trackers = $trackers;
    }
}
