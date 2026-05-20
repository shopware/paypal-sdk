<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\V1\Reporting\Transactions;

use OpenApi\Attributes as OA;
use Shopware\PayPalSDK\Struct\Struct;

#[OA\Schema(schema: 'paypal_v1_reporting_transactions_name')]
class Name extends Struct
{
    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $prefix = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $givenName = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $surname = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $middleName = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $suffix = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $alternateFullName = null;

    #[OA\Property(type: 'string', nullable: true)]
    protected ?string $fullName = null;

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    public function setGivenName(?string $givenName): void
    {
        $this->givenName = $givenName;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function setSuffix(?string $suffix): void
    {
        $this->suffix = $suffix;
    }

    public function getAlternateFullName(): ?string
    {
        return $this->alternateFullName;
    }

    public function setAlternateFullName(?string $alternateFullName): void
    {
        $this->alternateFullName = $alternateFullName;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }
}
