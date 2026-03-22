<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // La URL base ahora es directamente la de activos
        $apiBase = 'http://host.docker.internal:8080/api/activos';

        try {
            // Todo sale del mismo router centralizado
            $stats = Http::get("$apiBase/stats")->json();
            $catalogos = Http::get("$apiBase/catalogos")->json(); 
            
            $activos = Http::get("$apiBase/", [
                'search' => $request->search,
                'categoria_id' => $request->categoria_id,
                'estado' => $request->estado,
                'limit' => 5 
            ])->json();
            
        } catch (\Exception $e) {
            $stats = ['total_activos' => 0, 'activos_faltantes' => 0, 'solicitudes_pendientes' => 0];
            $catalogos = ['categorias' => [], 'ubicaciones' => []];
            $activos = [];
        }

        $filters = $request->only(['search', 'categoria_id', 'estado']);

        return view('dashboard', compact('stats', 'catalogos', 'activos', 'filters'));
    }
}