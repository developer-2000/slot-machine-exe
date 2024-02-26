<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model {
    use HasFactory;

    protected $table = 'licenses';
    protected $guarded = [];
    protected $casts = [
        'request' => 'json',
    ];

    // локация у лицензии
    public function location() {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    // аттракционы лицензии
    public function attractions() {
        return $this->hasMany(Attraction::class, 'location_id', 'location_id');
    }

    // владелец лицензии
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
