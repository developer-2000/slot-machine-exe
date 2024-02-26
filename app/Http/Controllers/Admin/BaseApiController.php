<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\Admin\BaseRestTrait;
use Illuminate\Http\Request;

/**
 * Class BaseApiController
 * @package App\Http\Controllers\Api
 */
class BaseApiController extends Controller
{
    use BaseRestTrait;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * BaseApiController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth('api')->user();
            return $next($request);
        });
    }
}
