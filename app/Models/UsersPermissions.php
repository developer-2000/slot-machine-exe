<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersPermissions extends Model {
    use SoftDeletes;

    protected $table = 'users_permissions';
    public $fillable = ['user_id', 'permission_id'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
