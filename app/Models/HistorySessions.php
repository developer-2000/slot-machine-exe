<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorySessions extends Model {
    use HasFactory;

    protected $table = 'history_sessions';
    protected $guarded = [];
    protected $casts = [
        'result' => 'json',
    ];

    // выбрать атракцион
    public function attraction() {
        return $this->belongsTo('App\Models\Attraction', 'attraction_id','id');
    }

}
