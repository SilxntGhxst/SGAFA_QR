<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ActivoController extends Controller
{
    public function index(Request $request)
    {
        $apiBase = 'http://host.docker.internal:8080/api';

        // Capturamos la página actual y el límite personalizable
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        try {
            // Pedimos los catálogos para llenar los filtros (Corrigiendo endpoint)
            $catalogos = Http::get("$apiBase/activos/catalogos")->json();
            
            $params = array_filter([
                'search' => $request->search,
                'categoria_id' => $request->categoria_id,
                'estado' => $request->estado,
                'ubicacion_id' => $request->ubicacion_id,
                'limit' => $limit,
                'offset' => $offset
            ], fn($val) => !is_null($val) && $val !== '');
            
            // Pedimos los activos a nuestro super-endpoint reutilizable
            $response = Http::get("$apiBase/activos", $params)->json();
            
            $activos = $response['data'] ?? [];
            $total = $response['total'] ?? 0;
            
        } catch (\Exception $e) {
            $catalogos = ['categorias' => [], 'ubicaciones' => [], 'usuarios' => []];
            $activos = [];
            $total = 0;
        }

        // Pasamos todo a la vista, incluyendo variables para calcular la paginación manual
        return view('activos.index', compact('activos', 'catalogos', 'page', 'limit', 'total'));
    }

    public function store(Request $request)
    {
        $apiBase = 'http://host.docker.internal:8080/api';
        Http::post("$apiBase/activos", $request->all());
        return redirect()->back()->with('success', 'Activo creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $apiBase = 'http://host.docker.internal:8080/api';
        Http::put("$apiBase/activos/$id", $request->all());
        return redirect()->back()->with('success', 'Activo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $apiBase = 'http://host.docker.internal:8080/api';
        Http::delete("$apiBase/activos/$id");
        return redirect()->back()->with('success', 'Activo eliminado correctamente.');
    }
}