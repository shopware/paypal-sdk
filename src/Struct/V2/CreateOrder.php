<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V2;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\ConstantsV2;
use Shopware\PayPalSDK\Struct\Struct;
use Shopware\PayPalSDK\Struct\V2\Order\ApplicationContext;
use Shopware\PayPalSDK\Struct\V2\Order\Payer;
use Shopware\PayPalSDK\Struct\V2\Order\PaymentSource;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnit;
use Shopware\PayPalSDK\Struct\V2\Order\PurchaseUnitCollection;

#[OA\Schema(schema: 'paypal_v2_create_order', required: ['intent', 'purchase_units'])]
class CreateOrder extends Struct
{
    public const PROCESSING_INSTRUCTION_COMPLETE_ON_APPROVAL = 'ORDER_COMPLETE_ON_PAYMENT_APPROVAL';

    #[OA\Property(type: 'string', enum: [ConstantsV2::INTENT_CAPTURE, ConstantsV2::INTENT_AUTHORIZE])]
    protected string $intent;

    #[OA\Property(ref: Payer::class, nullable: true)]
    protected ?Payer $payer;

    #[OA\Property(type: 'array', items: new OA\Items(ref: PurchaseUnit::class))]
    protected PurchaseUnitCollection $purchaseUnits;

    #[OA\Property(ref: ApplicationContext::class, nullable: true)]
    protected ?ApplicationContext $applicationContext;

    #[OA\Property(ref: PaymentSource::class, nullable: true)]
    protected ?PaymentSource $paymentSource;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $processingInstruction;

    public function getIntent(): string
    {
        return $this->intent;
    }

    public function setIntent(string $intent): void
    {
        $this->intent = $intent;
    }

    public function getPayer(): ?Payer
    {
        return $this->payer ?? null;
    }

    public function setPayer(?Payer $payer): void
    {
        $this->payer = $payer;
    }

    public function getPurchaseUnits(): PurchaseUnitCollection
    {
        return $this->purchaseUnits ??= new PurchaseUnitCollection();
    }

    public function setPurchaseUnits(PurchaseUnitCollection $purchaseUnits): void
    {
        $this->purchaseUnits = $purchaseUnits;
    }

    public function getApplicationContext(): ?ApplicationContext
    {
        return $this->applicationContext ?? null;
    }

    public function setApplicationContext(?ApplicationContext $applicationContext): void
    {
        $this->applicationContext = $applicationContext;
    }

    public function getPaymentSource(): ?PaymentSource
    {
        return $this->paymentSource ?? null;
    }

    public function setPaymentSource(?PaymentSource $paymentSource): void
    {
        $this->paymentSource = $paymentSource;
    }

    public function getProcessingInstruction(): ?string
    {
        return $this->processingInstruction ?? null;
    }

    public function setProcessingInstruction(?string $processingInstruction): void
    {
        $this->processingInstruction = $processingInstruction;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        if (!isset($this->intent) || !\in_array($this->intent, [ConstantsV2::INTENT_CAPTURE, ConstantsV2::INTENT_AUTHORIZE], true)) {
            throw new \InvalidArgumentException('CreateOrder requires an explicit CAPTURE or AUTHORIZE intent.');
        }

        if (!isset($this->purchaseUnits) || $this->purchaseUnits->count() === 0) {
            throw new \InvalidArgumentException('CreateOrder requires at least one purchase unit.');
        }

        return \array_filter(parent::jsonSerialize(), static fn (mixed $value): bool => $value !== null);
    }
}
