<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FastApiService;

class AjustesController extends Controller
{
    protected $api;

    public function __construct(FastApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Muestra la interfaz de ajustes
     */
    public function index()
    {
        $response = $this->api->get('/ajustes');
        $ajustes = $response->successful() ? $response->json() : [];

        return view('ajustes.index', compact('ajustes'));
    }

    /**
     * Guarda los ajustes enviados desde el formulario
     */
    public function guardar(Request $request)
    {
        $data = $request->except('_token');
        
        $response = $this->api->post('/ajustes/bulk', $data);

        if ($response->successful()) {
            return back()->with('success', 'Configuración guardada correctamente.');
        }

        return back()->with('error', 'Error al guardar los ajustes en la API.');
    }
}
