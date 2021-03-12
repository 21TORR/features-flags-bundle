<?php
declare(strict_types=1);

namespace Torr\FeatureFlags\Features;

use Symfony\Contracts\Cache\CacheInterface;
use Torr\FeatureFlags\Features\Loader\FeaturesFileLoader;

/**
 * @final
 */
class FeatureFlags
{
	private const CACHE_KEY = "21torr.feature-flags";

	/** @var array<string,bool>|null  */
	private ?array $flags = null;
	private FeaturesFileLoader $featuresFileLoader;
	private CacheInterface $cache;
	private bool $isDebug;

	/**
	 */
	public function __construct (
		FeaturesFileLoader $featuresFileLoader,
		CacheInterface $cache,
		bool $isDebug
	)
	{
		$this->featuresFileLoader = $featuresFileLoader;
		$this->cache = $cache;
		$this->isDebug = $isDebug;
	}

	/**
	 * Returns all feature flags
	 *
	 * @return array<string,bool>
	 */
	private function getFlags () : array
	{
		if (null === $this->flags)
		{
			$this->flags = !$this->isDebug
				? $this->cache->get(self::CACHE_KEY, [$this->featuresFileLoader, "load"])
				: $this->featuresFileLoader->load();
		}

		return $this->flags;
	}


	/**
	 * Returns whether the flag is enabled
	 */
	public function hasFeature (string $flag) : bool
	{
		return $this->getFlags()[$flag] ?? false;
	}
}
