<?php

namespace App\Http\Middleware;

use App\Helpers\BaseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserAdminRole
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
        if (! Auth::guard('admin')->check()) {
            return redirect()->route(BaseHelper::getAdminPrefix() . '.login');
        }
        return $next($request);
    }
}
