<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route(config('core.user_prefix') . '.login');
        }

        // Note: vo_sinh table doesn't have Session_login field
        // Session checking logic has been removed as it's not applicable for vo_sinh

        return $next($request);
    }
}
