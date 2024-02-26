<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationModeStatistic extends Model {
    use HasFactory;

    protected $table = 'location_mode_statistics';
    protected $guarded = [];
    protected $casts = [
        'data' => 'json',
    ];
}
