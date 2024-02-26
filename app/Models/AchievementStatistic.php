<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementStatistic extends Model {
    use HasFactory;

    protected $table = 'achievement_statistics';
    protected $guarded = [];
    protected $casts = [
        'data' => 'json',
    ];
}


