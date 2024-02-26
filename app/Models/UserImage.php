<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model {
    use HasFactory;

    protected $table = 'user_images';
    protected $guarded = [];
    protected $casts = [
        'border_image' => 'json',
        'avatar_image' => 'json',
        'victory_image' => 'json',
        'impact_image' => 'json',
        'change_image' => 'json',
    ];

}
