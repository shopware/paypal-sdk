<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_data_error_context')]
class DataErrorContext extends Struct implements ContextInterface
{
    /**
     * Specific data validation issue type
     *
     * Enum: [ MISSING_CHECKOUT_FIELDS, MISSING_PAYMENT_METHOD, MISSING_POLICY_ACCEPTANCE, REQUIRED_FIELD_MISSING, INVALID_EMAIL_FORMAT, INVALID_PHONE_FORMAT, FIELD_VALUE_TOO_LONG, FIELD_VALUE_TOO_SHORT, INVALID_DATE_FORMAT, FUTURE_DATE_NOT_ALLOWED, INVALID_CUSTOMER_DATA, ITEM_NOT_FOUND, INVALID_ITEM_DATA, ITEM_ATTRIBUTE_MISMATCH ]
     */
    #[OA\Property(
        type: 'string',
        enum: ['MISSING_CHECKOUT_FIELDS', 'MISSING_PAYMENT_METHOD', 'MISSING_POLICY_ACCEPTANCE', 'REQUIRED_FIELD_MISSING', 'INVALID_EMAIL_FORMAT', 'INVALID_PHONE_FORMAT', 'FIELD_VALUE_TOO_LONG', 'FIELD_VALUE_TOO_SHORT', 'INVALID_DATE_FORMAT', 'FUTURE_DATE_NOT_ALLOWED', 'INVALID_CUSTOMER_DATA', 'ITEM_NOT_FOUND', 'INVALID_ITEM_DATA', 'ITEM_ATTRIBUTE_MISMATCH']
    )]
    protected string $specificIssue;

    /**
     * Name of the field with validation error
     */
    #[OA\Property(type: 'string')]
    protected string $fieldName;

    /**
     * Value that failed validation
     */
    #[OA\Property(type: 'string')]
    protected string $providedValue;

    /**
     * Expected format description
     */
    #[OA\Property(type: 'string')]
    protected string $expectedFormat;

    /**
     * Maximum allowed length
     */
    #[OA\Property(type: 'integer')]
    protected int $maxLength;

    /**
     * Minimum required length
     */
    #[OA\Property(type: 'integer')]
    protected int $minLength;

    /**
     * Current value length
     */
    #[OA\Property(type: 'integer')]
    protected int $currentLength;

    /**
     * Required regex pattern
     */
    #[OA\Property(type: 'string')]
    protected string $regex_pattern;

    /**
     * Suggested corrected value
     */
    #[OA\Property(type: 'string')]
    protected string $suggested_value;

    /**
     * List of allowed values for enum fields
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $allowedValues;

    /**
     * List of required field names
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $requiredFields;

    /**
     * Descriptions for required fields
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected array $fieldDescriptions;
}
