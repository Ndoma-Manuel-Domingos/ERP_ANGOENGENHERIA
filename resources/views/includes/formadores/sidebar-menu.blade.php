<!-- Sidebar Menu -->

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
        <li class="nav-item">
            <a href="{{ route('dashboard-formadores') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-formadores') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>    

        <li class="nav-item">
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();document.getElementById('formLoggout').submit();" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                    Terminar sessão
                    <span class="right badge badge-danger">New</span>
                </p>
            </a>
            <form action="{{ route('logout') }}" id="formLoggout" method="post" class="d-none">@csrf
            </form>
        </li>

    </ul>
</nav>
<!-- /.sidebar-menu -->