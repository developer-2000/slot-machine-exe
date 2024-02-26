<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModeStatistic extends Model {
    use HasFactory;

    protected $table = 'user_mode_statistics';
    protected $guarded = [];
    protected $casts = [
        'data' => 'json',
    ];
}
