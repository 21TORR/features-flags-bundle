<?php
declare(strict_types=1);

namespace Torr\FeatureFlags\Features\Loader;

use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * @final
 */
class FeaturesFileLoader
{
	private LoggerInterface $logger;
	private string $projectDir;

	/**
	 */
	public function __construct (
		LoggerInterface $logger,
		string $projectDir
	)
	{
		$this->logger = $logger;
		$this->projectDir = $projectDir;
	}


	/**
	 * Loads the feature flags fresh from the disk
	 *
	 * @return array<string, bool>
	 */
	public function load () : array
	{
		$projectDir = "{$this->projectDir}/.features.yaml";

		if (!\is_file($projectDir) || !\is_readable($projectDir))
		{
			return [];
		}

		try
		{
			$flags = Yaml::parseFile($projectDir);
			$parsed = $this->parseRawFlags($flags);

			if (null === $parsed)
			{
				$this->logger->error("Failed to parse feature flag files: invalid feature flags");
				return [];
			}

			return $parsed;
		}
		catch (ParseException $exception)
		{
			$this->logger->error("Failed to parse feature flag files: {message}", [
				"message" => $exception->getMessage(),
				"exception" => $exception,
			]);

			return [];
		}
	}


	/**
	 * Parses the raw flags
	 *
	 * @param mixed $flags
	 */
	private function parseRawFlags ($flags) : ?array
	{
		if (!\is_array($flags))
		{
			return null;
		}

		$parsed = [];

		foreach ($flags as $key => $value)
		{
			// invalid key: abort
			if (!\is_string($key))
			{
				return null;
			}

			if (\is_bool($value))
			{
				$parsed[$key] = $value;
				continue;
			}

			if ("off" === $value || "on" === $value)
			{
				$parsed[$key] = "on" === $value;
				continue;
			}

			// abort, as no supported value
			return null;
		}

		return $parsed;
	}
}
