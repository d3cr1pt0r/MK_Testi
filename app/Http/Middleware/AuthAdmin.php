<?php

namespace MKTests\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AuthAdmin
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->user() != null && $this->auth->user()->user_type != 0)
            return redirect()->guest('admin/login');
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }

        return $next($request);
    }
}
