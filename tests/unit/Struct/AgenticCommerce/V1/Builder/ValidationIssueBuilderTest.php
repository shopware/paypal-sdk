<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\AgenticCommerce\V1\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Builder\ValidationIssueBuilder;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Context\InventoryIssueContext;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\ResolutionOption;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\ResolutionOptionCollection;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\ValidationIssue;

#[CoversClass(ValidationIssueBuilder::class)]
class ValidationIssueBuilderTest extends TestCase
{
    public function testBuilder(): void
    {
        $builder = new ValidationIssueBuilder();

        $context = new InventoryIssueContext();
        $options = new ResolutionOptionCollection([new ResolutionOption()]);

        $issue = $builder
            ->withContext($context)
            ->withCode(ValidationIssue::CODE__INVENTORY_ISSUE)
            ->withType(ValidationIssue::TYPE__BUSINESS_RULE)
            ->withMessage('The request is invalid.')
            ->withUserMessage('Please check your input and try again.')
            ->withItemId('item123')
            ->withField('email')
            ->withResolutionOptions($options)
            ->build();

        static::assertSame($context, $issue->getContext());
        static::assertSame(ValidationIssue::CODE__INVENTORY_ISSUE, $issue->getCode());
        static::assertSame(ValidationIssue::TYPE__BUSINESS_RULE, $issue->getType());
        static::assertSame('The request is invalid.', $issue->getMessage());
        static::assertSame('Please check your input and try again.', $issue->getUserMessage());
        static::assertSame('item123', $issue->getItemId());
        static::assertSame('email', $issue->getField());
        static::assertSame($options, $issue->getResolutionOptions());
    }

    public function testCompleteBuild(): void
    {
        $builder = new ValidationIssueBuilder();

        $issue = $builder
            ->addResolutionOption()
                ->withLabel('Remove item')
                ->withUrl('https://example.com/remove')
                ->withAction(ResolutionOption::ACTION__ACCEPT_TERMS)
                ->withMetadata()
                    ->withCostImpact('high')
                    ->withWaist('medium')
                    ->withAutoApplicable(true)
                    ->withEstimatedTime('2 hours')
                    ->withRedirectRequired(false)
                    ->end()
                ->end()
            ->addResolutionOption()
                ->withLabel('Contact support')
                ->withUrl('https://example.com/contact')
                ->withAction(ResolutionOption::ACTION__CONTACT_SUPPORT)
                ->withMetadata()
                    ->withCostImpact('low')
                    ->withWaist('low')
                    ->withAutoApplicable(false)
                    ->withEstimatedTime('1 hour')
                    ->withRedirectRequired(true)
                    ->end()
                ->end()
            ->build();

        static::assertCount(2, $issue->getResolutionOptions());
        $resolutions = $issue->getResolutionOptions();

        $firstResolution = $resolutions->first();
        static::assertNotNull($firstResolution);
        static::assertSame('Remove item', $firstResolution->getLabel());
        static::assertSame('https://example.com/remove', $firstResolution->getUrl());
        static::assertSame(ResolutionOption::ACTION__ACCEPT_TERMS, $firstResolution->getAction());
        static::assertSame('high', $firstResolution->getMetadata()->getCostImpact());
        static::assertSame('medium', $firstResolution->getMetadata()->getWaist());
        static::assertTrue($firstResolution->getMetadata()->isAutoApplicable());
        static::assertSame('2 hours', $firstResolution->getMetadata()->getEstimatedTime());
        static::assertFalse($firstResolution->getMetadata()->isRedirectRequired());

        $secondResolution = $resolutions->last();
        static::assertNotNull($secondResolution);
        static::assertSame('Contact support', $secondResolution->getLabel());
        static::assertSame('https://example.com/contact', $secondResolution->getUrl());
        static::assertSame(ResolutionOption::ACTION__CONTACT_SUPPORT, $secondResolution->getAction());
        static::assertSame('low', $secondResolution->getMetadata()->getCostImpact());
        static::assertSame('low', $secondResolution->getMetadata()->getWaist());
        static::assertFalse($secondResolution->getMetadata()->isAutoApplicable());
        static::assertSame('1 hour', $secondResolution->getMetadata()->getEstimatedTime());
        static::assertTrue($secondResolution->getMetadata()->isRedirectRequired());
    }
}
