<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'mensaje',
        'icono',
        'color',
        'url',
        'leida',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: solo no leídas
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    // Scope: del usuario autenticado
    public function scopeDelUsuario($query)
    {
        return $query->where('user_id', auth()->id());
    }
}