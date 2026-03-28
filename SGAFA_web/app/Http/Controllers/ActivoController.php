<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ActivoController extends Controller
{
    private string $apiBase = 'http://host.docker.internal:8080/api';

    public function index(Request $request)
    {
        $limit  = $request->input('limit', 10);
        $page   = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        try {
            $token     = session('api_token');
            $catalogos = Http::timeout(5)
                ->withToken($token)
                ->get("{$this->apiBase}/activos/catalogos")
                ->json();

            $params = array_filter([
                'search'       => $request->search,
                'categoria_id' => $request->categoria_id,
                'estado'       => $request->estado,
                'ubicacion_id' => $request->ubicacion_id,
                'limit'        => $limit,
                'offset'       => $offset,
            ], fn($val) => !is_null($val) && $val !== '');

            $response = Http::timeout(5)
                ->withToken($token)
                ->get("{$this->apiBase}/activos", $params)
                ->json();

            $activos  = $response['data']  ?? [];
            $total    = $response['total'] ?? 0;

        } catch (\Exception $e) {
            Log::error('Error cargando activos: ' . $e->getMessage());
            $catalogos = ['categorias' => [], 'ubicaciones' => [], 'usuarios' => []];
            $activos   = [];
            $total     = 0;
        }

        return view('activos.index', compact('activos', 'catalogos', 'page', 'limit', 'total'));
    }

    public function store(Request $request)
    {
        $token = session('api_token');

        // Resolver catálogos nuevos antes de enviar a la API
        $categoriaId = $this->resolveCatalog($request, 'categoria_id', 'nueva_categoria', "{$this->apiBase}/activos/catalogos/categoria", 'categoria', $token);
        $ubicacionId = $this->resolveCatalog($request, 'ubicacion_id', 'nueva_ubicacion', "{$this->apiBase}/activos/catalogos/ubicacion", 'nombre', $token);
        $usuarioId   = $this->resolveCatalog($request, 'usuario_responsable_id', 'nuevo_usuario_nombre', "{$this->apiBase}/usuarios/rapido", 'nombre', $token);

        $data = [
            'nombre'                 => $request->input('nombre'),
            'descripcion'            => $request->input('descripcion'),
            'categoria_id'           => $categoriaId,
            'ubicacion_id'           => $ubicacionId,
            'usuario_responsable_id' => $usuarioId,
            'estado'                 => $request->input('estado'),
        ];

        // Subir foto a Laravel Storage y construir URL accesible
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $path = $request->file('foto')->store('activos', 'public');
            $data['foto'] = '/storage/' . $path;
        }

        // Limpiar campos vacíos
        $data = array_filter($data, fn($v) => !is_null($v) && $v !== '');

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->post("{$this->apiBase}/activos", $data);

            if (!$response->successful()) {
                $body = $response->json();
                $errorMsg = $body['detail'] ?? $body['message'] ?? $response->body();
                Log::error('Error al crear activo: ' . $errorMsg);
                return redirect()->back()
                    ->with('error', 'Error al crear activo: ' . $errorMsg)
                    ->withInput();
            }

            return redirect()->back()->with('success', 'Activo creado correctamente.');

        } catch (\Exception $e) {
            Log::error('Excepción al crear activo: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'No se pudo conectar con el servidor. Intenta de nuevo.')
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $token = session('api_token');

        $categoriaId = $this->resolveCatalog($request, 'categoria_id', 'nueva_categoria', "{$this->apiBase}/activos/catalogos/categoria", 'categoria', $token);
        $ubicacionId = $this->resolveCatalog($request, 'ubicacion_id', 'nueva_ubicacion', "{$this->apiBase}/activos/catalogos/ubicacion", 'nombre', $token);
        $usuarioId   = $this->resolveCatalog($request, 'usuario_responsable_id', 'nuevo_usuario_nombre', "{$this->apiBase}/usuarios/rapido", 'nombre', $token);

        $data = [
            'nombre'                 => $request->input('nombre'),
            'descripcion'            => $request->input('descripcion'),
            'categoria_id'           => $categoriaId,
            'ubicacion_id'           => $ubicacionId,
            'usuario_responsable_id' => $usuarioId,
            'estado'                 => $request->input('estado'),
        ];

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $path = $request->file('foto')->store('activos', 'public');
            $data['foto'] = '/storage/' . $path;
        }

        $data = array_filter($data, fn($v) => !is_null($v) && $v !== '');

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->put("{$this->apiBase}/activos/{$id}", $data);

            if (!$response->successful()) {
                $body = $response->json();
                $errorMsg = $body['detail'] ?? $body['message'] ?? $response->body();
                return redirect()->back()->with('error', 'Error al actualizar: ' . $errorMsg);
            }

            return redirect()->back()->with('success', 'Activo actualizado correctamente.');

        } catch (\Exception $e) {
            Log::error('Excepción al actualizar activo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo conectar con el servidor.');
        }
    }

    public function destroy($id)
    {
        $token = session('api_token');
        try {
            Http::timeout(5)->withToken($token)->delete("{$this->apiBase}/activos/{$id}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar el activo.');
        }
        return redirect()->back()->with('success', 'Activo eliminado correctamente.');
    }

    /**
     * Resuelve un ID de catálogo, creándolo si es necesario.
     */
    private function resolveCatalog(Request $request, string $fieldId, string $fieldNewName, string $url, string $payloadKey, string $token)
    {
        $val = $request->input($fieldId);
        if ($val === 'NEW' && $request->filled($fieldNewName)) {
            try {
                $resp = Http::timeout(5)
                    ->withToken($token)
                    ->post($url, [$payloadKey => $request->input($fieldNewName)])
                    ->json();
                return $resp['id'] ?? null;
            } catch (\Exception $e) {
                Log::warning("Error creando catálogo {$fieldId}: " . $e->getMessage());
            }
        }
        // Retornar el valor si no es 'NEW' (ya sea un ID numérico o un UUID)
        return ($val && $val !== 'NEW') ? $val : null;
    }
}
