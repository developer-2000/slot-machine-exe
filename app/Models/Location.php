<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model {
    use HasFactory;

    protected $table = 'locations';
    protected $guarded = [];
    protected $casts = [
        'country' => 'json',
        'region' => 'json',
        'city' => 'json',
        'street' => 'json',
        'working_hours' => 'json',
    ];

    // аттракционы локации
    public function attractions() {
        return $this->hasMany('App\Models\Attraction', 'location_id', 'id');
    }

    // активные аттракционы локации
    public function activeAttractions() {
        return $this->hasMany('App\Models\Attraction', 'location_id', 'id')
            ->where('activation', 1);
    }

    // статистика игроков на этой локации
    public function statistic() {
        return $this->hasMany(PlayerStatisticLocation::class, 'location_id', 'id');
    }

    // статистика локации
    public function mode_statistic() {
        return $this->hasMany(LocationModeStatistic::class, 'location_id', 'id');
    }

    // лицензия локации
    public function license() {
        return $this->hasOne(License::class, 'location_id', 'id');
    }

    // админ локации
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
