<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerfilController extends Controller
{
    private string $apiBase = 'http://host.docker.internal:8080/api';

    /**
     * Muestra el perfil del usuario autenticado.
     */
    public function index()
    {
        $authUser = session('auth_user', []);
        $token    = session('api_token');

        // Intentar refrescar datos del usuario desde la API
        try {
            $me = Http::timeout(5)
                ->withToken($token)
                ->get("{$this->apiBase}/auth/me")
                ->json();

            if (isset($me['id'])) {
                $authUser = $me;
                session(['auth_user' => $me]);
            }
        } catch (\Exception $e) {
            Log::warning('No se pudo refrescar perfil: ' . $e->getMessage());
        }

        return view('perfil.index', ['user' => $authUser]);
    }

    /**
     * Actualiza el nombre y/o contraseña del usuario.
     */
    public function update(Request $request)
    {
        $token    = session('api_token');
        $authUser = session('auth_user', []);
        $userId   = $authUser['id'] ?? null;

        if (!$userId) {
            return redirect()->back()->with('error', 'Sesión inválida.');
        }

        $data = array_filter([
            'nombre'    => $request->input('nombre'),
            'apellidos' => $request->input('apellidos'),
        ], fn($v) => !is_null($v) && $v !== '');

        if ($request->filled('password') && $request->input('password') === $request->input('password_confirm')) {
            $data['password'] = $request->input('password');
        }

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->put("{$this->apiBase}/usuarios/{$userId}", $data);

            if ($response->successful()) {
                $updated = $response->json()['data'] ?? $authUser;
                session(['auth_user' => array_merge($authUser, $updated)]);
                return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
            }

            return redirect()->back()->with('error', 'Error al actualizar el perfil.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo conectar con el servidor.');
        }
    }
}
