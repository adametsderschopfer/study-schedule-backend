<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Requests\ExternalAuthRequest;

class HasAccount
{
    public function __construct(ExternalAuthRequest $externalAuthRequest) {
        $this->externalAuthRequest = $externalAuthRequest;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->externalAuthRequest->send()) {
            abort(401);
        }

        return $next($request);
    }
}
