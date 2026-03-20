<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| SGAFA QR — Rutas (solo navegación, sin autenticación por ahora)
|--------------------------------------------------------------------------
*/

// ── Autenticación ───────────────────────────────────────────────
Route::get('/',       fn() => view('auth.login'))->name('login');
Route::get('/login',  fn() => view('auth.login'))->name('login.show');
Route::post('/login', fn() => redirect('/dashboard'))->name('login.post');
Route::get('/logout', fn() => redirect('/'))->name('logout');

// ── Dashboard ───────────────────────────────────────────────────
Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

// ── Activos ─────────────────────────────────────────────────────
Route::prefix('activos')->name('activos.')->group(function () {
    Route::get('/',      fn() => view('activos.index'))->name('index');
    Route::get('/{id}',  fn() => view('activos.show', ['id' => 1]))->name('show');
});

// ── Solicitudes ─────────────────────────────────────────────────
Route::prefix('solicitudes')->name('solicitudes.')->group(function () {
    Route::get('/',                  fn() => redirect('/solicitudes/administrativos'));
    Route::get('/administrativos',   fn() => view('solicitudes.administrativos'))->name('administrativos');
    Route::get('/buzon',             fn() => view('solicitudes.buzon'))->name('buzon');
    Route::get('/nueva',             fn() => view('solicitudes.administrativos'))->name('nueva');
    Route::get('/{id}',              fn() => view('solicitudes.administrativos'))->name('show');
    Route::get('/buzon/{id}',        fn() => view('solicitudes.buzon'))->name('buzon.show');
    Route::get('/buzon/{id}/edit',   fn() => view('solicitudes.buzon'))->name('buzon.edit');
});

// ── Usuarios ────────────────────────────────────────────────────
Route::prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('/',          fn() => view('usuarios.index'))->name('index');
    Route::get('/crear',     fn() => view('usuarios.index'))->name('create');
    Route::get('/{id}',      fn() => view('usuarios.index'))->name('show');
    Route::get('/{id}/edit', fn() => view('usuarios.index'))->name('edit');
});

// ── Reportes ────────────────────────────────────────────────────
Route::get('/reportes', fn() => view('reportes.index'))->name('reportes');

// ── Perfil ──────────────────────────────────────────────────────
Route::get('/perfil',      fn() => view('perfil.index'))->name('perfil');
Route::get('/perfil/edit', fn() => view('perfil.index'))->name('perfil.edit');

// ── Forgot password (placeholder) ──────────────────────────────
Route::get('/forgot-password',  fn() => view('auth.forgot-password'))->name('password.request');
Route::post('/forgot-password', fn() => back()->with('status', 'Enlace enviado.'))->name('password.email');

// ── Notificaciones ──────────────────────────────────────────────
Route::get('/notificaciones',             [NotificationController::class, 'index']);
Route::post('/notificaciones/{id}/leer',  [NotificationController::class, 'marcarLeida']);
Route::post('/notificaciones/leer-todas', [NotificationController::class, 'marcarTodasLeidas']);