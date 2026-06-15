<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'start_time', 'end_time', 'is_active', 'completion_message', 'is_published', 'push_leaderboard_at'];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_active' => 'boolean',
            'is_published' => 'boolean',
            'push_leaderboard_at' => 'datetime',
        ];
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
