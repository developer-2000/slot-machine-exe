<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermissions extends Model {
    use SoftDeletes;

    protected $table = 'role_permissions';
    public $fillable = ['role_id', 'permission_id'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
