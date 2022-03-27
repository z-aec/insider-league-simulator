<?php

namespace App\Http\Controllers;

use App\Service\FixtureGenerator;
use App\Service\MatchSimulator;
use App\Service\Predictor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $fixtureGenerator;

    private $matchSimulator;

    private $predictor;

    public function __construct(
        FixtureGenerator $fixtureGenerator,
        MatchSimulator $matchSimulator,
        Predictor $predictor
    ) {
        $this->fixtureGenerator = $fixtureGenerator;
        $this->matchSimulator   = $matchSimulator;
        $this->predictor        = $predictor;
    }

    public function generateFixtures(Request $request)
    {
        $request->validate([
            'teams' => 'required|integer|min:2'
        ]);
        $teams = (int) $request->get('teams');

        return [
            'response' => $this->fixtureGenerator->generate($teams)
        ];
    }

    public function simulation(Request $request)
    {
        $request->validate([
            'fixture' => 'required|array',
            'league' => 'required',
        ]);
        $fixture = $request->get('fixture');
        $league   = $request->get('league');
        $teamsStrength = array_column($league['teams'], 'strength');

        $result = $this->matchSimulator->runMatches($fixture, $teamsStrength, $league['strengthAtHome'] ?? 0);

        return [
            'response' => $result
        ];
    }

    public function predict(Request $request)
    {
        $request->validate([
            'scoreTable'   => 'required|array',
            'fixtureCount' => 'required|integer|min:0',
        ]);

        $scoreTable   = $request->get('scoreTable');
        $fixtureCount = (int) $request->get('fixtureCount');

        return [
            'response' => $this->predictor->predict($scoreTable, $fixtureCount),
        ];
    }
}
