<?php

namespace App\Providers;

use App\Events\GameEnd;
use App\Listeners\UpdateGameStatistic;
use App\Listeners\UpdatePredictions;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        GameEnd::class => [
            UpdateGameStatistic::class,
            UpdatePredictions::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
