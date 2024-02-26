<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable {
    use Notifiable, HasApiTokens, SoftDeletes;

    protected $guarded = [];
    protected $hidden = [ 'password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'country' => 'json',
        'region' => 'json',
        'city' => 'json',
        'statistic' => 'json',
    ];
    protected $dates = ['deleted_at', 'date_of_birth'];

    // >>>
    public function roles() {
        return $this->belongsToMany('App\Models\Roles', 'users_roles', 'user_id', 'role_id')
            ->withPivot('id');
    }

    // >>>
    // все аттракционы
    public function owner_attraction() {
        return $this->hasMany('App\Models\Attraction', 'user_id', 'id');
    }

    // >>>
    // один атракцион
    public function attrOne() {
        return $this->hasOne('App\Models\Attraction', 'user_id', 'id');
    }

    // >>>
    // Текущие Сессии игр
    public function session_user() {
        return $this->hasMany('App\Models\SessionUser', 'user_id', 'id');
    }

    // >>>
    // История Сессий игр
    public function history_session_user() {
        return $this->hasMany(HistorySessionUsers::class, 'user_id', 'id');
    }

    // >>>
    // Последняя игра игрока
    public function last_game_user() {
        return $this->hasOne(HistorySessionUsers::class, 'user_id', 'id')
            ->orderBy('created', 'DESC');
    }

    // >>>
    // локации и аттракционы администратора
    public function location_user() {
        return $this->hasMany('App\Models\Location', 'user_id', 'id')
            ->with('attractions');
    }

    // >>>
    // картинки юзера
    public function user_image() {
        return $this->hasOne(UserImage::class, 'user_id', 'id');
    }

    // >>>
    // лицензия админа
    public function license() {
        return $this->hasMany(License::class, 'user_id', 'id');
    }

    // >>> при создании юзера автоматом формирует в смежной таблице запись для этого юзера
    protected static function boot() {
        parent::boot();
        static::created(function (User $model) {
            $config = config('game.user_images');
            // назначить юзеру колекцию рамок
            $model->user_image()->create([
                'avatar_image' => array_column($config['avatar_image']['free'], 'id'),
                'border_image' => array_column($config['border_image']['free'], 'id'),
                'change_image' => array_column($config['change_image']['free'], 'id'),
                'impact_image' => array_column($config['impact_image']['free'], 'id'),
                'victory_image' => array_column($config['victory_image']['free'], 'id'),
            ]);
        });
    }

    // >>>
    // вернет true / false если есть такой достуа
    public function canDo($access) {
        $user = auth()->user();

        if (!is_null($user)) {
            if (isset($user->access)){
                return (strpos(implode(", ", $user->access), $access) !== false) ? true : false;
            }
        }

    return false;
    }

}
