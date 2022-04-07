<?php

namespace App\Services;

use App\Events\GameEnd;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class GameService
{
    const GAME_MINUTES = 90;
    const GAME_STEP = 10;

    public function getTeams(): Collection
    {
        return Team::all();
    }

    public function getCurrentWeek()
    {
        return Game::query()
            ->where('is_played', false)
            ->orderBy('week', 'ASC')
            ->limit(1)
            ->value('week');
    }

    public function weekGames(int $week): Collection
    {
        return Game::query()
            ->where('is_played', false)
            ->where('week', $week)
            ->orderBy('week', 'ASC')
            ->get();
    }

    public function playWeekGames(int $week): void
    {
        $games = $this->weekGames($week);

        foreach ($games as $game)
        {
            $this->simulateGame($game);
        }
    }

    private function simulateGame(Game $game)
    {
        $goals[$game->hostTeam->id] = 0;
        $goals[$game->guestTeam->id] = 0;

        $attackTeam = $game->hostTeam;
        $defenseTeam = $game->guestTeam;
        for ($gameTime = 0; $gameTime < self::GAME_MINUTES; $gameTime += self::GAME_STEP)
        {
            // simulate if attack is succeeded
            if ($this->isSituationSucceeded($attackTeam->attack, $defenseTeam->defence))
            {
                // simulate if kick to gates is succeeded
                if ($this->isSituationSucceeded($attackTeam->accuracy, $defenseTeam->goalkeeper)) {
                    $goals[$attackTeam->id] += 1;
                }
            }

            list($attackTeam, $defenseTeam) = [$defenseTeam,$attackTeam];
        }

        $game->setAttribute('host_team_goals', $goals[$game->hostTeam->id]);
        $game->setAttribute('guest_team_goals', $goals[$game->guestTeam->id]);
        $game->setAttribute('is_played', true);

        $game->save();

        GameEnd::dispatch($game);
    }

    /**
     * Inspired by Football, tactics & glory game
     *
     * @param int $attackPoints
     * @param int $defensePoints
     * @return bool
     */
    private function isSituationSucceeded(int $attackPoints, int $defensePoints)
    {
        return rand(1, $attackPoints) > rand(1, $defensePoints);
    }
}
