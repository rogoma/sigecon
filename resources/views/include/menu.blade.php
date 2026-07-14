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
                            </a>
                        </li>
                    </ul>

                @foreach ($main_menu as $menu_level1)
                    @php
                        $childs_level2 = DB::select("SELECT DISTINCT b.id, b.description, b.route, b.icon 
                        FROM roles_menus as a INNER JOIN menus as b ON a.menu_id = b.id 
                        WHERE a.role_id IN(".$role_id.") AND b.level = 2 AND b.superior_menu_id = ".$menu_level1->id);
                    @endphp
                    <div class="pcoded-navigation-label">{{ $menu_level1->description }}</div>
                    <ul class="pcoded-item pcoded-left-item">
                    @foreach ($childs_level2 as $menu_level2)
                        @php
                            $childs_level3 = DB::select("SELECT DISTINCT b.id, b.description, b.route, b.icon 
                            FROM roles_menus as a INNER JOIN menus as b ON a.menu_id = b.id 
                            WHERE a.role_id IN(".$role_id.") AND b.level = 3 AND b.superior_menu_id = ".$menu_level2->id);
                        @endphp
                        @if (count($childs_level3) == 0)
                            <li class="@if(Route::currentRouteName() == $menu_level2->route) active @endif">
                                <a href="@if($menu_level2->route) {{ route($menu_level2->route) }} @endif" class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><i class="{{ $menu_level2->icon }}"></i></span>
                                    <span class="pcoded-mtext">{{ $menu_level2->description }}</span>
                                </a>
                            </li>
                        @else
                            <li class="pcoded-hasmenu">
                                <a href="javascript:void(0)" class="waves-effect waves-dark">
                                    <span class="pcoded-micon"><i class="{{ $menu_level2->icon }}"></i></span>
                                    <span class="pcoded-mtext">{{ $menu_level2->description }}</span>
                                </a>
                                <ul class="pcoded-submenu">
                                @foreach ($childs_level3 as $menu_level3)
                                    <li class="@if(Route::currentRouteName() == $menu_level3->route) active @endif">
                                        <a href="{{ route($menu_level3->route) }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">{{ $menu_level3->description }}</span>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                    </ul>
                @endforeach

                </div>
            </div>
        </nav>