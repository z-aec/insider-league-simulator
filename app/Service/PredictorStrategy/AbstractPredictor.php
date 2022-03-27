<?php

namespace App\Service\PredictorStrategy;

use App\Service\Predictor;

class AbstractPredictor
{
    protected function calculatePoints(array $teamScore): int
    {
        return $teamScore['won'] * Predictor::WIN_POINTS + $teamScore['drawn'] * Predictor::DRAW_POINTS;
    }

    protected function calculateTeamPoints(array $scoreTable): array
    {
        $result = [];

        foreach ($scoreTable as $team => $score) {
            $result[$team] = $this->calculatePoints($score);
        }

        return $result;
    }

    protected function maxAvailablePoints(int $fixturesCount): int
    {
        return $fixturesCount * Predictor::WIN_POINTS;
    }
}
