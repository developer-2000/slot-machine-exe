<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersRoles extends Model {
    use SoftDeletes;

    protected $table = 'users_roles';
    public $fillable = ['user_id', 'role_id'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];

    // лицензия администратора
    public function license() {
        return $this->hasOne(License::class, 'user_id', 'user_id');
    }

    // локация администратора
    public function location() {
        return $this->hasOne(Location::class, 'user_id', 'user_id');
    }

}
