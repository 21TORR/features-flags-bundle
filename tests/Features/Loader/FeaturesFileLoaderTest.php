<?php
declare(strict_types=1);

namespace Tests\Torr\FeaturesFlags\Features\Loader;

use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use Torr\FeaturesFlags\Features\Loader\FeaturesFileLoader;

final class FeaturesFileLoaderTest extends TestCase
{
	public function provideLoading () : iterable
	{
		yield ["missing", false, []];
		yield ["simple", false, ["active" => true, "inactive" => false]];
		yield ["nested-array", true, []];
		yield ["number", true, []];
		yield ["plain-string", true, []];

	}

	/**
	 * @dataProvider provideLoading
	 */
	public function testLoading (string $fixturesDir, bool $expectLogMessage, array $expectedReturn) : void
	{
		$fixturesFilePath = \dirname(__DIR__, 2) . "/fixtures/feature-files/{$fixturesDir}";
		$logger = new TestLogger();

		$loader = new FeaturesFileLoader($logger, $fixturesFilePath);
		$flags = $loader->load();

		self::assertSame($expectLogMessage, $logger->hasErrorRecords());
		self::assertSame($expectedReturn, $flags);
	}
}
