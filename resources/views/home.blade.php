@extends('layouts.app')

@section('content')
<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    {{-- <br><br> --}}
                    <i class="fa-solid fa-shield bg-c-blue"></i>
                    <div class="d-inline">
                        <h5>Página de inicio</h5>
                        <span>Bienvenido</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class=" breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Página de inicio</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Bienvenido: {{ Auth::user()->getFullName() }}</h5>
                                </div>
                                <div class="card-block">
                                    @if (Auth::user()->role->id == 4)
                                    @else
                                        <p>
                                            <span class="f-w-600">Dependencia:</span><br>
                                            {{ Auth::user()->dependency->description }}
                                        </p>
                                    @endif
                                    <p>
                                        <span class="f-w-600">Rol:</span><br>                                        
                                        {{ Auth::user()->role->description }}
                                    </p>
                                    <p>
                                        <span class="f-w-600">Función:</span><br>                                        
                                        {{ Auth::user()->position->description }}
                                    </p>                                    
                                </div>
                                <div class="card-footer">
                                    <p class="p-t-30">
                                        SENASA-Sistema de Gestión de Órdenes de Ejecución de Componentes y Rubros de Obras<br>
                                        {{-- SENASA<br> --}}
                                        Ministerio de Salud Pública y Bienestar Social.<br>
                                        <small>&copy; {{date('Y')}} SENASA - MSPBS</small>
                                    </p>
                                    <br>
                                        {{-- Muestra vídeos de capacitación sólo para los roles Pedidos --}}
                                        {{-- @if ((Auth::user()->role->id == 2))
                                            Vídeos de capacitación: <a href="https://drive.google.com/file/d/1AKuAno8y8wEKBh9fQHPTR_IPeCBe2dzF/view?usp=sharing" class="btn btn-primary" target="_blank">Video1</a>     <a href="https://drive.google.com/file/d/1QmJa_hRIPe6AhqUPIGsYej-q4rpsMMXi/view?usp=sharing" class="btn btn-primary" target="_blank">Video2</a>
                                        @endif     --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
