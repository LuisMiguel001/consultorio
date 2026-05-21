<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoExpiradoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (
            $user &&
            $user->es_demo &&
            $user->demo_expira_en &&
            now()->greaterThan($user->demo_expira_en)
        ) {

            Auth::logout();

            return redirect('/')
                ->with('error',
                    'Tu demo ha expirado.');
        }

        return $next($request);
    }
}
