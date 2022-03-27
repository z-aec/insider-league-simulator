<?php

namespace Tests\Unit;

use App\Service\FixtureGenerator;
use App\Service\MatchSimulator;
use PHPUnit\Framework\TestCase;
use Tests\Util\PhpUnitUtil;

class MatchSimulatorTest extends TestCase
{
    const EPS = 0.001;

    /** @var MatchSimulator  */
    private $service;

    public function setUp(): void
    {
        $this->service = new MatchSimulator();
    }

    /**
     * @dataProvider probabilitySpaceDataProvider
     */
    public function testProbabilitySpace(array $teamStrength, array $expectedResult)
    {
        $actualResult = PhpUnitUtil::callMethod($this->service, 'getProbabilitySpace', $teamStrength);

        $this->assertEqualsWithDelta($expectedResult[0], $actualResult[0], self::EPS);
        $this->assertEqualsWithDelta($expectedResult[1], $actualResult[1], self::EPS);
    }

    public function probabilitySpaceDataProvider() {
        return [
            [[1, 1], [0.333, 0.666]],
            [[1, 0], [1, 1]],
            [[0, 1], [0, 0]],
            [[2, 1], [0.518, 0.74]],
            [[1, 2], [0.259, 0.481]],
            [[1, 3], [0.208, 0.375]]
        ];
    }
}
