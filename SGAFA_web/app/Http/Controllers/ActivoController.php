<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ActivoController extends Controller
{
    private string $apiBase = 'http://host.docker.internal:8080/api';

    public function index(Request $request)
    {
        $limit  = $request->input('limit', 10);
        $page   = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        try {
            $catalogos = Http::timeout(5)->get("{$this->apiBase}/activos/catalogos")->json();

            $params = array_filter([
                'search'       => $request->search,
                'categoria_id' => $request->categoria_id,
                'estado'       => $request->estado,
                'ubicacion_id' => $request->ubicacion_id,
                'limit'        => $limit,
                'offset'       => $offset,
            ], fn($val) => !is_null($val) && $val !== '');

            $response = Http::timeout(5)->get("{$this->apiBase}/activos", $params)->json();
            $activos  = $response['data']  ?? [];
            $total    = $response['total'] ?? 0;

        } catch (\Exception $e) {
            $catalogos = ['categorias' => [], 'ubicaciones' => [], 'usuarios' => []];
            $activos   = [];
            $total     = 0;
        }

        return view('activos.index', compact('activos', 'catalogos', 'page', 'limit', 'total'));
    }

    public function store(Request $request)
    {
        // Campos de texto que van a la API
        $data = $request->only([
            'nombre', 'descripcion', 'categoria_id',
            'ubicacion_id', 'usuario_responsable_id', 'estado',
        ]);

        // Subir foto a Laravel Storage y enviar URL a la API
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $path = $request->file('foto')->store('activos', 'public');
            $data['foto'] = asset('storage/' . $path);
        }

        // Limpiar campos vacíos
        $data = array_filter($data, fn($v) => !is_null($v) && $v !== '');

        $response = Http::timeout(10)->post("{$this->apiBase}/activos", $data);

        if (!$response->successful()) {
            return redirect()->back()
                ->with('error', 'Error al crear: ' . $response->body())
                ->withInput();
        }

        return redirect()->back()->with('success', 'Activo creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->only([
            'nombre', 'descripcion', 'categoria_id',
            'ubicacion_id', 'usuario_responsable_id', 'estado',
        ]);

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $path = $request->file('foto')->store('activos', 'public');
            $data['foto'] = asset('storage/' . $path);
        }

        $data = array_filter($data, fn($v) => !is_null($v) && $v !== '');

        Http::timeout(10)->put("{$this->apiBase}/activos/{$id}", $data);

        return redirect()->back()->with('success', 'Activo actualizado correctamente.');
    }

    public function destroy($id)
    {
        Http::timeout(5)->delete("{$this->apiBase}/activos/{$id}");
        return redirect()->back()->with('success', 'Activo eliminado correctamente.');
    }
}
