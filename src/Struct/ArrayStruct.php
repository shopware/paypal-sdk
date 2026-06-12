<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct;

use Shopware\PayPalSDK\Util\CaseConverter;

#[\AllowDynamicProperties]
class ArrayStruct extends Struct
{
    public function assign(array $data): static
    {
        foreach ($data as $key => $value) {
            $this->{CaseConverter::denormalize((string) $key)} = $value;
        }

        return $this;
    }
}
