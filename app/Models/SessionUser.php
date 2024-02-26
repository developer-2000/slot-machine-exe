<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionUser extends Model {
    use HasFactory;

    protected $table = 'session_users';
    protected $guarded = [];

    // на юзера
    public function user_one() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function sessions() {
        return $this->belongsTo('App\Models\Session', 'session_id', 'id');
    }

}
