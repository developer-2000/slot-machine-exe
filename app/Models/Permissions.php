<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissions extends Model {
    use SoftDeletes;

    protected $table = 'permissions';
    public $fillable = ['name'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
