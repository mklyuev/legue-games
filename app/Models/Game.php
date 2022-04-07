<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public bool $timestamps = false;

    protected array $hidden = ['id', 'host_team_id', 'guest_team_id'];
    protected array $with = ['hostTeam', 'guestTeam'];

    public function hostTeam()
    {
        return $this->belongsTo(Team::class, 'host_team_id', 'id');
    }

    public function guestTeam()
    {
        return $this->belongsTo(Team::class, 'guest_team_id', 'id');
    }
}
