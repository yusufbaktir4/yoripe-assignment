<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Traits\ApiTrait;

class AdminAuth
{
    use ApiTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($this->isAdmin(auth('sanctum')->user())) {
            return $next($request);
        } else {
            return $this->responseError([], 401, "You're not allowed to do this action");
        }
    }
}
