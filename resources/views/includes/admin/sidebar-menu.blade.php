<!-- Sidebar Menu -->

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    Empresas
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                
                <li class="nav-item">
                    <a href="{{ route('empresas.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Listagem</p>
                    </a>
                </li>
            
                <li class="nav-item">
                    <a href="{{ route('configuracao-admin') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Configurações</p>
                    </a>
                </li>
            
                <li class="nav-item">
                    <a href="{{ route('gerar-licenca-configuracao-admin') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Gerar Licença</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('nossos-utilizadores') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Utilizadores</p>
                    </a>
                </li>
            
            </ul>
        </li>
        
        
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    Tabela de Apoio
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ route('tipos-entidade.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipo Entidades</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('modulos-entidade.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Modulos Entidades</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('tipo-pagamentos.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipo de Pagamentos</p>
                    </a>
                </li>
                
                                    
                <li class="nav-item">
                    <a href="{{ route('provincias.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Províncias</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('municipios.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Municípios</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('distritos.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Distritos</p>
                    </a>
                </li>
                

            </ul>
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