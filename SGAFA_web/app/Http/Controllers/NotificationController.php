<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Vista de todas las notificaciones (Full page).
     */
    public function todas()
    {
        $userId = session('auth_user.id');
        if (!$userId) return redirect('/login');

        $notificaciones = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notificaciones.index', compact('notificaciones'));
    }

    /**
     * Devuelve las notificaciones del usuario autenticado (identificado por sesión).
     */
    public function index()
    {
        $userId = session('auth_user.id');

        if (!$userId) {
            return response()->json(['notifications' => [], 'no_leidas' => 0]);
        }

        try {
            $notifications = Notification::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get();

            $noLeidas = Notification::where('user_id', $userId)
                ->where('leida', false)
                ->count();

            return response()->json([
                'notifications' => $notifications,
                'no_leidas'     => $noLeidas,
            ]);
        } catch (\Exception $e) {
            Log::warning('Error cargando notificaciones: ' . $e->getMessage());
            return response()->json(['notifications' => [], 'no_leidas' => 0]);
        }
    }

    /**
     * Marca una notificación como leída.
     */
    public function marcarLeida($id)
    {
        $userId = session('auth_user.id');
        if (!$userId) return response()->json(['ok' => false], 401);

        try {
            $notification = Notification::where('id', $id)
                ->where('user_id', $userId)
                ->firstOrFail();
            $notification->update(['leida' => true]);
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false], 404);
        }
    }

    /**
     * Marca todas como leídas.
     */
    public function marcarTodasLeidas()
    {
        $userId = session('auth_user.id');
        if (!$userId) return response()->json(['ok' => false], 401);

        Notification::where('user_id', $userId)
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['ok' => true]);
    }

    /**
     * Crea una notificación interna (usable desde otros controllers o API webhooks).
     */
    public static function crear(string $userId, string $tipo, string $titulo, string $mensaje, string $icono = 'bell', string $color = 'blue', ?string $url = null): void
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'tipo'    => $tipo,
                'titulo'  => $titulo,
                'mensaje' => $mensaje,
                'icono'   => $icono,
                'color'   => $color,
                'url'     => $url,
                'leida'   => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creando notificación: ' . $e->getMessage());
        }
    }
}