<?php
declare(strict_types=1);

namespace Torr\FeaturesFlags\Features\Loader;

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

			if (!$this->isValid($flags))
			{
				$this->logger->error("Failed to parse feature flag files: invalid feature flags");
				return [];
			}

			return $flags;
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
	 * Checks whether the feature flags file is valid
	 */
	private function isValid ($flags) : bool
	{
		if (!\is_array($flags))
		{
			return false;
		}

		foreach ($flags as $key => $value)
		{
			if (!\is_string($key) || !\is_bool($value))
			{
				return false;
			}
		}

		return true;
	}
}
