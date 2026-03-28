<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ActivoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| SGAFA QR — Rutas Web
|--------------------------------------------------------------------------
*/

// ── Autenticación pública ─────────────────────────────────────────────────
Route::get('/',              fn() => redirect('/login'));
Route::get('/login',         fn() => session('api_token') ? redirect('/dashboard') : view('auth.login'))->name('login');
Route::post('/login',        [AuthController::class, 'login'])->name('login.post');
Route::get('/logout',        [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password',  fn() => view('auth.forgot-password'))->name('password.request');
Route::post('/forgot-password', fn() => back()->with('status', 'Enlace enviado.'))->name('password.email');

// ── Rutas protegidas — requieren sesión con token ─────────────────────────
Route::middleware('auth.token')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activos
    Route::prefix('activos')->name('activos.')->group(function () {
        Route::get('/',          [ActivoController::class, 'index'])->name('index');
        Route::post('/',         [ActivoController::class, 'store'])->name('store');
        Route::put('/{id}',      [ActivoController::class, 'update'])->name('update');
        Route::delete('/{id}',   [ActivoController::class, 'destroy'])->name('destroy');
        Route::get('/{id}',      fn() => view('activos.show', ['id' => 1]))->name('show');
    });

    // Solicitudes / Auditorías
    Route::prefix('solicitudes')->name('solicitudes.')->group(function () {
        Route::get('/',                  fn() => redirect('/solicitudes/administrativos'));
        Route::get('/administrativos',   [AuditoriaController::class, 'index'])->name('administrativos');
        Route::post('/administrativos',  [AuditoriaController::class, 'store'])->name('administrativos.store');
        Route::get('/buzon',             fn() => view('solicitudes.buzon'))->name('buzon');
        Route::get('/nueva',             fn() => view('solicitudes.administrativos'))->name('nueva');
        Route::get('/{id}',              fn() => view('solicitudes.administrativos'))->name('show');
        Route::get('/buzon/{id}',        fn() => view('solicitudes.buzon'))->name('buzon.show');
        Route::get('/buzon/{id}/edit',   fn() => view('solicitudes.buzon'))->name('buzon.edit');
    });

    // Usuarios
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/',          [UsuarioController::class, 'index'])->name('index');
        Route::post('/',         [UsuarioController::class, 'store'])->name('store');
        Route::put('/{id}',      [UsuarioController::class, 'update'])->name('update');
        Route::delete('/{id}',   [UsuarioController::class, 'destroy'])->name('destroy');
        Route::get('/{id}',      [UsuarioController::class, 'index'])->name('show');
    });

    // Reportes
    Route::get('/reportes', fn() => view('reportes.index'))->name('reportes');

    // Notificaciones (AJAX y Vista)
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notificaciones.index');
    Route::get('/notificaciones/todas', [NotificationController::class, 'todas'])->name('notificaciones.todas');
    Route::post('/notificaciones/{id}/leida', [NotificationController::class, 'marcarLeida'])->name('notificaciones.leida');
    Route::post('/notificaciones/todas-leidas', [NotificationController::class, 'marcarTodasLeidas'])->name('notificaciones.todasLeidas');

    // Perfil
    Route::get('/perfil',      [PerfilController::class, 'index'])->name('perfil');
    Route::post('/perfil',     [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/perfil/edit', [PerfilController::class, 'index'])->name('perfil.edit');

    // Notificaciones
    Route::get('/notificaciones',             [NotificationController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leer',  [NotificationController::class, 'marcarLeida'])->name('notificaciones.leer');
    Route::post('/notificaciones/leer-todas', [NotificationController::class, 'marcarTodasLeidas'])->name('notificaciones.leer-todas');
});