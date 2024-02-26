<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model {
    use HasFactory;

    protected $table = 'achievements';
    protected $guarded = [];

    // связь с типами достижений игрока
    public function types_user() {
        return $this->hasMany(PlayerAchievement::class, 'achievement_id', 'id');
    }
}
