<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FastApiService;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    protected $api;

    public function __construct(FastApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Vista principal de reportes
     */
    public function index()
    {
        // Cargamos catálogos para los filtros de búsqueda
        $catResponse = $this->api->get('/activos/catalogos');
        $catalogos = $catResponse->successful() ? $catResponse->json() : ['categorias' => [], 'ubicaciones' => []];

        return view('reportes.index', compact('catalogos'));
    }

    /**
     * Proxy para vista previa de reportes (evita CORS y fallos de cliente)
     */
    public function preview(Request $request)
    {
        $tipo = $request->query('tipo', 'activos');
        $params = $request->except(['tipo']);

        switch ($tipo) {
            case 'auditorias':
                $response = $this->api->get('/reportes/auditorias');
                break;
            case 'discrepancias':
                $response = $this->api->get('/reportes/discrepancias');
                break;
            case 'activos':
            default:
                $response = $this->api->get('/reportes/activos', $params);
                break;
        }

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'API Error'], 500);
    }

    /**
     * Generación de PDF según el tipo
     */
    public function generarPdf(Request $request)
    {
        $tipo = $request->input('tipo', 'activos');
        $params = $request->except(['_token', 'tipo']);

        switch ($tipo) {
            case 'auditorias':
                $response = $this->api->get('/reportes/auditorias');
                $view = 'reportes.pdf-auditorias';
                $title = 'Reporte de Auditorías';
                break;
            case 'discrepancias':
                $response = $this->api->get('/reportes/discrepancias');
                $view = 'reportes.pdf-discrepancias';
                $title = 'Reporte de Discrepancias (Buzón)';
                break;
            case 'activos':
            default:
                $response = $this->api->get('/reportes/activos', $params);
                $view = 'reportes.pdf-activos';
                $title = 'Reporte de Inventario de Activos';
                break;
        }

        if (!$response->successful()) {
            return back()->with('error', 'No se pudieron obtener los datos de la API.');
        }

        $data = $response->json()['data'] ?? [];

        $pdf = Pdf::loadView($view, [
            'data' => $data,
            'title' => $title,
            'fecha' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->download(strtolower(str_replace(' ', '_', $title)) . '.pdf');
    }
}
