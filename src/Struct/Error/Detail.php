<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\Error;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_error_detail')]
class Detail extends Struct
{
    #[OA\Property(type: 'string')]
    protected ?string $field = null;

    #[OA\Property(type: 'string')]
    protected ?string $value = null;

    #[OA\Property(type: 'string')]
    protected ?string $location = null;

    #[OA\Property(type: 'string')]
    protected ?string $issue = null;

    #[OA\Property(type: 'string')]
    protected ?string $description = null;

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(?string $field): void
    {
        $this->field = $field;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getIssue(): ?string
    {
        return $this->issue;
    }

    public function setIssue(?string $issue): void
    {
        $this->issue = $issue;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function jsonSerialize(): array
    {
        return \array_filter(parent::jsonSerialize());
    }
}
