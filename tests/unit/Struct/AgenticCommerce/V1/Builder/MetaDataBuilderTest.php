<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Tests\Unit\Struct\AgenticCommerce\V1\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Builder\MetaDataBuilder;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Builder\ResolutionBuilder;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Builder\ValidationIssueBuilder;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\ResolutionOption;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\ValidationIssue;

#[CoversClass(MetaDataBuilder::class)]
class MetaDataBuilderTest extends TestCase
{
    public function testBuilder(): void
    {
        $option = new ResolutionOption();

        $resolutionBuilder = new ResolutionBuilder(
            new ValidationIssue(),
            new ValidationIssueBuilder(),
        );

        $builder = new MetaDataBuilder($option, $resolutionBuilder);

        $builder
            ->withCostImpact('high')
            ->withWaist('medium')
            ->withAutoApplicable(true)
            ->withEstimatedTime('2 hours')
            ->withRedirectRequired(false);

        $metaData = $option->getMetadata();

        static::assertSame('high', $metaData->getCostImpact());
        static::assertSame('medium', $metaData->getWaist());
        static::assertTrue($metaData->isAutoApplicable());
        static::assertSame('2 hours', $metaData->getEstimatedTime());
        static::assertFalse($metaData->isRedirectRequired());

        static::assertSame($resolutionBuilder, $builder->end());
    }
}
