<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? env('APP_NAME') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('dist/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">      
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
        <!-- BS Stepper -->
        <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">

    <link rel="stylesheet" href="{{ asset('keypad/css/jquery.keypad.css') }}">
</head>

@php
    $caixaActivoAberto = App\Models\Caixa::where([
        ['active', true],
        ['status', '=', 'aberto'],
        ['user_id', '=', Auth::user()->id],
    ])
    ->with('loja')
    ->first();

    $entidade = App\Models\User::with('empresa')->findOrFail(Auth::user()->id);
    $caixaActivoNAbero = App\Models\Caixa::where([
        ['active', true],
        ['entidade_id', '=', $entidade->empresa->id]
    ])
    ->with('loja')
    ->first();

@endphp

<body class="hold-transition sidebar-mini  sidebar-collapse">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
           
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                       Preços
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Grupo de Preços
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                 
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        Caixa
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @if ($caixaActivoAberto)
                            <a href="{{ route('caixa.fechamento_caixa') }}" class="dropdown-item">
                                Estado Caixa <br><small>Caixa {{ $caixaActivoAberto->status }}</small>
                                <span class="float-right text-muted text-info text-sm">Fechar Caixa</span>
                            </a> 
                        @else
                            <a href="{{ route('caixa.abertura_caixa') }}" class="dropdown-item">
                                Estado Caixa <br><small>Caixa Fechada</small>
                                <span class="float-right text-muted text-info text-sm">Abrir Caixa</span>
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('caixa.entrada_dinheiro_caixa') }}" class="dropdown-item">
                            Entrada de dinheiro <br>
                            <small>Entrada de dinheiro manualmente</small>
                            
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('caixa.saida_dinheiro_caixa') }}" class="dropdown-item">
                            Saída de dinheiro <br>
                            <small>Saída de dinheiro manualmente</small>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('caixa.movimentos_caixa') }}" class="dropdown-item">
                            Ponto do Caixa <br>
                            <small>Ponto Situação dos movimentos</small>
                        </a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        Documentos
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            Documento <br><small>Pesquisar por Número ou cliente</small>
                            <span class="float-right text-muted text-info text-sm">Pesquisar</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Facturação <br>
                            <small>Facturas e notas de Creditos</small>
                            
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Informativo <br>
                            <small>Orçamento, facturas pró-formas, etc.</small>
                            
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Transportes <br>
                            <small>Guias de transporte, Guias de Remessa etc.</small>
                            
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Trocas, Devoluções ou Anulações <br>
                            <small>Processo passo a passo.</small>
                            
                        </a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        Documentos
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            Documento <br><small>Pesquisar por Número ou cliente</small>
                            <span class="float-right text-muted text-info text-sm">Pesquisar</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Facturação <br>
                            <small>Facturas e notas de Creditos</small>
                            
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Informativo <br>
                            <small>Orçamento, facturas pró-formas, etc.</small>
                            
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Transportes <br>
                            <small>Guias de transporte, Guias de Remessa etc.</small>
                            
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Trocas, Devoluções ou Anulações <br>
                            <small>Processo passo a passo.</small>
                            
                        </a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        Impressão
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            Tipo impressão <br><small>Impressão Browser</small>
                            <span class="float-right text-muted text-info text-sm">Alterar</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Configurações de Impressão <br>
                            <small>Facturas e notas de Creditos</small>
                            
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Impressoras e Cuzinhas <br>
                        </a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user"></i>
                        {{ Auth::user()->name }} <br>
                        @if ($caixaActivoAberto)
                            <small>{{ $caixaActivoAberto->loja->nome }}</small>
                        @else
                            @if ($caixaActivoNAbero)
                                <small>{{ $caixaActivoNAbero->loja->nome }}</small>
                            @endif
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @if ($caixaActivoAberto)
                            <a href="{{ route('caixa.caixas') }}" class="dropdown-item">
                                Loja - {{ $caixaActivoAberto->loja->nome }}<br>
                                <small>Caixa - {{ $caixaActivoAberto->nome }}</small>
                                <span class="float-right text-muted text-info text-sm">Alterar</span>
                            </a>
                        @else
                            @if ($caixaActivoNAbero)
                                <a href="{{ route('caixa.caixas') }}" class="dropdown-item">
                                    Loja - {{ $caixaActivoNAbero->loja->nome }}<br>
                                    <small>Caixa - {{ $caixaActivoNAbero->nome }}</small>
                                    <span class="float-right text-muted text-info text-sm">Alterar</span>
                                </a>
                            @endif
                        @endif
                        
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Bloquear Ecrã
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Expandir
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Trocar utilizador
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            Tempo de Suspensão
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            BackOffice
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-widget="control-sidebar" data-slide="true" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('formLoggout').submit();" role="button">
                                <i class="fas fa-sign-out-alt"></i>
                            Terminar Sessão
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('pronto-venda') }}" class="brand-link">
                <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">INICIO</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                 
                        <li class="nav-item">
                            <a href="{{ route('pronto-venda') }}" class="nav-link">
                                <i class="nav-icon fas fa-door-open"></i>
                                <p>
                                    Pronto de Venda
                                </p>
                            </a>
                        </li>

                    
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-print"></i>
                                <p>
                                    impressão
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                
                                <li class="nav-item">
                                    <a href="{{ route('produtos.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Configurações de impressão</p>
                                    </a>
                                </li>
                
                                <li class="nav-item">
                                    <a href="{{ route('estoques.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Impressoras e Cuzinhas</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-box"></i>
                                <p>
                                    Caixa
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                
                                <li class="nav-item">
                                    <a href="{{ route('produtos.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Abrir Caixa</p>
                                    </a>
                                </li>
                
                                <li class="nav-item">
                                    <a href="{{ route('estoques.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Entrada de dinheiro</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('estoques.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Saída de dinheiro</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('estoques.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Abrir Gaveta</p>
                                    </a>
                                </li>


                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>
                                    Docunentos
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                
                                <li class="nav-item">
                                    <a href="{{ route('pronto-venda.facturas-facturacao') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Facturas</p>
                                    </a>
                                </li>
                
                                <li class="nav-item">
                                    <a href="{{ route('pronto-venda.facturas-informativo') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Informativo</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('pronto-venda.facturas-sem-pagamento') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Sem Pagamentos</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('pronto-venda.facturas-todas') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Todos</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('pronto-venda.facturas-operacaoes') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Trocas, Devoluções ou Anulações</p>
                                    </a>
                                </li>


                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('dashboard-principal') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Clientes
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('dashboard-principal') }}" class="nav-link">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>
                                    Consultar Stock
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-backward"></i>
                                <p>
                                    Backoffice
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('formLoggout').submit();" role="button">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        @yield('section')

        {{-- <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <!-- Default to the left -->
             <strong>Copyright &copy; 2021 - @php echo date("Y"); @endphp <a href="https://ea-viegas.com">{{ env('APP_NAME') }}</a>.</strong> Todos direitos Reservados.
            reserved.
        </footer> --}}
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->

    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>

    <script src="{{ asset('keypad/js/jquery.plugin.min.js') }}"></script>
    <script src="{{ asset('keypad/js/jquery.keypad.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
            theme: 'bootstrap4'
            })
        });
    </script>
    @include('sweetalert::alert')

</body>

</html>


@yield('scripts')