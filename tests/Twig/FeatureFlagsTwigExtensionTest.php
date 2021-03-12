<?php
declare(strict_types=1);

namespace Tests\Torr\FeaturesFlags\Twig;

use PHPUnit\Framework\TestCase;
use Torr\FeaturesFlags\Features\FeatureFlags;
use Torr\FeaturesFlags\Twig\FeatureFlagsTwigExtension;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class FeatureFlagsTwigExtensionTest extends TestCase
{
	/**
	 */
	public function testHasFeatureFunctionIntegration () : void
	{
		$twig = new Environment(
			new ArrayLoader([
				"test" => "{{ has_feature('test') }}",
			])
		);

		$featureFlags = $this->createMock(FeatureFlags::class);
		$featureFlags->expects(self::once())
			->method("hasFeature")
			->with("test");

		$twig->addExtension(new FeatureFlagsTwigExtension($featureFlags));
		$twig->render("test");
	}
}
