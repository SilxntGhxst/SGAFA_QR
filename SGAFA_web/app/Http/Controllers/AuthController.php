<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private string $apiBase = 'http://host.docker.internal:8080/api';

    /**
     * POST /login
     * Consume FastAPI /api/auth/login, guarda token y usuario en sesión Laravel.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:1',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Introduce un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        try {
            $response = Http::timeout(10)->post("{$this->apiBase}/auth/login", [
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            if ($response->status() === 401) {
                return back()->withErrors(['email' => 'Correo o contraseña incorrectos.'])->withInput();
            }

            if (!$response->successful()) {
                return back()->withErrors(['email' => 'Error al conectar con el servidor. Intenta de nuevo.'])->withInput();
            }

            $data = $response->json();

            // Guardar token y datos del usuario en sesión Laravel
            session([
                'api_token' => $data['access_token'],
                'auth_user' => $data['user'],
            ]);

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'No se pudo conectar con el servidor de autenticación.'])->withInput();
        }
    }

    /**
     * GET /logout
     * Invalida el token en la API y destruye la sesión Laravel.
     */
    public function logout(Request $request)
    {
        $token = session('api_token');
        if ($token) {
            try {
                Http::timeout(5)
                    ->withToken($token)
                    ->post("{$this->apiBase}/auth/logout");
            } catch (\Exception $_) {
                // Si falla la invalidación en API, igual cerramos sesión local
            }
        }

        $request->session()->flush();
        return redirect('/login');
    }
}
