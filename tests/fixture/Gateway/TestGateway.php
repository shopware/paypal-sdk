<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Fixture\Gateway;

use Shopware\PayPalSDK\Contract\Context\ApiContextInterface;
use Shopware\PayPalSDK\Gateway\AbstractGateway;
use Shopware\PayPalSDK\Struct\Struct;

class TestGateway extends AbstractGateway
{
    /**
     * @template T of Struct
     *
     * @param class-string<T>|null $responseClass
     *
     * @return ($responseClass is null ? null : T)
     */
    public function testPost(?Struct $body, ?string $responseClass, ApiContextInterface $context): ?Struct
    {
        return $this->request(
            'POST',
            '/some/path/to/endpoint',
            $body,
            $responseClass,
            $context,
        );
    }
}
