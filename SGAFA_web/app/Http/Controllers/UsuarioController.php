<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    private string $apiBase = 'http://host.docker.internal:8080/api';

    /**
     * Lista todos los usuarios desde la API.
     */
    public function index(Request $request)
    {
        $token = session('api_token');

        try {
            $response = Http::timeout(5)
                ->withToken($token)
                ->get("{$this->apiBase}/usuarios")
                ->json();

            $usuarios = $response['data'] ?? [];
            $total    = $response['total'] ?? count($usuarios);

            // Obtener roles disponibles para los selects
            $roles = [
                ['id' => 1, 'nombre' => 'admin'],
                ['id' => 2, 'nombre' => 'resguardante'],
                ['id' => 3, 'nombre' => 'auditor'],
            ];

            // Intentar cargar roles desde la API
            try {
                $rolesResp = Http::timeout(5)
                    ->withToken($token)
                    ->get("{$this->apiBase}/varios/roles")
                    ->json();
                if (!empty($rolesResp)) $roles = $rolesResp;
            } catch (\Exception $_) {}

        } catch (\Exception $e) {
            Log::error('Error cargando usuarios: ' . $e->getMessage());
            $usuarios = [];
            $total    = 0;
            $roles    = [];
        }

        return view('usuarios.index', compact('usuarios', 'total', 'roles'));
    }

    /**
     * Crea un nuevo usuario via la API.
     */
    public function store(Request $request)
    {
        $token = session('api_token');

        $request->validate([
            'nombre'    => 'required|string',
            'apellidos' => 'required|string',
            'email'     => 'required|email',
            'password'  => 'required|string|min:6',
            'rol_id'    => 'required|integer',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'email.required'     => 'El correo es obligatorio.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $data = $request->only(['nombre', 'apellidos', 'email', 'password', 'rol_id']);

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->post("{$this->apiBase}/usuarios", $data);

            if (!$response->successful()) {
                $body = $response->json();
                $errorMsg = $body['detail'] ?? $body['message'] ?? 'Error al crear el usuario.';
                return redirect()->back()->with('error', $errorMsg)->withInput();
            }

            return redirect()->back()->with('success', 'Usuario creado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo conectar con el servidor.')->withInput();
        }
    }

    /**
     * Actualiza un usuario via la API.
     */
    public function update(Request $request, $id)
    {
        $token = session('api_token');

        $data = array_filter($request->only(['nombre', 'apellidos', 'email', 'rol_id', 'password']),
            fn($v) => !is_null($v) && $v !== '');

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->put("{$this->apiBase}/usuarios/{$id}", $data);

            if (!$response->successful()) {
                $body = $response->json();
                $errorMsg = $body['detail'] ?? $body['message'] ?? 'Error al actualizar el usuario.';
                return redirect()->back()->with('error', $errorMsg);
            }

            return redirect()->back()->with('success', 'Usuario actualizado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo conectar con el servidor.');
        }
    }

    /**
     * Elimina un usuario via la API.
     */
    public function destroy($id)
    {
        $token = session('api_token');
        try {
            Http::timeout(5)->withToken($token)->delete("{$this->apiBase}/usuarios/{$id}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar el usuario.');
        }
        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }
}
