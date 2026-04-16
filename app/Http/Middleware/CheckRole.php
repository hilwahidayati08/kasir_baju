<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $roles = explode('|', $role);
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
