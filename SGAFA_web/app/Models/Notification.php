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

    // Sin relación para evitar problemas de tipo UUID vs Integer
    // La relación se resuelve a nivel de sesión en los controllers

}