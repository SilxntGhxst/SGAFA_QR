<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware que protege rutas que requieren autenticación mediante API token.
 * Si no existe un token guardado en sesión → redirige al login.
 */
class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('api_token')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión para continuar.');
        }

        return $next($request);
    }
}
