<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use OpenApi\Attributes as OA;

/**
 * @experimental
 */
#[OA\Schema(schema: 'paypal_agentic_commerce_v1_context_data_error_context')]
class DataErrorContext extends AbstractContext
{
    public const SPECIFIC_ISSUES = [
        'MISSING_CHECKOUT_FIELDS' => 'MISSING_CHECKOUT_FIELDS',
        'MISSING_PAYMENT_METHOD' => 'MISSING_PAYMENT_METHOD',
        'MISSING_POLICY_ACCEPTANCE' => 'MISSING_POLICY_ACCEPTANCE',
        'REQUIRED_FIELD_MISSING' => 'REQUIRED_FIELD_MISSING',
        'INVALID_EMAIL_FORMAT' => 'INVALID_EMAIL_FORMAT',
        'INVALID_PHONE_FORMAT' => 'INVALID_PHONE_FORMAT',
        'FIELD_VALUE_TOO_LONG' => 'FIELD_VALUE_TOO_LONG',
        'FIELD_VALUE_TOO_SHORT' => 'FIELD_VALUE_TOO_SHORT',
        'INVALID_DATE_FORMAT' => 'INVALID_DATE_FORMAT',
        'FUTURE_DATE_NOT_ALLOWED' => 'FUTURE_DATE_NOT_ALLOWED',
        'INVALID_CUSTOMER_DATA' => 'INVALID_CUSTOMER_DATA',
        'ITEM_NOT_FOUND' => 'ITEM_NOT_FOUND',
        'INVALID_ITEM_DATA' => 'INVALID_ITEM_DATA',
        'ITEM_ATTRIBUTE_MISMATCH' => 'ITEM_ATTRIBUTE_MISMATCH',
    ];

    /**
     * Name of the field with validation error
     */
    #[OA\Property(type: 'string')]
    protected ?string $fieldName = null;

    /**
     * Value that failed validation
     */
    #[OA\Property(type: 'string')]
    protected ?string $providedValue = null;

    /**
     * Expected format description
     */
    #[OA\Property(type: 'string')]
    protected ?string $expectedFormat = null;

    /**
     * Maximum allowed length
     */
    #[OA\Property(type: 'integer')]
    protected ?int $maxLength = null;

    /**
     * Minimum required length
     */
    #[OA\Property(type: 'integer')]
    protected ?int $minLength = null;

    /**
     * Current value length
     */
    #[OA\Property(type: 'integer')]
    protected ?int $currentLength = null;

    /**
     * Required regex pattern
     */
    #[OA\Property(type: 'string')]
    protected ?string $regex_pattern = null;

    /**
     * Suggested corrected value
     */
    #[OA\Property(type: 'string')]
    protected ?string $suggested_value = null;

    /**
     * List of allowed values for enum fields
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $allowedValues = null;

    /**
     * List of required field names
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $requiredFields = null;

    /**
     * Descriptions for required fields
     *
     * @var string[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
    )]
    protected ?array $fieldDescriptions = null;

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function setFieldName(?string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    public function getProvidedValue(): ?string
    {
        return $this->providedValue;
    }

    public function setProvidedValue(?string $providedValue): void
    {
        $this->providedValue = $providedValue;
    }

    public function getExpectedFormat(): ?string
    {
        return $this->expectedFormat;
    }

    public function setExpectedFormat(?string $expectedFormat): void
    {
        $this->expectedFormat = $expectedFormat;
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function setMaxLength(?int $maxLength): void
    {
        $this->maxLength = $maxLength;
    }

    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    public function setMinLength(?int $minLength): void
    {
        $this->minLength = $minLength;
    }

    public function getCurrentLength(): ?int
    {
        return $this->currentLength;
    }

    public function setCurrentLength(?int $currentLength): void
    {
        $this->currentLength = $currentLength;
    }

    public function getRegexPattern(): ?string
    {
        return $this->regex_pattern;
    }

    public function setRegexPattern(?string $regex_pattern): void
    {
        $this->regex_pattern = $regex_pattern;
    }

    public function getSuggestedValue(): ?string
    {
        return $this->suggested_value;
    }

    public function setSuggestedValue(?string $suggested_value): void
    {
        $this->suggested_value = $suggested_value;
    }

    /**
     * @return ?string[]
     */
    public function getAllowedValues(): ?array
    {
        return $this->allowedValues;
    }

    /**
     * @param ?string[] $allowedValues
     */
    public function setAllowedValues(?array $allowedValues): void
    {
        $this->allowedValues = $allowedValues;
    }

    public function addAllowedValue(string $allowedValue): void
    {
        $this->allowedValues[] = $allowedValue;
    }

    public function getRequiredFields(): ?array
    {
        return $this->requiredFields;
    }

    public function setRequiredFields(?array $requiredFields): void
    {
        $this->requiredFields = $requiredFields;
    }

    public function getFieldDescriptions(): ?array
    {
        return $this->fieldDescriptions;
    }

    public function setFieldDescriptions(?array $fieldDescriptions): void
    {
        $this->fieldDescriptions = $fieldDescriptions;
    }
}
