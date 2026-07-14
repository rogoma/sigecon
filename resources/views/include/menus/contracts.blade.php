<div class="pcoded-main-container">
    <div class="pcoded-wrapper">

        <nav class="pcoded-navbar">
            <div class="nav-list">
                <div class="pcoded-inner-navbar main-menu">
                    <div class="pcoded-navigation-label">Navegación</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="@if(Route::currentRouteName() == 'home') active @endif">
                            <a href="{{ route('home') }}" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="fa-solid fa-shield"></i></span>
                                <span class="pcoded-mtext">Inicio</span>
                                {{-- <a href="/pdf/secuencia" class="btn btn-info" target="_blank">Flujo-Licitaciones</a>
                                <a href="/pdf/secuencia2" class="btn btn-info" target="_blank">Flujo-Compras Menores</a>
                                <a href="/pdf/secuencia3" class="btn btn-info" target="_blank">Flujo-Procesos Compl.</a>  --}}
                            </a>
                            <a href="/pdf/change_pass" class="btn btn-primary" target="_blank">
                                <span class="pcoded-micon"><i class="fa-solid fa-key"></i></span>
                                <span class="pcoded-mtext">Cambiar Password</span>
                            </a>
                        </li>
                    </ul>
                    <div class="pcoded-navigation-label">Módulo de Contratos y Garantías</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa fa-clone"></i></span>
                            <span class="pcoded-mtext">Contratos y Garantías</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'contracts.index') active @endif">
                                    <a href="{{ route('contracts.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Llamados</span>
                                    </a>
                                </li>
                                {{-- <li class="@if(Route::currentRouteName() == 'contracts.create') active @endif">
                                    <a href="{{ route('contracts.create') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Agregar Llamado</span>
                                    </a>
                                </li> --}}
                            </ul>
                        </li>
                    </ul>
                    {{-- <div class="pcoded-navigation-label">Panel de Administración</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa fa-folder-open"></i></span>
                            <span class="pcoded-mtext">Admin. Contratos</span>
                            </a>
                                <ul class="pcoded-submenu"> --}}
                                    {{-- <li class="@if(Route::currentRouteName() == 'departments.index') active @endif">
                                        <a href="{{ route('departments.index') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Tipos de Contratos</span>
                                        </a>
                                    </li>
                                    <li class="@if(Route::currentRouteName() == 'modalities.index') active @endif">
                                        <a href="{{ route('modalities.index') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Modalidades</span>
                                        </a>
                                    </li>
                                    <li class="@if(Route::currentRouteName() == 'districts.index') active @endif">
                                        <a href="{{ route('districts.index') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Tipos de Pólizas</span>
                                        </a>
                                    </li>
                                    <li class="@if(Route::currentRouteName() == 'districts.index') active @endif">
                                        <a href="{{ route('districts.index') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Tipos de Estados</span>
                                        </a>
                                    </li> --}}
                                    {{-- <li class="@if(Route::currentRouteName() == 'providers.index') active @endif">
                                        <a href=" {{ route('providers.index') }} " class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="fa fa-suitcase"></i></span>
                                        <span class="pcoded-mtext">Contratistas</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </nav>

{{-- <div class="pcoded-main-container">
    <div class="pcoded-wrapper">

        <nav class="pcoded-navbar">
            <div class="nav-list">
                <div class="pcoded-inner-navbar main-menu">
                    <div class="pcoded-navigation-label">Navegación</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="@if(Route::currentRouteName() == 'home') active @endif">
                            <a href="{{ route('home') }}" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="fa-solid fa-shield"></i></span>
                                <span class="pcoded-mtext">Inicio</span>
                                <a href="/pdf/secuencia" class="btn btn-info" target="_blank">Flujo-Licitaciones</a>
                                <a href="/pdf/secuencia2" class="btn btn-info" target="_blank">Flujo-Compras Menores</a>
                                <a href="/pdf/secuencia3" class="btn btn-info" target="_blank">Flujo-Procesos Compl.</a>
                            </a>
                            <a href="/pdf/change_pass" class="btn btn-primary" target="_blank">
                                <span class="pcoded-micon"><i class="fa-solid fa-key"></i></span>
                                <span class="pcoded-mtext">Cambiar Password</span>
                            </a>
                        </li>
                    </ul>
                    <div class="pcoded-navigation-label">Módulo de Contratos y Garantías</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa fa-clone"></i></span>
                            <span class="pcoded-mtext">Contratos y Garantías</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'contracts.index') active @endif">
                                    <a href="{{ route('contracts.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Llamados</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> --}}
