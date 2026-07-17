<div class="pcoded-main-container">
    <div class="pcoded-wrapper">

        <nav class="pcoded-navbar" navbar-theme="theme1">
            <div class="nav-list">
                <div class="pcoded-inner-navbar main-menu">
                    <div class="pcoded-navigation-label">Navegación</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="@if(Route::currentRouteName() == 'home') active @endif">
                            <a href="{{ route('home') }}" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="fa-solid fa-shield"></i></span>
                                <span class="pcoded-mtext">Inicio</span>
                            </a>
                            <a href="/pdf/change_pass" class="btn btn-primary" target="_blank">
                                <span class="pcoded-micon"><i class="fa-solid fa-key"></i></span>
                                <span class="pcoded-mtext">Cambiar Password</span>
                            </a>
                        </li>
                    </ul>
                    {{-- <div class="pcoded-navigation-label">Módulo de Pedidos</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-list"></i></span>
                            <span class="pcoded-mtext">Pedidos</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'orders.index') active @endif">
                                    <a href="{{ route('orders.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Pedidos</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'orders.create') active @endif">
                                    <a href="{{ route('orders.create') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Agregar Pedido</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul> --}}
                    {{-- <div class="pcoded-navigation-label">Módulo de Planificación</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-clone"></i></span>
                            <span class="pcoded-mtext">Planificación</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'plannings.index') active @endif">
                                    <a href="{{ route('plannings.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Pedidos</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="pcoded-navigation-label">Módulo de Licitaciones</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-clone"></i></span>
                            <span class="pcoded-mtext">Licitaciones</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'tenders.index') active @endif">
                                    <a href="{{ route('tenders.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Llamados</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul> --}}
                    {{-- <div class="pcoded-navigation-label">Módulo de Compras Menores</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-clone"></i></span>
                            <span class="pcoded-mtext">Compras Menores</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'minor_purchases.index') active @endif">
                                    <a href="{{ route('minor_purchases.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Llamados</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul> --}}
                    {{-- <div class="pcoded-navigation-label">Módulo de Procesos Complementarios y Excepciones</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-clone"></i></span>
                            <span class="pcoded-mtext">Proc.Complem.yExcepciones</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'exceptions.index') active @endif">
                                    <a href="{{ route('exceptions.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Llamados</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="pcoded-navigation-label">Módulo de Adjudicaciones</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-clone"></i></span>
                            <span class="pcoded-mtext">Adjudicaciones</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'awards.index') active @endif">
                                    <a href="{{ route('awards.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Llamados</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul> --}}
                    <div class="pcoded-navigation-label">Módulo de Pólizas y Garantías</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-clone"></i></span>
                            <span class="pcoded-mtext">Pólizas y Garantías</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'contracts.index') active @endif">
                                    <a href="{{ route('contracts.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Llamados</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'contracts.create') active @endif">
                                    <a href="{{ route('contracts.create') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Agregar Llamado</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    {{-- <div class="pcoded-navigation-label">Módulo de Contratos/Órdenes de Ejecución</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-clone"></i></span>
                            <span class="pcoded-mtext">Contratos de Obras</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'contracts.index') active @endif">
                                    <a href="{{ route('contracts.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Contratos</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul> --}}

                    <div class="pcoded-navigation-label">Panel de Administración</div>
                    <ul class="pcoded-item pcoded-left-item">
                        {{-- <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-users"></i></span>
                            <span class="pcoded-mtext">Admin. Usuarios</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'users.index') active @endif">
                                    <a href="{{ route('users.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Usuarios</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'roles.index') active @endif">
                                    <a href="{{ route('roles.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Roles</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'permissions.index') active @endif">
                                    <a href="{{ route('permissions.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Permisos</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'positions.index') active @endif">
                                    <a href="{{ route('positions.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Cargos</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-sitemap"></i></span>
                            <span class="pcoded-mtext">Dependencias</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'dependencies.index') active @endif">
                                    <a href="{{ route('dependencies.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Dependencias</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'dependency_types.index') active @endif">
                                    <a href="{{ route('dependency_types.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Tipos de Dependencia</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'uoc_types.index') active @endif">
                                    <a href="{{ route('uoc_types.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Tipos de UOC</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-money-bill"></i></span>
                            <span class="pcoded-mtext">Estructura Presupuestaria</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'funding_sources.index') active @endif">
                                    <a href="{{ route('funding_sources.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Fuentes de Financiamiento</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'financial_organisms.index') active @endif">
                                    <a href="{{ route('financial_organisms.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Organismos Financieros</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'financial_levels.index') active @endif">
                                    <a href="{{ route('financial_levels.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Niveles Financieros</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'program_types.index') active @endif">
                                    <a href="{{ route('program_types.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Tipos de Programa</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'programs.index') active @endif">
                                    <a href="{{ route('programs.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Programas</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'sub_programs.index') active @endif">
                                    <a href="{{ route('sub_programs.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Sub Programas</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'program_measurement_units.index') active @endif">
                                    <a href="{{ route('program_measurement_units.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Unidad de Medida (Programa)</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'expenditure_objects.index') active @endif">
                                    <a href="{{ route('expenditure_objects.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Objetos de Gasto</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-map"></i></span>
                            <span class="pcoded-mtext">Distritos</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'departments.index') active @endif">
                                    <a href="{{ route('departments.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Departamentos</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'regions.index') active @endif">
                                    <a href="{{ route('regions.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Regiones</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'districts.index') active @endif">
                                    <a href="{{ route('districts.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Distritos</span>
                                    </a>
                                </li>
                            </ul>
                        </li> --}}
                        <li class="pcoded-hasmenu">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                            <span class="pcoded-micon"><i class="fa-solid fa-folder-open"></i></span>
                            <span class="pcoded-mtext">Admin. Contratos</span>
                            </a>
                            <ul class="pcoded-submenu">
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
                                <li class="@if(Route::currentRouteName() == 'providers.index') active @endif">
                                    <a href=" {{ route('providers.index') }} " class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><i class="fa-solid fa-suitcase"></i></span>
                                    <span class="pcoded-mtext">Contratistas</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>



{{-- <div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <nav class="pcoded-navbar" navbar-theme="theme1">
            <div class="nav-list">
                <div class="pcoded-inner-navbar main-menu">
                    <div class="pcoded-navigation-label">Navegación</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="@if(Route::currentRouteName() == 'home') active @endif">
                            <a href="{{ route('home') }}" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="fa-solid fa-shield"></i></span>
                                <span class="pcoded-mtext">Inicio</span>
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
                            <span class="pcoded-micon"><i class="fa-solid fa-clone"></i></span>
                            <span class="pcoded-mtext">Contratos y Garantías</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="@if(Route::currentRouteName() == 'contracts.index') active @endif">
                                    <a href="{{ route('contracts.index') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Listado de Llamados</span>
                                    </a>
                                </li>
                                <li class="@if(Route::currentRouteName() == 'contracts.create') active @endif">
                                    <a href="{{ route('contracts.create') }}" class="waves-effect waves-dark">
                                    <span class="pcoded-mtext">Agregar Llamado</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> --}}
