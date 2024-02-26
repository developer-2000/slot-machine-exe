<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeGeographyDb extends Model {
    use HasFactory;

    protected $table = 'make_geography_dbs';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'country' => 'json',
        'regions' => 'json',
        'cities' => 'json',
    ];
}
