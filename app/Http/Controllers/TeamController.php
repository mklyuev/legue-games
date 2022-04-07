<?php


namespace App\Http\Controllers;

use App\Services\GameService;

class TeamController extends Controller
{
    private GameService $gameService;
    public function __construct(
        GameService $gameService
    )
    {
        $this->gameService = $gameService;
    }

    public function list()
    {
        return [
            'teams' => $this->gameService->getTeams()
        ];
    }
}
