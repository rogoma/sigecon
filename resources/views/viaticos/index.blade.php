@extends('layouts.app')


@section('content')
    <div class="container">
        <h2>Solicitudes de Viáticos</h2>
        <a href="{{ route('viaticos.create') }}" class="btn btn-primary mb-3">Nueva Solicitud</a>


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Saldo Inicial</th>
                    <th>Motivo</th>
                    <th>Monto del Viático</th>
                    <th>Saldo Final</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($viaticos as $v)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y') }}</td>
                        <td>{{ number_format($v->saldo_inicial, 0, ',', '.') }}</td>
                        <td>{{ $v->motivo }}</td>
                        <td>{{ number_format($v->monto_viatico, 0, ',', '.') }}</td>
                        <td>{{ number_format($v->saldo_final, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
