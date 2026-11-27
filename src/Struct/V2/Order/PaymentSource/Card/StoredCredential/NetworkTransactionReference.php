<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2\Order\PaymentSource\Card\StoredCredential;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Pattern;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_v2_order_payment_source_card_stored_credential_network_transaction_reference',
    required: ['id']
)]
class NetworkTransactionReference extends Struct
{
    #[OA\Property(type: 'string', minLength: 9, maxLength: 36, pattern: Pattern::NETWORK_TRANSACTION_ID)]
    protected string $id;

    #[OA\Property(type: 'string', minLength: 4, maxLength: 4, pattern: Pattern::DIGITS)]
    protected ?string $date = null;

    #[OA\Property(type: 'string')]
    protected ?string $network = null;

    #[OA\Property(type: 'string', minLength: 1, maxLength: 36, pattern: Pattern::ACQUIRER_REFERENCE_NUMBER)]
    protected ?string $acquirerReferenceNumber = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function setNetwork(?string $network): void
    {
        $this->network = $network;
    }

    public function getAcquirerReferenceNumber(): ?string
    {
        return $this->acquirerReferenceNumber;
    }

    public function setAcquirerReferenceNumber(?string $acquirerReferenceNumber): void
    {
        $this->acquirerReferenceNumber = $acquirerReferenceNumber;
    }
}
