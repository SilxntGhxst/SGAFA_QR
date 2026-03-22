<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ActivoController extends Controller
{
    public function index(Request $request)
    {
        $apiBase = 'http://host.docker.internal:8080/api';

        // Capturamos la página actual (por defecto la 1)
        $page = $request->input('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        try {
            // Pedimos los catálogos para llenar los filtros
            $catalogos = Http::get("$apiBase/catalogos")->json();
            
            // Pedimos los activos a nuestro super-endpoint reutilizable
            $activos = Http::get("$apiBase/activos", [
                'search' => $request->search,
                'categoria_id' => $request->categoria_id,
                'estado' => $request->estado,
                'limit' => $limit,
                'offset' => $offset
            ])->json();
            
        } catch (\Exception $e) {
            $catalogos = ['categorias' => [], 'ubicaciones' => []];
            $activos = [];
        }

        // Pasamos todo a la vista, incluyendo variables para calcular la paginación manual
        return view('activos.index', compact('activos', 'catalogos', 'page', 'limit'));
    }
}