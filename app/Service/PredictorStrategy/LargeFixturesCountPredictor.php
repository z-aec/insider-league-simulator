<?php

namespace App\Service\PredictorStrategy;

class LargeFixturesCountPredictor extends AbstractPredictor implements PredictorInterface
{
    public function supports(int $fixturesCount): bool
    {
        return $fixturesCount > 5;
    }

    public function predict(array $scoreTable, int $fixturesCount): array
    {
        $teamPoints = $this->calculateTeamPoints($scoreTable);
        $maxAvailablePoints = $this->maxAvailablePoints($fixturesCount);
        $maxPoints = max($teamPoints);

        $result = [];
        foreach ($teamPoints as $team => $point) {
            $result[$team] = max(0, $point - $maxPoints + $maxAvailablePoints);
        }
        $sum = array_sum($result);
        $result = array_map(fn($item) => $sum ? ($item / $sum) : 0, $result);

        return $result;
    }
}
