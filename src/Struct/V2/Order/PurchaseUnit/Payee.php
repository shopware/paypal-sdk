<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit\Payee\DisplayData;

#[OA\Schema(schema: 'paypal_v2_order_purchase_unit_payee')]
class Payee extends Struct
{
    #[OA\Property(type: 'string', minLength: 3, maxLength: 254, pattern: Pattern::CONTAINS_EMAIL_ADDRESS)]
    protected string $emailAddress;

    #[OA\Property(type: 'string', minLength: 13, maxLength: 13, pattern: Pattern::ACCOUNT_ID, nullable: true)]
    protected ?string $merchantId = null;

    #[OA\Property(ref: DisplayData::class, nullable: true)]
    protected ?DisplayData $displayData = null;

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    public function getMerchantId(): ?string
    {
        return $this->merchantId;
    }

    public function setMerchantId(?string $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getDisplayData(): ?DisplayData
    {
        return $this->displayData;
    }

    public function setDisplayData(?DisplayData $displayData): void
    {
        $this->displayData = $displayData;
    }
}
