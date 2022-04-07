<?php


namespace App\Services;

use App\Models\Game;
use App\Models\Team;
use App\Models\TeamStatistic;

class FixtureService
{
    public function createTeams(array $teams): void
    {
        foreach ($teams as $team)
        {
            $newTeam = new Team();
            $newTeam->setAttribute('name', $team['name']);
            $newTeam->setAttribute('attack', $team['attack']);
            $newTeam->setAttribute('defence', $team['defence']);
            $newTeam->setAttribute('accuracy', $team['accuracy']);
            $newTeam->setAttribute('goalkeeper', $team['goalkeeper']);

            $newTeam->save();
        }
    }

    public function generateWeekGames(): void
    {
        $teams = Team::all();

        $firstGamesOfWeek = [];
        $secondGamesOfWeek = [];
        foreach ($teams as $firstTeamIndex => $firstTeam) {
            foreach ($teams as $secondTeamIndex => $secondTeam) {
                if ($firstTeamIndex >= $secondTeamIndex) {
                    continue;
                }

                $game = new Game();
                $game->setAttribute('host_team_id', $secondTeam->getAttributeValue('id'));
                $game->setAttribute('guest_team_id', $firstTeam->getAttributeValue('id'));
                array_push($firstGamesOfWeek, $game);

                $game = new Game();
                $game->setAttribute('host_team_id', $firstTeam->getAttributeValue('id'));
                $game->setAttribute('guest_team_id', $secondTeam->getAttributeValue('id'));
                array_push($secondGamesOfWeek, $game);
            }
        }

        $secondGamesOfWeek = array_reverse($secondGamesOfWeek);
        foreach ($firstGamesOfWeek as $weekIndex => $game)
        {
            $game->setAttribute('week', $weekIndex);
            $game->save();

            $secondGamesOfWeek[$weekIndex]->setAttribute('week', $weekIndex);
            $secondGamesOfWeek[$weekIndex]->save();
        }
    }

    public function isSimulationStarted(): bool
    {
        return Game::query()->exists();
    }

    public function restart()
    {
        TeamStatistic::query()->delete();
        Game::query()->delete();
        Team::query()->delete();
    }
}
