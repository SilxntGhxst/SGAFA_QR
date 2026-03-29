@extends('reportes.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>Código Activo</th>
                <th>Nombre Activo</th>
                <th>Tipo Discrepancia</th>
                <th>Reportado por</th>
                <th>Área</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['codigo_activo'] }}</td>
                    <td>{{ $item['activo_nombre'] }}</td>
                    <td>{{ $item['tipo_dano'] }}</td>
                    <td>{{ $item['reportado_por'] }}</td>
                    <td>{{ $item['area'] }}</td>
                    <td>{{ $item['estado'] }}</td>
                    <td>{{ $item['fecha'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
