<?php

namespace App\Http\Middleware;

use App\Access;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $access = new Access();

        $user = (Auth::user() != null) ? Auth::user()->id : 'guest';

        $access->date = Carbon::now();
        $access->user_id = $user;
        $access->ip = $request->ip();
        $access->route = $request->path();
        $access->type = $request->getMethod();
        $access->action = $request->getMethod() == 'GET' ? 'show' : 'save';

        $access->save();

        return $next($request);
    }
}