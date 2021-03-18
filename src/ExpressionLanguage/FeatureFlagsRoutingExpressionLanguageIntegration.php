<?php
declare(strict_types=1);

namespace Torr\FeatureFlags\ExpressionLanguage;

use Torr\FeatureFlags\Features\FeatureFlags;

/**
 * Provides the `has_feature()` function call for routing conditions
 */
final class FeatureFlagsRoutingExpressionLanguageIntegration
{
	private FeatureFlags $featureFlags;

	/**
	 */
	public function __construct (FeatureFlags $featureFlags)
	{
		$this->featureFlags = $featureFlags;
	}

	/**
	 */
	public function __invoke (string $flag) : bool
	{
		return $this->featureFlags->hasFeature($flag);
	}
}
