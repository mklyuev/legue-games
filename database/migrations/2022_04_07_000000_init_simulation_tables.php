<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitSimulationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name')->unique();
            $table->integer('attack');
            $table->integer('defence');
            $table->integer('accuracy');
            $table->integer('goalkeeper');
        });

        Schema::create('games', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('week')->index();
            $table->integer('host_team_id');
            $table->integer('guest_team_id');
            $table->integer('host_team_goals')->default(0);
            $table->integer('guest_team_goals')->default(0);
            $table->boolean('is_played')->default(false);

            $table->unique(['host_team_id', 'guest_team_id']);
            $table->foreign('host_team_id')->references('id')->on('teams');
            $table->foreign('guest_team_id')->references('id')->on('teams');
        });

        Schema::create('team_statistics', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('team_id');
            $table->integer('played')->default(0);
            $table->integer('won')->default(0);
            $table->integer('draw')->default(0);
            $table->integer('loss')->default(0);
            $table->integer('goals')->default(0);
            $table->integer('points')->default(0);
            $table->integer('win_percentage')->default(25);

            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
