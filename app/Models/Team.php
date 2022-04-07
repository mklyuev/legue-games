<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public bool $timestamps = false;

    protected array $with = ['statistic'];
    public function statistic()
    {
        return $this->belongsTo(TeamStatistic::class, 'id', 'team_id');
    }
}
