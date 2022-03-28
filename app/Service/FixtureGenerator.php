<?php

namespace App\Service;

class FixtureGenerator
{
    public function generate(int $teams): array
    {
        if ($teams < 2) {
            return [];
        }

        if ($teams % 2 === 0) {
            return $this->generateEvenFixtures($teams);
        }

        $result = $this->generateEvenFixtures($teams + 1);
        foreach ($result as $i => $fixture) {
            foreach ($fixture as $j => $match) {
                if (in_array($teams, $match)) {
                    unset($result[$i][$j]);
                }
            }
            // fix array indexes
            $result[$i] = [...$result[$i]];
        }

        return $result;
    }

    protected function generateEvenFixtures(int $teams): array
    {
        $result = [];
        $halfFixturesCount = $teams - 1;

        $roundMatrix = $this->generateRoundMatrix($halfFixturesCount);
        foreach ($roundMatrix as $firstTeam => $secondTeams) {
            foreach ($secondTeams as $secondTeam => $meetingOrder) {
                if ($firstTeam !== $secondTeam) {
                    $round = $firstTeam < $secondTeam ? $meetingOrder : $halfFixturesCount + $meetingOrder;
                    $this->addMatchToFixture($result, $round, [$firstTeam, $secondTeam]);
                } else {
                    $this->addMatchToFixture($result, $meetingOrder, [$firstTeam, $halfFixturesCount]);
                    $this->addMatchToFixture(
                        $result,
                        $meetingOrder + $halfFixturesCount,
                        [$halfFixturesCount, $firstTeam]
                    );
                }
            }
        }
        ksort($result);

        return $result;
    }

    protected function addMatchToFixture(array &$fixtures, int $round, array $match): void
    {
        if (!isset($fixtures[$round])) {
            $fixtures[$round] = [];
        }
        $fixtures[$round][] = $match;
    }

    protected function generateRoundMatrix(int $halfFixturesCount)
    {
        $result = [];
        for ($i = 0; $i < $halfFixturesCount; $i++) {
            for ($j = 0; $j < $halfFixturesCount; $j++) {
                $result[$i][$j] = ($i + $j) % $halfFixturesCount;
            }
        }

        return $result;
    }
}
