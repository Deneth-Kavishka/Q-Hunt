<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = ['team_id', 'stage_id', 'answer_given', 'is_correct'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
