<?php

namespace App\Service\PredictorStrategy;

interface PredictorInterface
{
    public function supports(int $fixturesCount): bool;

    public function predict(array $scoreTable, int $fixturesCount): array;
}
