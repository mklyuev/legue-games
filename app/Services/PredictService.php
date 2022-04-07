<?php

namespace App\Services;

use App\Models\Game;
use App\Models\TeamStatistic;

class PredictService
{
    public function updatePredictions(int $week): void
    {
        $highestPoints = $this->getHighestPoints();
        $leftGamesCount = $this->getWeeksCount() - $week;

        $teamStatistics = TeamStatistic::query()
            ->orderBy('points', 'ASC')
            ->get();

        $leftPercentage = 100;
        foreach ($teamStatistics as $teamStatistic) {
            $pointsToWin = $highestPoints - $teamStatistic->points;
            $gamesHasToBeWin = ceil($pointsToWin / 2);

            if ($gamesHasToBeWin == 0) {
                $teamStatistic->win_percentage = $leftPercentage;
                $teamStatistic->save();
                continue;
            }

            if ($gamesHasToBeWin > $leftGamesCount) {
                $teamStatistic->win_percentage = 0;
                $teamStatistic->save();
                continue;
            }

            $gamePercentageStep = pow(2, $leftGamesCount);
            if ($gamesHasToBeWin == $leftGamesCount) {
                $teamStatistic->win_percentage = $gamesHasToBeWin / ($gamePercentageStep) * 25;
            } else {
                $teamStatistic->win_percentage = (1 - ($gamesHasToBeWin / ($gamePercentageStep))) * 25;
            }
            $teamStatistic->save();
            $leftPercentage -= $teamStatistic->win_percentage;
        }
    }

    private function getWeeksCount()
    {
        return Game::query()
                ->max('week') + 1;
    }

    private function getHighestPoints()
    {
        return TeamStatistic::query()
            ->max('points');
    }
}
