<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEventEnded
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
        if ($request->event && $request->event->has_ended) {
            return $request->expectsJson()
                    ? abort(403, 'This event has ended.')
                    : redirect()->route('home')->with('status', 'event-ended');
        }

        return $next($request);
    }
}
