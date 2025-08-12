<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Builder\AgenticCommerce\V1;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Builder\AgenticCommerce\V1\ResolutionBuilder;
use Shopware\PayPalSDK\Builder\AgenticCommerce\V1\ValidationIssueBuilder;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\ResolutionOption;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\ValidationIssue;

#[CoversClass(ResolutionBuilder::class)]
class ResolutionBuilderTest extends TestCase
{
    public function testBuilder(): void
    {
        $issue = new ValidationIssue();
        $validationIssueBuilder = new ValidationIssueBuilder();
        $resolutionBuilder = new ResolutionBuilder($issue, $validationIssueBuilder);

        $resolutionBuilder
            ->withAction(ResolutionOption::ACTION__ACCEPT_TERMS)
            ->withLabel('label')
            ->withUrl('https://example.com');

        $resolutions = $issue->getResolutionOptions();

        static::assertCount(1, $resolutions);

        $resolution = $resolutions->first();

        static::assertNotNull($resolution);
        static::assertSame(ResolutionOption::ACTION__ACCEPT_TERMS, $resolution->getAction());
        static::assertSame('label', $resolution->getLabel());
        static::assertSame('https://example.com', $resolution->getUrl());

        static::assertSame($validationIssueBuilder, $resolutionBuilder->end());
    }

    public function testWithMetaData(): void
    {
        $issue = new ValidationIssue();
        $validationIssueBuilder = new ValidationIssueBuilder();
        $resolutionBuilder = new ResolutionBuilder($issue, $validationIssueBuilder);

        $resolutionBuilder
            ->withMetadata()
                ->withCostImpact('high')
                ->withWaist('medium')
                ->withAutoApplicable(true)
                ->withEstimatedTime('2 hours')
                ->withRedirectRequired(false);

        $resolutions = $issue->getResolutionOptions();

        static::assertCount(1, $resolutions);

        $resolution = $resolutions->first();

        static::assertNotNull($resolution);
        static::assertSame('high', $resolution->getMetadata()->getCostImpact());
        static::assertSame('medium', $resolution->getMetadata()->getWaist());
        static::assertTrue($resolution->getMetadata()->isAutoApplicable());
        static::assertSame('2 hours', $resolution->getMetadata()->getEstimatedTime());
        static::assertFalse($resolution->getMetadata()->isRedirectRequired());

        static::assertSame($validationIssueBuilder, $resolutionBuilder->end());
    }
}
