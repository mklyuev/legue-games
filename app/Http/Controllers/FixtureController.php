<?php


namespace App\Http\Controllers;

use App\Http\Requests\NewFixtures;
use App\Services\FixtureService;

class FixtureController extends Controller
{
    private FixtureService $fixtureService;

    public function __construct(
        FixtureService $fixtureService
    )
    {
        $this->fixtureService = $fixtureService;
    }

    public function createFixtures(NewFixtures $request)
    {
        if ($this->fixtureService->isSimulationStarted()) {
            return [
                'msg' => 'Fixture already created'
            ];
        }

        $this->fixtureService->createTeams($request->get('teams'));
        $this->fixtureService->generateWeekGames();

        return [
            'msg' => 'Fixture created'
        ];
    }

    public function reset()
    {
        $this->fixtureService->restart();

        return [
            'msg' => 'Entries deleted'
        ];
    }
}
