<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['stage_id', 'type', 'content', 'options', 'correct_answer', 'hint', 'time_penalty_seconds', 'max_attempts'];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}
