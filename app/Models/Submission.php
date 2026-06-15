<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['team_id', 'question_id', 'submitted_answer', 'is_correct'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
