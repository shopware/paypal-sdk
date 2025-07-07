<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context\ContextInterface;
use OpenApi\Attributes as OA;
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
     *
     * Enum: [ MISSING_FIELD, INVALID_DATA, BUSINESS_RULE ]
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
    protected string $userMessage;

    /**
     * Specific item ID if the issue is item-specific
     */
    #[OA\Property(type: 'string')]
    protected string $itemId;

    /**
     * Specific field name if the issue is field-specific
     */
    #[OA\Property(type: 'string')]
    protected string $field;

    /**
     * Category-specific context information
     */
    #[OA\Property(ref: ContextInterface::class)]
    // TODO: or use "oneOf"?
    protected ContextInterface $context;

    /**
     * Available actions to resolve this issue
     *
     * @var ResolutionOption[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: ResolutionOption::class)
    )]
    protected array $resolutionOptions;
}
