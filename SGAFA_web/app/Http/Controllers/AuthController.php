<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\FastApiService;

class AuthController extends Controller
{
    protected $api;

    public function __construct(FastApiService $api)
    {
        $this->api = $api;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * POST /register
     * Registra un nuevo usuario forzando el rol de Auditor (3).
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:50',
            'apellidos' => 'required|string|max:100',
            'email'     => 'required|email',
            'password'  => 'required|string|min:6|confirmed',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        try {
            $response = $this->api->guestPost('/auth/register', [
                'nombre'    => $request->nombre,
                'apellidos' => $request->apellidos,
                'email'     => $request->email,
                'password'  => $request->password,
                'rol_id'    => 3, // FORZADO A AUDITOR
            ]);

            if ($response->successful()) {
                return redirect('/login')->with('success', 'Cuenta de Auditor creada exitosamente. Ya puedes iniciar sesión.');
            }

            $error = $response->json()['detail'] ?? 'Error al registrar el usuario.';
            return back()->withErrors(['email' => $error])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'No se pudo conectar con el servidor.'])->withInput();
        }
    }

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
            $response = $this->api->guestPost('/auth/login', [
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
            $user = $data['user'];

            // Restricción de roles para la web: Solo Admin (1) y Auditor (3)
            if ($user['rol_id'] == 2) { // 2 es Resguardante
                return back()->withErrors([
                    'email' => 'Acceso denegado. Esta plataforma es para administradores y auditores. Los resguardantes deben usar la aplicación móvil.'
                ])->withInput();
            }

            // Guardar token y datos del usuario en sesión Laravel
            session([
                'api_token' => $data['access_token'],
                'auth_user' => $user,
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
    public function logout()
    {
        $this->api->post('/auth/logout');
        session()->forget(['api_token', 'auth_user']);
        return redirect('/login')->with('success', 'Sesión cerrada.');
    }

    // ── RECUPERACIÓN DE CONTRASEÑA ──

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $response = $this->api->guestPost('/auth/forgot-password', [
            'email' => $request->email
        ]);

        return back()->with('success', 'Si el correo está registrado, recibirás un código.')
                     ->with('email', $request->email);
    }

    public function showResetPassword()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'codigo' => 'required|size:6',
            'nueva_password' => 'required|min:8',
            'confirm_password' => 'same:nueva_password'
        ]);

        $response = $this->api->guestPost('/auth/reset-password', [
            'email' => $request->email,
            'codigo' => $request->codigo,
            'nueva_password' => $request->nueva_password
        ]);

        if ($response->successful()) {
            return redirect('/login')->with('success', 'Contraseña actualizada. Ya puedes iniciar sesión.');
        }

        return back()->with('error', $response->json()['detail'] ?? 'Código inválido o expirado.');
    }
}
