<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Builder;

use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\Referral\MetaData;
use Shopware\PayPalSDK\Struct\AgenticCommerce\V1\ResolutionOption;

/**
 * @experimental
 */
final class MetaDataBuilder
{
    private readonly MetaData $metadata;

    public function __construct(
        private readonly ResolutionOption $resolutionOption,
        private readonly ResolutionBuilder $resolutionBuilder,
    ) {
        $this->metadata = $this->resolutionOption->getMetadata() ?? new MetaData();
        $this->resolutionOption->setMetadata($this->metadata);
    }

    public function withCostImpact(string $costImpact): self
    {
        $this->metadata->setCostImpact($costImpact);

        return $this;
    }

    public function withPriority(string $priority): self
    {
        $this->metadata->setPriority($priority);

        return $this;
    }

    public function withWaist(string $waist): self
    {
        $this->metadata->setWaist($waist);

        return $this;
    }

    public function withAutoApplicable(bool $autoApplicable): self
    {
        $this->metadata->setAutoApplicable($autoApplicable);

        return $this;
    }

    public function withEstimatedTime(string $estimatedTime): self
    {
        $this->metadata->setEstimatedTime($estimatedTime);

        return $this;
    }

    public function withRedirectRequired(bool $redirectRequired): self
    {
        $this->metadata->setRedirectRequired($redirectRequired);

        return $this;
    }

    public function end(): ResolutionBuilder
    {
        return $this->resolutionBuilder;
    }
}
