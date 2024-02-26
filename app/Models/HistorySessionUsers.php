<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorySessionUsers extends Model {
    use HasFactory;

    protected $casts = [
        'result' => 'json',
    ];
    protected $table = 'history_session_users';
    protected $guarded = [];

    public function session_history() {
        return $this->hasOne(HistorySessions::class, 'session_id', 'session_id');
    }

}
