<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;

abstract class AbstractAPMPaymentSource extends AbstractPaymentSource
{
    #[OA\Property(type: 'string', minLength: 1, maxLength: 300)]
    protected string $name;

    #[OA\Property(type: 'string', minLength: 2, maxLength: 2, pattern: Pattern::COUNTRY_CODE)]
    protected string $countryCode;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }
}
