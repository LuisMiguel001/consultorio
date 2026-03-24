<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $key = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('error', 'Demasiados intentos. Intenta en 1 minuto.');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            RateLimiter::clear($key); // limpiar intentos si login exitoso

            $request->session()->regenerate();

            if (!Auth::user()->activo) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Tu cuenta está desactivada.'
                ]);
            }

            return redirect()->route('pacientes.inicio');
        }

        RateLimiter::hit($key, 60); // bloquear por 60 segundos

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
