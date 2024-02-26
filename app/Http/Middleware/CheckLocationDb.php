<?php

namespace App\Http\Middleware;

use App\Models\MakeGeographyDb;
use App\Services\MakeLocationDbServices;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckLocationDb {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        Log::alert('CheckLocationDb');

        // сформировать в базе записи локации города , регионы, страны
        if (!$coll = MakeGeographyDb::find(1)) {
            (new MakeLocationDbServices())->allCountry();
        }

        return $next($request);
    }
}
