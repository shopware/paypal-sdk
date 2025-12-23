<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V3\ManagedAccount\IndividualOwner;

use Shopware\PayPalSDK\Struct\Collection;

/**
 * @extends Collection<IdentificationDocument>
 */
class IdentificationDocumentCollection extends Collection
{
    public static function getExpectedClass(): string
    {
        return IdentificationDocument::class;
    }
}
