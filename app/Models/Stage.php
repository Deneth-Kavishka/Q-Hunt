<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'location_clue',
        'start_time',
        'end_time',
        'order',
        'points',
        'qr_code_identifier',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
