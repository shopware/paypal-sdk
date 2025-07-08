<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Value\ValueInterface;
use Shopware\PayPalSDK\Struct\Struct;

/**
 * PayPal-controlled checkout field for buyer data collection with structured values and validation.
 *
 * Field Lifecycle:
 *
 * PENDING: Field needs customer input
 * COMPLETED: Valid value provided and accepted
 * REJECTED: Invalid/unacceptable value provided
 * ERROR: System error during processing
 *
 * Structured Values: Each field type has a specific value schema based on its requirements. Age verification uses boolean confirmation, text fields use strings, etc.
 */
#[OA\Schema(
    schema: 'paypal_agentic_commerce_v1_checkout_field',
    required: ['type', 'status']
)]
class CheckoutField extends Struct
{
    /**
     * PayPal-approved checkout field type
     *
     * Enum: [ AGE_VERIFICATION_18_PLUS, AGE_VERIFICATION_21_PLUS, GIFT_RECIPIENT_EMAIL, GIFT_RECIPIENT_NAME, GIFT_MESSAGE, DELIVERY_INSTRUCTIONS, DELIVERY_DATE_PREFERENCE, ALLERGY_INFORMATION, CUSTOM_ENGRAVING_TEXT, CUSTOM_SIZING_INFO, TERMS_ACCEPTANCE, PRIVACY_CONSENT ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['AGE_VERIFICATION_18_PLUS', 'AGE_VERIFICATION_21_PLUS', 'GIFT_RECIPIENT_EMAIL', 'GIFT_RECIPIENT_NAME', 'GIFT_MESSAGE', 'DELIVERY_INSTRUCTIONS', 'DELIVERY_DATE_PREFERENCE', 'ALLERGY_INFORMATION', 'CUSTOM_ENGRAVING_TEXT', 'CUSTOM_SIZING_INFO', 'TERMS_ACCEPTANCE', 'PRIVACY_CONSENT']
    )]
    protected string $type;

    /**
     * Field completion and validation status:
     *
     * PENDING: Field needs customer input
     *
     * Initial state when field is required
     * AI agent should collect this information
     * value field is null or empty
     *
     * COMPLETED: Valid value provided and accepted
     *
     * Customer provided acceptable input
     * Value passes all validation rules
     * Cart can proceed with this field resolved
     *
     * REJECTED: Invalid or unacceptable value provided
     *
     * Customer provided input that doesn't meet requirements
     * validation_issue explains the specific problem
     * AI agent should request corrected input
     *
     * ERROR: System error during processing
     *
     * Technical failure in field processing
     * Should retry or escalate to support
     * Not caused by customer input
     */
    #[OA\Property(
        type: 'string',
        enum: ['PENDING', 'COMPLETED', 'REJECTED', 'ERROR']
    )]
    protected string $status;

    /**
     * Structured value based on field type. Each checkout field type has a specific value schema.
     * Use oneOf to validate against the appropriate structure for the field type.
     */
    #[OA\Property(ref: ValueInterface::class)]
    // TODO: Or use "oneOf"?
    protected ValueInterface $value;

    /**
     * Additional context and metadata for the checkout field.
     * This is a flexible object that can contain any field-specific information needed for validation, display, or processing.
     * The structure varies based on the field type.
     */
    #[OA\Property(type: 'mixed')]
    protected $context;

    #[OA\Property(ref: ValidationIssue::class)]
    protected ValidationIssue $validationIssue;
}
