<?php

namespace App\Http\Controllers;

use App\Services\GameService;

class GameController extends Controller
{
    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function currentWeekGames()
    {
        $currentWeek = $this->gameService->getCurrentWeek();
        if ($currentWeek === null) {
            return ['msg' => 'Games not found'];
        }

        $games = $this->gameService->weekGames($currentWeek);

        return ['current_week_games' => $games];
    }

    public function playCurrentWeek()
    {
        $currentWeek = $this->gameService->getCurrentWeek();
        if ($currentWeek === null) {
            return ['msg' => 'Games not found'];
        }

        $this->gameService->playWeekGames($currentWeek);

        return ['msg' => 'Games are played'];
    }

    public function playAllWeeks()
    {
        while ($this->gameService->getCurrentWeek() !== null) {
            $this->gameService->playWeekGames($this->gameService->getCurrentWeek());
        }

        return ['msg' => 'Games are played'];
    }
}
