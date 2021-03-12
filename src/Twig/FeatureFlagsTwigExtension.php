<?php
declare(strict_types=1);

namespace Torr\FeatureFlags\Twig;

use Torr\FeatureFlags\Features\FeatureFlags;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FeatureFlagsTwigExtension extends AbstractExtension
{
	private FeatureFlags $featureFlags;

	/**
	 */
	public function __construct (FeatureFlags $featureFlags)
	{
		$this->featureFlags = $featureFlags;
	}

	/**
	 * @inheritDoc
	 */
	public function getFunctions() : array
	{
		return [
			new TwigFunction("has_feature", [$this->featureFlags, "hasFeature"]),
		];
	}
}
