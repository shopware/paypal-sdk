<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Contract\Struct\V2\Order\PaymentSource;

use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Common\Attributes;

interface VaultedPaymentSourceInterface extends VaultablePaymentSourceInterface
{
    public function getAttributes(): ?Attributes;

    public function setAttributes(?Attributes $attributes): void;

    public function getVaultId(): string;

    public function setVaultId(string $vaultId): void;

    public function getVaultIdentifier(): string;
}
