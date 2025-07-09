<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context;

use Shopware\PayPalSDK\Struct\Struct;

/**
 * @experimental
 */
abstract class AbstractContext extends Struct
{
    public const SPECIFIC_ISSUES = [];

    /**
     * Specific business rule issue type
     */
    #[OA\Property(type: 'string')]
    protected string $specificIssue;

    public function getSpecificIssue(): string
    {
        return $this->specificIssue;
    }

    public function setSpecificIssue(string $specificIssue): void
    {
        if (!\in_array($specificIssue, static::SPECIFIC_ISSUES, true)) {
            // TODO: Better one?
            throw new \RuntimeException('Specific issue not allowed: ' . $specificIssue);
        }

        $this->specificIssue = $specificIssue;
    }
}
