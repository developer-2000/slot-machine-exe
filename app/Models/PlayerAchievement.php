<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerAchievement extends Model {
    use HasFactory;

    protected $table = 'player_achievements';
    protected $guarded = [];

}
