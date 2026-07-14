@extends('layouts.app')


@section('content')
<div class="container">
    <h2>Nueva Solicitud de Viático</h2>


    <form action="{{ route('viaticos.store') }}" method="POST">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif




        <div class="form-group">
            <label>Saldo Inicial (Disponible)</label>
            <input type="text" class="form-control" value="{{ number_format($saldo_inicial, 0, ',', '.') }}" readonly>
            <input type="hidden" name="saldo_inicial" value="{{ $saldo_inicial }}">
        </div>
       
        <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="fecha" class="form-control" required>
        </div>


        <div class="form-group">
            <label>Motivo</label>
            <input type="text" name="motivo" class="form-control" required>
        </div>


        <div class="form-group @error('monto_viatico') has-danger @enderror">
            <label class="col-form-label">Monto del Viático</label>
            <input type="text" id="monto_viatico" name="monto_viatico" value="{{ old('monto_viatico') }}" class="form-control monto_viatico autonumber" data-a-sep="." data-a-dec=",">
            @error('monto_viatico')
                <div class="col-form-label">{{ $message }}</div>
            @enderror
        </div>


        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('viaticos.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection


@push('scripts')
<script type="text/javascript">


// Script para formatear el valor con separador de miles mientras se ingresa TOTAL AMOUNT
document.getElementById('monto_viatico').addEventListener('input', function(event) {
    // Obtenemos el valor ingresado y eliminamos los separadores de miles existentes
    let monto = event.target.value.replace(/\./g, '');
    // Formateamos el valor con separador de miles
    monto = parseFloat(monto).toLocaleString('es-ES');
    // Actualizamos el valor en el input text
    event.target.value = monto;
});


</script>
@endpush