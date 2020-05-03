<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class Weapp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()){
            return $next($request);
        }

        return Response::json([
            'code' => 0,
            'message' => '没有登录',

        ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }
}
