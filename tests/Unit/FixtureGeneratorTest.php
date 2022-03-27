<?php

namespace Tests\Unit;

use App\Service\FixtureGenerator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Util\PhpUnitUtil;

class FixtureGeneratorTest extends TestCase
{
    /** @var FixtureGenerator  */
    private $service;

    public function setUp(): void
    {
        $this->service = new FixtureGenerator();
    }

    /** @dataProvider teamsFixturesDataProvider */
    public function testGenerate(int $teams, array $roundMatrix)
    {
        $actualResult = $this->service->generate($teams);

        if (empty($roundMatrix)) {
            $this->assertEmpty($actualResult);
        }

        foreach ($actualResult as $round => $teams) {
            foreach ($teams as [$firstTeam, $secondTeam]) {
                $this->assertEquals($roundMatrix[$firstTeam][$secondTeam], $round);
            }
        }
    }

    public function teamsFixturesDataProvider() {
        return [
            [2, [
                [null, 0],
                [1, null]
            ]],
            [4, [
                [null, 1, 2, 0],
                [4, null, 0, 2],
                [5, 3, null, 1],
                [3, 5, 4, null]
            ]],
            [6, [
                [null, 1, 2, 3, 4, 0],
                [6, null, 3, 4, 0, 2],
                [7, 8, null, 0, 1, 4],
                [8, 9, 5, null, 2, 1],
                [9, 5, 6, 7, null, 3],
                [5, 7, 9, 6, 8, null]
            ]],
            [3, [
                [null, 1, 2, 0],
                [4, null, 0, 2],
                [5, 3, null, 1],
                [3, 5, 4, null]
            ]],
            [1, []],
        ];
    }
}
