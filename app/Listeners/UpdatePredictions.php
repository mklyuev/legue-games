<?php

namespace App\Listeners;

use App\Events\GameEnd;
use App\Models\Game;
use App\Services\PredictService;

class UpdatePredictions
{
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
        $this->predictService->updatePredictions($gameEnd->game->week);
    }
}
