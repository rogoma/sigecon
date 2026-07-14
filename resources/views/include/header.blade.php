<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">
        <nav class="navbar header-navbar pcoded-header">
            <div class="navbar-wrapper">
                <div class="navbar-logo">
                    <a href="{{ route('home') }}">
                        SIGECON
                    </a>
                    <a class="mobile-menu" id="mobile-collapse" href="#!">
                        <i class="feather icon-menu icon-toggle-right"></i>
                    </a>
                    <a class="mobile-options waves-effect waves-light">
                        <i class="feather icon-more-horizontal"></i>
                    </a>
                </div>
                <div class="navbar-container container-fluid">
                    <ul class="nav-right">
                        <li class="header-notification">
                            <div class="dropdown-primary dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="feather icon-bell"></i>
                                    <span id="numero-notificaciones" class="badge bg-c-red"></span>
                                </div>
                                <ul style="color:#ff0000" id="alertas-notificaciones" class="show-notification notification-view dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                    <li>
                                        <h6>Alertas</h6>
                                    </li>
                                    <li id="no-alertas">
                                        <h6>No hay alertas actualmente</h6>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="user-profile header-notification">
                            <div class="dropdown-primary dropdown">
                                <div class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ asset('img/user.png') }}" class="img-radius" alt="User-Profile-Image">
                                    <span style="color:blue">{{ Auth::user()->getFullName() }} </span>
                                    {{-- <span style="color:#ff0000">{{ Auth::user()->role->description }}</span> --}}

                                    <i class="feather icon-chevron-down"></i>
                                </div>
                                <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('users.change_pass',Auth::user()->id)}}">Cambiar Contraseña</a>
                                    </li>

                                    {{-- <button type="button" title="Editar" class="btn btn-warning btn-icon" onclick="updateUser({{  Auth::user()->id }})">
                                        <i class="fa fa-pencil"></i>
                                    </button> --}}

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" title="Cerrar Sesión" class="logout-button">
                                                 <i class="feather icon-log-out"></i> Cerrar sesión
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

         @if (Auth::user()->role_id == 1)//administrador del sistema
            @include('include.menus.admin')
        @elseif(Auth::user()->role_id == 2)//DOSAPAS
            @include('include.menus.orders2')
        @elseif(Auth::user()->role_id == 3)//FISCAL
            @include('include.menus.orders')
        @elseif(Auth::user()->role_id == 4)//CONTRATISTA
            @include('include.menus.contractors')
        {{-- @elseif(Auth::user()->role_id == 5)
            @include('include.menus.plannings')
        @elseif(Auth::user()->role_id == 6)
            @include('include.menus.minor_purchases')
        @elseif(Auth::user()->role_id == 7)
            @include('include.menus.awards') --}}
        @elseif(Auth::user()->role_id == 8 || Auth::user()->role_id == 26)//contratos y garantias y derivar contratos
            @include('include.menus.contracts')
        {{-- @elseif(Auth::user()->role_id == 9)
            @include('include.menus.tenders')
        @elseif(Auth::user()->role_id == 10)
            @include('include.menus.exceptions')
        @elseif(Auth::user()->role_id == 11)
            @include('include.menus.utas')
        @elseif(Auth::user()->role_id == 20)
            @include('include.menus.comites')
        @elseif(Auth::user()->role_id == 21)
            @include('include.menus.legal_advices')
        @elseif(Auth::user()->role_id == 23)
            @include('include.menus.coordinations') --}}
        @elseif(Auth::user()->role_id == 24)//DGAF
            @include('include.menus.dgafs')
        {{-- @elseif(Auth::user()->role_id == 25)
            @include('include.menus.documentals') --}}
        @elseif(Auth::user()->role_id == 27)//ADMINISTRADOR DE USUARIOS
            @include('include.menus.admin_users')
        @elseif(Auth::user()->role_id == 30)//UOC2
            @include('include.menus.uoc2')
        @elseif(Auth::user()->role_id == 31)//DPP
            @include('include.menus.orders2')
        @elseif(Auth::user()->role_id == 33)//FONDOS LOCALES
            @include('include.menus.orders')
        @elseif(Auth::user()->role_id == 34)//BID
            @include('include.menus.orders')
        @elseif(Auth::user()->role_id == 35)//FOCEM
            @include('include.menus.orders')
        @endif

    {{-- Si hay rol derivar pedidos y SI ES usuario de UTA     --}}
        {{-- @if(Auth::user()->role_id == 4 && Auth::user()->dependency->id == 56) --}}
            {{-- @include('include.menus.utas') --}}
        {{-- Si hay rol derivar pedidos y NO ES usuario de UTA     --}}
        {{-- @elseif(Auth::user()->role_id == 4 && Auth::user()->dependency->id <> 56)
            @include('include.menus.derive_orders')
        @endif --}}