<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_checkout_option')]
class CheckoutOption extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $checkoutOptionName = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $checkoutOptionValue = null;

    public function getCheckoutOptionName(): ?string
    {
        return $this->checkoutOptionName;
    }

    public function setCheckoutOptionName(?string $checkoutOptionName): void
    {
        $this->checkoutOptionName = $checkoutOptionName;
    }

    public function getCheckoutOptionValue(): ?string
    {
        return $this->checkoutOptionValue;
    }

    public function setCheckoutOptionValue(?string $checkoutOptionValue): void
    {
        $this->checkoutOptionValue = $checkoutOptionValue;
    }
}
