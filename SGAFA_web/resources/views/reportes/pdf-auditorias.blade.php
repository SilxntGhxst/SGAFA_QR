@extends('reportes.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Ubicación</th>
                <th>Fecha</th>
                <th>Progreso</th>
                <th>Estado</th>
                <th>Resultado Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['folio'] }}</td>
                    <td>{{ $item['ubicacion'] }}</td>
                    <td>{{ $item['fecha'] }}</td>
                    <td>{{ $item['progreso'] }}</td>
                    <td>{{ $item['estado'] }}</td>
                    <td>{{ $item['resultado_final'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
