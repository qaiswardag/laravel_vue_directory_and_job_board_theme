<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EnsureHasCorrectRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // current team: Auth::user()->currentTeam
        // users team role for current team: Auth::user()->teamRole(Auth::user()->currentTeam)
        //
        dd(Auth::user()->currentTeam);
        dd(Auth::user()->teamRole(Auth::user()->currentTeam));
        //
        //
        if (true) {
            return Inertia::render("Error", [
                "customError" =>
                    "Check your current Role as your current Role does not allow you to perform this action.",
                "status" => 403,
            ])
                ->toResponse($request)
                ->setStatusCode(403);
        }

        return $next($request);
    }
}
