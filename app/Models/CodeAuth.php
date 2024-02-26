<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeAuth extends Model {
    use HasFactory;

    protected $table = 'code_auths';
    protected $guarded = [];
}
