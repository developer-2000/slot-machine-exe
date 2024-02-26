<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationAchievement extends Model {
    use HasFactory;

    protected $table = 'location_achievements';
    protected $guarded = [];
    protected $casts = [
        'long_liver' => 'json',
        'axes_all_directions' => 'json',
        'access_granted' => 'json',
        'in_love_bullseye' => 'json',
        'in_love_sniper' => 'json',
        'in_love_throw_royal' => 'json',
        'in_love_monopoly' => 'json',
        'hype' => 'json',
        'packed_to_eyeballs' => 'json',
        'who_you' => 'json',
    ];
    protected $dates = [
        'last_update',
    ];
}
