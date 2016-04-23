<?php

namespace App\Http\Middleware;

use App\Access;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

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
        $agent = new Agent();
        Session::put('isMobile', $agent->isMobile());

        if (env('APP_DEBUG') != true) {
            $access = new Access();

            $user = (Auth::user() != null) ? Auth::user()->id : 'guest';

            $access->date = Carbon::now();
            $access->user_id = $user;
            $access->ip = $request->ip();
            $access->route = $request->path();
            $access->type = $request->getMethod();
            $access->browser = $request->header('User-Agent');
            $access->action = $request->getMethod() == 'GET' ? 'show' : 'save';

            $access->save();
        }

        return $next($request);
    }
}
