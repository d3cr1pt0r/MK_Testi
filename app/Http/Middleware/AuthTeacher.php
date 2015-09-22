<?php

namespace MKTests\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AuthTeacher
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('teachers/checkpoint');
            }
        }

        return $next($request);
    }
}
