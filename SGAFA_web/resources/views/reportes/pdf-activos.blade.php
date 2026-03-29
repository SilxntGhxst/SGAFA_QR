@extends('reportes.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Ubicación</th>
                <th>Estado</th>
                <th>Responsable</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['codigo'] }}</td>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['categoria'] }}</td>
                    <td>{{ $item['ubicacion'] }}</td>
                    <td>
                        <span class="status status-{{ strtolower($item['estado']) }}">
                            {{ $item['estado'] }}
                        </span>
                    </td>
                    <td>{{ $item['responsable'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px;">
        <strong>Resumen:</strong> Total de activos reportados: {{ count($data) }}
    </div>
@endsection
