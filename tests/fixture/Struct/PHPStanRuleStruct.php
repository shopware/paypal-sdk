<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1;

use Shopware\PayPalSDK\Struct\Struct;

class PHPStanRuleStruct extends Struct
{
    public const ALLOWED_CONSTANT = 'something';

    public string $wrongVisibility;

    protected string $snake_case_name;

    protected $missingNativeType;

    protected ?string $missingDefaultValue;

    protected string $missingSetter;

    protected string $missingGetter;

    protected bool $boolVal;

    protected bool $isHasSpecialGetter;

    protected string $setterNotVoid;

    protected Struct $wrongGetSetTypes;

    protected Struct $wrongGetSetSignature;

    public function getWrongVisibility(): string
    {
        return $this->wrongVisibility;
    }

    public function setWrongVisibility(string $wrongVisibility): void
    {
        $this->wrongVisibility = $wrongVisibility;
    }

    public function getSnakeCaseName(): string
    {
        return $this->snake_case_name;
    }

    public function setSnakeCaseName(string $snake_case_name): void
    {
        $this->snake_case_name = $snake_case_name;
    }

    public function getMissingNativeType()
    {
        return $this->missingNativeType;
    }

    public function setMissingNativeType($missingNativeType): void
    {
        $this->missingNativeType = $missingNativeType;
    }

    public function getMissingDefaultValue(): ?string
    {
        return $this->missingDefaultValue;
    }

    public function setMissingDefaultValue(?string $missingDefaultValue): void
    {
        $this->missingDefaultValue = $missingDefaultValue;
    }

    public function getMissingSetter(): string
    {
        return $this->missingSetter;
    }

    public function setMissingGetter(string $missingGetter): void
    {
        $this->missingGetter = $missingGetter;
    }

    public function isBoolVal(): bool
    {
        return $this->boolVal;
    }

    public function setBoolVal(bool $boolVal): void
    {
        $this->boolVal = $boolVal;
    }

    public function isHasSpecialGetter(): bool
    {
        return $this->isHasSpecialGetter;
    }

    public function setIsHasSpecialGetter(bool $isHasSpecialGetter): void
    {
        $this->isHasSpecialGetter = $isHasSpecialGetter;
    }

    public function getSetterNotVoid(): string
    {
        return $this->setterNotVoid;
    }

    public function setSetterNotVoid(string $setterNotVoid): string
    {
        $this->setterNotVoid = $setterNotVoid;

        return '';
    }

    public function getWrongGetSetTypes(): \stdClass
    {
        return new \stdClass();
    }

    public function setWrongGetSetTypes(\stdClass $wrongGetSetTypes): void
    {
    }

    protected function getWrongGetSetSignature(string $paramNotAllowed): void
    {
    }

    public function setWrongGetSetSignature(int $value, string $extra): void
    {
    }
}
