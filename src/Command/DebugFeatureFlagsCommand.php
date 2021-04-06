<?php
declare(strict_types=1);

namespace Torr\FeatureFlags\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Torr\FeatureFlags\Features\FeatureFlags;

final class DebugFeatureFlagsCommand extends Command
{
	protected static $defaultName = "21torr:feature-flags:debug";
	private FeatureFlags $flags;

	public function __construct (FeatureFlags $flags)
	{
		parent::__construct();
		$this->flags = $flags;
	}


	/**
	 * @inheritDoc
	 */
	protected function configure () : void
	{
		$this->setDescription("Dumps all configured feature flags");
	}

	/**
	 * @inheritDoc
	 */
	protected function execute (InputInterface $input, OutputInterface $output) : int
	{
		$io = new SymfonyStyle($input, $output);
		$io->title("Feature Flags: Debug");

		$rows = [];

		foreach ($this->flags->getAll() as $name => $value)
		{
			$rows[] = [
				$name,
				$value ? "<fg=green>enabled</>" : "<fg=red>disabled</>",
			];
		}

		$io->table(["Flag", "Value"], $rows);

		return 0;
	}
}
