<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception) {

        $data = parent::render($request, $exception);

        // при запросе не авторизованным
        if ($exception instanceof RouteNotFoundException) {
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => config('game.custom_value.ex.8')], 401) :
                response(['code' => 0, 'status' => 'error', 'message' => config('game.custom_value.ex.8')], 401);
        }
        // 1 если нет route для запроса
        elseif($exception instanceof NotFoundHttpException) {
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => config('game.custom_value.ex.9')], 404) :
                response(['code' => 0, 'status' => 'error', 'message' => config('game.custom_value.ex.9')], 404);
        }
        // 2 если нет route для запроса
        elseif ($exception instanceof MethodNotAllowedHttpException) {
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => config('game.custom_value.ex.10')], 400) :
                response(['code' => 0, 'status' => 'error', 'message' => config('game.custom_value.ex.10')], 400);
        }
        // сообщение при превешении обращения к роутам (middle в route)
        elseif($exception instanceof ThrottleRequestsException) {
            $data = $request->expectsJson() ?
                response()->json(['code' => 0, 'status' => 'error', 'message' => config('game.custom_value.ex.11')], 400) :
                response(['code' => 0, 'status' => 'error', 'message' => config('game.custom_value.ex.11')], 400);
        }

    return $data;
    }


}
