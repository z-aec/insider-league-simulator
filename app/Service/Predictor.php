<?php

namespace App\Service;

use App\Service\PredictorStrategy\LargeFixturesCountPredictor;
use App\Service\PredictorStrategy\PredictorInterface;
use App\Service\PredictorStrategy\SmallFixturesCountPredictor;
use App\Service\PredictorStrategy\ZeroFixturesCountPredictor;

class Predictor
{
    const WIN_POINTS  = 3;
    const DRAW_POINTS = 1;
    const LOSE_POINTS = 0;

    /** @var PredictorInterface[]  */
    private $strategies = [];

    public function __construct(
        SmallFixturesCountPredictor $smallFixturesCountPredictor,
        LargeFixturesCountPredictor $largeFixturesCountPredictor,
        ZeroFixturesCountPredictor $zeroFixturesCountPredictor
    ){
        $this->strategies = func_get_args();
    }

    public function predict(array $scoreTable, int $fixturesCount): array
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($fixturesCount)) {
                return $strategy->predict($scoreTable, $fixturesCount);
            }
        }

        throw new \Exception("Prediction strategy not found");
    }
}
