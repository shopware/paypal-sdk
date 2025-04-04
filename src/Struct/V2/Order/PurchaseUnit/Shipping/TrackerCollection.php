<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Shipping;

use Shopware\PayPalSDK\Struct\Collection;

/**
 * @extends Collection<Tracker>
 */
class TrackerCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return Tracker::class;
    }

    /**
     * @return string[]
     */
    public function getTrackerCodes(): array
    {
        $trackerCodes = [];
        foreach ($this->elements as $tracker) {
            \strtok($tracker->getId(), '-');
            $code = \strtok('');

            if ($code && $tracker->getStatus() === Tracker::STATUS_SHIPPED) {
                $trackerCodes[] = $code;
            }
        }

        return $trackerCodes;
    }
}
