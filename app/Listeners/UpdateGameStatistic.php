<?php

namespace App\Listeners;

use App\Events\GameEnd;
use App\Models\Game;
use App\Models\Team;
use App\Models\TeamStatistic;
use App\Services\PredictService;

class UpdateGameStatistic
{
    const POINTS_FOR_WIN = 2;
    const POINTS_FOR_DRAW = 1;

    private PredictService $predictService;

    /**
     * Create the event listener.
     *
     * @param PredictService $predictService
     */
    public function __construct(PredictService $predictService)
    {
        $this->predictService = $predictService;
    }

    /**
     * Handle the event.
     *
     * @param GameEnd $gameEnd
     * @return void
     */
    public function handle(GameEnd $gameEnd)
    {
        $hostTeamStatistic = $this->findOrCreateTeamStatistic($gameEnd->game->hostTeam);
        $guestTeamStatistic = $this->findOrCreateTeamStatistic($gameEnd->game->guestTeam);

        $hostTeamPoints = $this->calculateHostTeamPoints($gameEnd->game);
        $guestTeamPoints = $this->calculateGuestTeamPoints($gameEnd->game);

        $hostTeamStatistic->played += 1;
        $hostTeamStatistic->won += (int) ($hostTeamPoints === self::POINTS_FOR_WIN);
        $hostTeamStatistic->draw += (int) ($hostTeamPoints === self::POINTS_FOR_WIN);
        $hostTeamStatistic->loss += (int) ($hostTeamPoints === 0);
        $hostTeamStatistic->points += (int) ($hostTeamPoints);
        $hostTeamStatistic->goals += (int) ($gameEnd->game->hostTeamGoals);

        $guestTeamStatistic->played += 1;
        $guestTeamStatistic->won += (int) ($guestTeamPoints === self::POINTS_FOR_WIN);
        $guestTeamStatistic->draw += (int) ($guestTeamPoints === self::POINTS_FOR_WIN);
        $guestTeamStatistic->loss += (int) ($guestTeamPoints === 0);
        $guestTeamStatistic->points += (int) ($guestTeamPoints);
        $guestTeamStatistic->goals += (int) ($gameEnd->game->guestTeamGoals);

        $hostTeamStatistic->save();
        $guestTeamStatistic->save();
    }

    private function calculateHostTeamPoints(Game $game)
    {
        switch (true) {
            case ($game->host_team_goals > $game->guest_team_goals):
                return self::POINTS_FOR_WIN;
            case ($game->host_team_goals == $game->guest_team_goals):
                return self::POINTS_FOR_DRAW;
            default:
                return 0;
        }
    }

    private function calculateGuestTeamPoints(Game $game)
    {
        switch (true) {
            case ($game->guest_team_goals > $game->host_team_goals):
                return self::POINTS_FOR_WIN;
            case ($game->host_team_goals == $game->guest_team_goals):
                return self::POINTS_FOR_DRAW;
            default:
                return 0;
        }
    }

    private function findOrCreateTeamStatistic(Team $team)
    {
        $teamStatistic = TeamStatistic::find($team->id);

        if (is_null($teamStatistic)) {
            $teamStatistic = new TeamStatistic();
            $teamStatistic->setAttribute('team_id', $team->id);
        }

        return $teamStatistic;
    }
}
