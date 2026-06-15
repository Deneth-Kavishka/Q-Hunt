<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['team_id', 'name', 'email'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
