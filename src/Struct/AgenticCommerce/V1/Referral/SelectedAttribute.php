<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Referral;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
class SelectedAttribute extends Struct
{
    #[OA\Property(type: 'string')]
    protected string $name;

    #[OA\Property(type: 'string')]
    protected string $value;

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
