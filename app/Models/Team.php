<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['event_id', 'leader_user_id', 'name', 'total_score'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_user_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
