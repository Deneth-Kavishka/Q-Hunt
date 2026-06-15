<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = ['team_id', 'current_stage_id', 'completed_at', 'earned_points', 'is_revoked'];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'is_revoked' => 'boolean',
        ];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function currentStage()
    {
        return $this->belongsTo(Stage::class, 'current_stage_id');
    }
}
