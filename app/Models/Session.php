<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model {
    use HasFactory;

    protected $table = 'sessions';
    protected $guarded = [];

// цепочка от игрока до аттракциона
// $user_attraction = User::with('session_user.sessions.attraction')->find(3)->toArray();

    // игроки сессии
    public function sess_user_many() {
        return $this->hasMany('App\Models\SessionUser', 'session_id', 'id');
    }

    // аттракцион сессии
    public function attraction() {
        return $this->belongsTo('App\Models\Attraction', 'attraction_id','id');
    }

}
