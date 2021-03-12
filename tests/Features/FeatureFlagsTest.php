<?php
declare(strict_types=1);

namespace Tests\Torr\FeaturesFlags\Features;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Torr\FeatureFlags\Features\FeatureFlags;
use Torr\FeatureFlags\Features\Loader\FeaturesFileLoader;

final class FeatureFlagsTest extends TestCase
{
	public function testHasFeature () : void
	{
		$loader = $this->createMock(FeaturesFileLoader::class);
		$loader
			->method("load")
			->willReturn([
				"active" => true,
				"deactivated" => false,
			]);

		$cache = $this->createMock(CacheInterface::class);

		$flags = new FeatureFlags($loader, $cache, true);

		self::assertTrue($flags->hasFeature("active"));
		self::assertFalse($flags->hasFeature("deactivated"));
		self::assertFalse($flags->hasFeature("missing"));
	}

	/**
	 *
	 */
	public function testCacheIntegration () : void
	{
		$loader = $this->createMock(FeaturesFileLoader::class);
		$cache = $this->createMock(CacheInterface::class);
		$cache
			->expects(self::once())
			->method("get")
			->willReturn([]);

		$flags = new FeatureFlags($loader, $cache, false);

		$flags->hasFeature("active");
	}
}
