<?php

namespace App\Http\Middleware;

use Closure;
use App\Support\Code;
use Illuminate\Support\Facades\Auth;

class AuthUserToken
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
        
        if (Auth::guard('users')->guest()) {
            return response()->json(['code' => Code::ERR_HTTP_UNAUTHORIZED,'msg' => '请登录']);
        }
        return $next($request);
    }
}
