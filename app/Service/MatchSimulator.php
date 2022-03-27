<?php

namespace App\Service;

class MatchSimulator
{
    const WIN  = 1;
    const DRAW = 0;
    const LOSE = -1;

    public function runMatches(array $fixture, array $teamsStrength = [], int $strengthAtHome = 1): array
    {
        $result = [];
        foreach ($fixture as $match => [$homeTeam, $guestTeam]) {
            $homeTeamStrength = $teamsStrength[$homeTeam] ?? 1 + $strengthAtHome;
            $guestTeamStrength = $teamsStrength[$guestTeam] ?? 1;

            $result[$match] = $this->getMatchResult($homeTeamStrength, $guestTeamStrength);
        }

        return $result;
    }

    protected function getMatchResult(int $homeTeamStrength, int $guestTeamStrength): array
    {
        $probabilitySpace = $this->getProbabilitySpace($homeTeamStrength, $guestTeamStrength);
        $matchResult = mt_rand() / mt_getrandmax();
        if ($matchResult < $probabilitySpace[0]) {
            return [self::WIN, self::LOSE];
        } elseif ($matchResult < $probabilitySpace[1]) {
            return [self::LOSE, self::WIN];
        }

        return [self::DRAW, self::DRAW];
    }

    protected function getProbabilitySpace(int $homeTeamStrength, int $guestTeamStrength): array
    {
        $homeTeamCoef = $homeTeamStrength / ($homeTeamStrength + $guestTeamStrength);
        $guestTeamCoef = 1 - $homeTeamCoef;
        $delta = abs($homeTeamCoef - $guestTeamCoef);
        $homeLoseProbability = 1/3 * (1 - $delta);
        $homeWinProbability = $homeTeamCoef * (1 - $homeLoseProbability);

        return [$homeWinProbability, $homeWinProbability + $homeLoseProbability];
    }
}
