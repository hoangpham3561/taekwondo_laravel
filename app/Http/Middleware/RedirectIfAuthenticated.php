<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use App\Helpers\BaseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'admin') {
                    return redirect()->route(BaseHelper::getAdminPrefix() . '.dashboard');
                }
                if ($guard === 'web') {
                    return redirect()->route(BaseHelper::getUserPrefix() . '.index');
                }
            }
        }

        return $next($request);
    }
}
