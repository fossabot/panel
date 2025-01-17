<?php

namespace Convoy\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorizeProprietaryToken
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
        $secret = $request->header('Authorization');

        if ($secret !== config('external.secret') || strlen(config('external.secret')) === 0 || empty(config('external.secret'))) {
            return new Response([
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
