<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api.key')->group(function () {
    Route::post('/fixtures', 'App\Http\Controllers\FixtureController@createFixtures');

    Route::get('/teams', 'App\Http\Controllers\TeamController@list');
    Route::get('/week', 'App\Http\Controllers\GameController@currentWeekGames');

    Route::post('/week/play', 'App\Http\Controllers\GameController@playCurrentWeek');
    Route::post('/week/play-all', 'App\Http\Controllers\GameController@playAllWeeks');

    Route::post('/reset', 'App\Http\Controllers\FixtureController@reset');
});

