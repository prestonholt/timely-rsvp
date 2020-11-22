<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInviteExpired
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
        if (!$request->user()->getInviteForEvent($request->event)->can_view) {
            return $request->expectsJson()
                    ? abort(403, 'This invite has expired.')
                    : redirect()->route('home')->with('status', 'invite-expired');
        }

        return $next($request);
    }
}
