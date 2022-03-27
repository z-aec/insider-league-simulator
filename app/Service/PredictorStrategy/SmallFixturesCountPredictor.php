<?php

namespace App\Service\PredictorStrategy;

use App\Service\Predictor;

class SmallFixturesCountPredictor extends AbstractPredictor implements PredictorInterface
{
    public function supports(int $fixturesCount): bool
    {
        return 0 < $fixturesCount && $fixturesCount <= 5;
    }

    public function predict(array $scoreTable, int $fixturesCount): array
    {
        $result     = [];
        $teamPoints = $this->calculateTeamPoints($scoreTable);

        foreach ($teamPoints as $team => $points) {

            $winProbability = 0;
            for ($i = max($teamPoints); $i <= max($teamPoints) + $this->maxAvailablePoints($fixturesCount); $i++) {
                $currentReachProbability = $this->getPointsStrictReachProbability($fixturesCount, $points, $i);
                // In order to simplify, assume that if more than one team have the same points, they are all winners
                $otherTeamsNotReachProbability = $this->getOtherTeamsNotReachProbability($fixturesCount, $i + 1, $teamPoints, $team);


                $winProbability+= $currentReachProbability * $otherTeamsNotReachProbability;
            }

            $result[$team] = $winProbability;
        }

        return $result;
    }

    private function getOtherTeamsNotReachProbability(int $fixturesCount, int $pointsNeeded, array $teamPoints, int $team)
    {
        $result = 1;

        foreach ($teamPoints as $otherTeam => $otherTeamPoints) {
            if ($team === $otherTeam) {
                continue;
            }

            $result *= (1 - $this->getPointsReachProbability($fixturesCount, $otherTeamPoints, $pointsNeeded));

        }

        return $result;
    }

    private function getAvailablePoints(int $fixturesCount, int $currentPoints = 0): array
    {
        if ($fixturesCount <= 0) {
            return [];
        }
        $seed = [
            Predictor::LOSE_POINTS,
            Predictor::DRAW_POINTS,
            Predictor::WIN_POINTS
        ];

        $result = $seed;
        foreach ($result as &$item) {
            $item += $currentPoints;
        } unset($item);

        for ($i = 0; $i < $fixturesCount - 1; $i++) {
            $tmp = [];
            foreach ($result as $item) {
                foreach ($seed as $points) {
                    $tmp[] = $item + $points;
                }
            }

            $result = $tmp;
        }

        return $result;
    }

    private function getPointsReachProbability(int $fixturesCount, int $currentPoints, int $pointsNeeded): float
    {
        if ($this->outOfRange($fixturesCount, $currentPoints, $pointsNeeded)) {
            return 0;
        }

        $availablePoints = $this->getAvailablePoints($fixturesCount, $currentPoints);
        $sum = 0;
        foreach ($availablePoints as $point) {
            if ($point >= $pointsNeeded) {
                $sum++;
            }
        }

        return $sum / count($availablePoints);
    }

    private function getPointsStrictReachProbability(int $fixturesCount, int $currentPoints, int $pointsNeeded): float
    {
        if ($this->outOfRange($fixturesCount, $currentPoints, $pointsNeeded)) {
            return 0;
        }

        $availablePoints = $this->getAvailablePoints($fixturesCount, $currentPoints);
        $sum = 0;
        foreach ($availablePoints as $point) {
            if ($point === $pointsNeeded) {
                $sum++;
            }
        }

        return $sum / count($availablePoints);
    }

    private function outOfRange(int $fixturesCount, int $currentPoints, int $pointsNeeded) {
        return $fixturesCount <= 0 || $pointsNeeded > $currentPoints + $this->maxAvailablePoints($fixturesCount);
    }
}
