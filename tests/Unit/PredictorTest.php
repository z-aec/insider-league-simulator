<?php

namespace Tests\Unit;

use App\Service\PredictorStrategy\SmallFixturesCountPredictor;
use PHPUnit\Framework\TestCase;

class PredictorTest extends TestCase
{
    const EPS = 0.001;

    /** @var SmallFixturesCountPredictor  */
    private $service;

    public function setUp(): void
    {
        $this->service = new SmallFixturesCountPredictor();
    }

    /** @dataProvider predictDataProvider */
    public function testPredict(array $expected, array $score, int $fixturesCount)
    {
        $scoreTable = array_map(fn($row) => array_combine(['won', 'drawn', 'lost'], $row), $score);

        $actual = $this->service->predict($scoreTable, $fixturesCount);
        $this->assertEquals(count($expected), count($actual));
        foreach ($expected as $key => $value) {
            $this->assertEqualsWithDelta($expected[$key], $actual[$key], self::EPS);
        }
        //var_dump($expected, $actual);
    }

    public function predictDataProvider() {
        return [
            [
                [1, 0.111],
                [
                    [1, 0, 0],
                    [0, 0, 1],
                ],
                1
            ],
            [  // Win (33%) + Draw (33%), because on draw both teams gets the same points and win
                [0.666, 0.666],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                ],
                1
            ],
            [ // First team win inevitably
                [1, 0],
                [
                    [2, 0, 0],
                    [0, 0, 1],
                ],
                1
            ],
            [ // But if there would be two matches, the second team has a little change...
                [1, 0.012],
                [
                    [2, 0, 0],
                    [0, 0, 1],
                ],
                2
            ],
            [
                [1, 0.012, 0.012],
                [
                    [2, 0, 0],
                    [0, 0, 1],
                    [0, 0, 1],
                ],
                2
            ],
            [
                [0.518, 0.518, 0.518],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
                1
            ],
            [
                [0.297, 0.297, 0.297, 0.297],
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
                5
            ],
            [
                [0.736, 0.29, 0.083, 0.04],
                [
                    [3, 2, 1],
                    [2, 3, 4],
                    [1, 4, 2],
                    [0, 6, 1],
                ],
                3
            ],
        ];
    }
}
