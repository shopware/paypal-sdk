<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context\AbstractContext;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_validation_issue',
    required: ['code', 'type', 'message']
)]
class ValidationIssue extends Struct
{
    /**
     * Consolidated error category
     *
     * Enum: [ INVENTORY_ISSUE, PRICING_ERROR, SHIPPING_ERROR, PAYMENT_ERROR, DATA_ERROR, BUSINESS_RULE_ERROR ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['INVENTORY_ISSUE', 'PRICING_ERROR', 'SHIPPING_ERROR', 'PAYMENT_ERROR', 'DATA_ERROR', 'BUSINESS_RULE_ERROR']
    )]
    protected string $code;

    /**
     * Type classification for error handling
     */
    #[OA\Property(
        type: 'string',
        enum: ['MISSING_FIELD', 'INVALID_DATA', 'BUSINESS_RULE']
    )]
    protected string $type;

    /**
     * Technical message for developers and logging
     */
    #[OA\Property(type: 'string')]
    protected string $message;

    /**
     * Customer-friendly message for end users
     */
    #[OA\Property(type: 'string')]
    protected ?string $userMessage = null;

    /**
     * Specific item ID if the issue is item-specific
     */
    #[OA\Property(type: 'string')]
    protected ?string $itemId = null;

    /**
     * Specific field name if the issue is field-specific
     */
    #[OA\Property(type: 'string')]
    protected ?string $field = null;

    /**
     * Category-specific context information
     */
    #[OA\Property(ref: ContextInterface::class)]
    // TODO: or use "oneOf"?
    protected ?AbstractContext $context = null;

    /**
     * Available actions to resolve this issue
     *
     * @var ResolutionOption[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: ResolutionOption::class)
    )]
    protected ?array $resolutionOptions = null;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        if (!\in_array($code, ['INVENTORY_ISSUE', 'PRICING_ERROR', 'SHIPPING_ERROR', 'PAYMENT_ERROR', 'DATA_ERROR', 'BUSINESS_RULE_ERROR'], true)) {
            throw new \InvalidArgumentException('Invalid code');
        }

        $this->code = $code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        if (!\in_array($type, ['MISSING_FIELD', 'INVALID_DATA', 'BUSINESS_RULE'], true)) {
            throw new \InvalidArgumentException('Invalid type');
        }

        $this->type = $type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getUserMessage(): ?string
    {
        return $this->userMessage;
    }

    public function setUserMessage(?string $userMessage): void
    {
        $this->userMessage = $userMessage;
    }

    public function getItemId(): ?string
    {
        return $this->itemId;
    }

    public function setItemId(?string $itemId): void
    {
        $this->itemId = $itemId;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(?string $field): void
    {
        $this->field = $field;
    }

    public function getContext(): ?ContextInterface
    {
        return $this->context;
    }

    public function setContext(?ContextInterface $context): void
    {
        $this->context = $context;
    }

    /**
     * @return ?ResolutionOption[]
     */
    public function getResolutionOptions(): ?array
    {
        return $this->resolutionOptions;
    }

    /**
     * @param ?ResolutionOption[] $resolutionOptions
     */
    public function setResolutionOptions(?array $resolutionOptions): void
    {
        $this->resolutionOptions = $resolutionOptions;
    }

    public function addResolutionOptions(ResolutionOption $resolutionOption): void
    {
        $this->resolutionOptions[] = $resolutionOption;
    }
}
