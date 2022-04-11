<?php

namespace App\Services;

use App\Models\Game;
use App\Models\TeamStatistic;

class PredictService
{
    /**
     * using bernoulli distribution function
     *
     * @param int $week
     */
    public function updatePredictions(int $week): void
    {
        $highestPoints = $this->getHighestPoints();
        $leftGamesCount = $this->getWeeksCount() - $week;

        $teamStatistics = TeamStatistic::query()
            ->orderBy('points', 'ASC')
            ->get();

        foreach ($teamStatistics as $teamStatistic) {
            if ( $teamStatistic->points === $highestPoints) {
                $pointsToWin = $this->getPreHighestPoints() - $highestPoints;
            } else {
                $pointsToWin = $highestPoints - $teamStatistic->points;
            }
            $gamesHasToBeWin = (int)
                (ceil($pointsToWin / 2) + ceil($leftGamesCount / 2));

            if ($gamesHasToBeWin <= 0) {
                $teamStatistic->win_percentage = 100;
                $teamStatistic->save();
                continue;
            }

            if ($gamesHasToBeWin > $leftGamesCount) {
                $teamStatistic->win_percentage = 0;
                $teamStatistic->save();
                continue;
            }

            $leftGameFactorial = (int) gmp_strval(gmp_fact($leftGamesCount));
            $gameHasToBeWinFactorial = (int) gmp_strval(gmp_fact($gamesHasToBeWin));
            $diffFactorial = (int) gmp_strval(gmp_fact($leftGamesCount - $gamesHasToBeWin));
            $percent = $leftGameFactorial
                / ($gameHasToBeWinFactorial * $diffFactorial)
                * pow(0.5, $leftGamesCount);

            $teamStatistic->win_percentage = $percent * 100;

            $teamStatistic->save();
        }
    }

    private function getWeeksCount()
    {
        return Game::query()
                ->max('week');
    }

    private function getHighestPoints()
    {
        return TeamStatistic::query()
            ->max('points');
    }

    private function getPreHighestPoints()
    {
        return TeamStatistic::query()
            ->select('points')
            ->orderBy('points', 'DESC')
            ->offset(1)
            ->first()
            ->points;
    }
}
