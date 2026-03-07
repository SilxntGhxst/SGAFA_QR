<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Devuelve las notificaciones del usuario en JSON (para el dropdown)
    public function index()
    {
        $notifications = Notification::delUsuario()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $noLeidas = Notification::delUsuario()->noLeidas()->count();

        return response()->json([
            'notifications' => $notifications,
            'no_leidas'     => $noLeidas,
        ]);
    }

    // Marca una notificación como leída
    public function marcarLeida($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->update(['leida' => true]);

        return response()->json(['ok' => true]);
    }

    // Marca todas como leídas
    public function marcarTodasLeidas()
    {
        Notification::delUsuario()->noLeidas()->update(['leida' => true]);

        return response()->json(['ok' => true]);
    }
}