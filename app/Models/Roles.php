<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model {
    use SoftDeletes;

    protected $table = 'roles';

    public $fillable = ['name'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
