<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerStatisticLocation extends Model {
    use HasFactory;


    protected $table = 'player_statistic_locations';
    protected $guarded = [];
    protected $casts = [
        'statistic' => 'json',
    ];
}
