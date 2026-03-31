<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $apiBase = 'http://host.docker.internal:8080/api';

        try {
            // Pedimos los catálogos para llenar los selects del modal de asignación
            $catalogos = Http::get("$apiBase/activos/catalogos")->json();
            
            $params = array_filter([
                'usuario_id' => $request->usuario_id,
                'estado' => $request->estado,
            ], fn($val) => !is_null($val) && $val !== '');
            
            // Pedimos las auditorías al FastAPI
            $response = Http::get("$apiBase/auditorias", $params)->json();
            $auditorias = $response['data'] ?? [];
            
        } catch (\Exception $e) {
            $catalogos = ['categorias' => [], 'ubicaciones' => [], 'usuarios' => []];
            $auditorias = [];
        }

        return view('solicitudes.administrativos', compact('auditorias', 'catalogos'));
    }

    public function store(Request $request)
    {
        $apiBase = 'http://host.docker.internal:8080/api';
        
        $request->validate([
            'ubicacion_id' => 'required',
            'usuario_id' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date'
        ]);

        Http::post("$apiBase/auditorias", [
            'ubicacion_id' => (int) $request->ubicacion_id,
            'usuario_id' => $request->usuario_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin
        ]);
        
        return redirect()->back()->with('success', 'Auditoría programada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $apiBase = 'http://host.docker.internal:8080/api';
        
        $request->validate([
            'ubicacion_id' => 'required',
            'usuario_id' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date'
        ]);

        Http::put("$apiBase/auditorias/$id", [
            'ubicacion_id' => (int) $request->ubicacion_id,
            'usuario_id' => $request->usuario_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin
        ]);
        
        return redirect()->back()->with('success', 'Auditoría actualizada correctamente.');
    }

    public function destroy($id)
    {
        $apiBase = 'http://host.docker.internal:8080/api';
        
        Http::delete("$apiBase/auditorias/$id");
        
        return redirect()->back()->with('success', 'Auditoría eliminada correctamente.');
    }
}
