<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\EligibleMethodsData\EligibleMethods;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_v2_eligible_methods_data_eligible_methods_paypal')]
class Paypal extends Struct
{
    #[OA\Property(type: 'boolean')]
    protected bool $canBeVaulted;

    public function isCanBeVaulted(): bool
    {
        return $this->canBeVaulted;
    }

    public function setCanBeVaulted(bool $canBeVaulted): void
    {
        $this->canBeVaulted = $canBeVaulted;
    }
}
