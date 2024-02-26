<?php

namespace App\Models;

use App\Repositories\HistorySessionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attraction extends Model {
    use SoftDeletes;

    protected $table = 'attractions';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

   // админ аттракциона
    public function User() {
        return $this->belongsTo('App\Models\User');
    }

    // сессии истории аттракциона
    public function hystory_sessions() {
        return $this->hasMany(HistorySessions::class, 'attraction_id', 'id');
    }

    // все сессии аттракциона
    public function sessions_many() {
        return $this->hasMany('App\Models\Session', 'attraction_id', 'id');
    }

    public function session_any() {
        return $this->hasOne('App\Models\Session', 'attraction_id', 'id');
    }

    // локация аттракциона
    public function location() {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

}
