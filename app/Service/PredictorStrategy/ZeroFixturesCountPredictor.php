<?php

namespace App\Service\PredictorStrategy;

class ZeroFixturesCountPredictor extends AbstractPredictor implements PredictorInterface
{
    public function supports(int $fixturesCount): bool
    {
        return $fixturesCount === 0;
    }

    public function predict(array $scoreTable, int $fixturesCount): array
    {
        $teamPoints = $this->calculateTeamPoints($scoreTable);
        $maxPoints = max($teamPoints);

        $result = [];

        foreach ($teamPoints as $team => $points) {
            $result[$team] = (int) ($points === $maxPoints);
        }

        return $result;
    }
}
