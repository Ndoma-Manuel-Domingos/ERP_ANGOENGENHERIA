<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? env('APP_NAME') }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('dist/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    
    {{-- sweetalert2 --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('dist/css/sweetalert2.min.css') }}">
        
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
      <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
        
    <!-- CSS do FullCalendar -->
    <link href="{{ asset('plugins/fullcalendar/main.min.css') }}" rel="stylesheet">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    
    <style>
    
        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: -1;
        }

        .spinner {
            width: 100px;
            height: 100px;
            border: 10px solid #f3f3f3;
            border-top: 10px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #toast-container {
            max-width: 350px;
        }

    </style>

    @yield('styles')
</head>

@php
    date_default_timezone_set('Africa/Luanda');
    /*sistema de datas*/
    $dia = @date("d");
    $mes = @date("m");
    $ano = @date("Y");
    $dataFinal = $ano."-".$mes."-".$dia;
  
    $controlo = App\Models\ControloSistema::where([
        ['entidade_id', '=', Auth::user()->entidade_id],
    ])->first();
    
    $date1 = date_create($controlo->final);
    $date2 = date_create($dataFinal);
    // $date2 = date_create($controlo->inicio);
    $diff = date_diff($date1,$date2);
    $diasRestantes = $diff->format("%a");
@endphp 

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
    
        <div class="loading-modal d-flex" id="loading-modal">
            <div class="spinner"></div>
        </div>

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
                <!-- Navbar Search -->
                @if ($diasRestantes > 30)
                <h1 class="text-success h4"> <i class="far fa-grin"></i> Faltam {{ $diasRestantes }} dias para expirar a Licença! <i class="far fa-grin"></i></h1>
                @else
                <h1 class="text-danger h4"> <i class="fas fa-grin-tears"></i> Faltam {{ $diasRestantes }} dias para expirar a Licença! <i class="fas fa-grin-tears"></i></h1>
                @endif
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                
                @if (Auth::user()->can('configuracoes'))
                    <li class="nav-item dropdown">
                        <a class="nav-link h4" href="{{ route('dashboard.configuracao') }}">
                        <i class="fas fa-cog"></i>
                        </a>
                    </li>
                @endif 
                
                <li class="nav-item dropdown">
                    <a class="nav-link h4" data-toggle="dropdown" href="#">
                      <i class="far fa-user"></i>
                      <!-- <span class="badge badge-danger navbar-badge">3</span> -->
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right text-center">
                     <div class="bg-info">
                      <img
                        src="{{ asset('dist/img/user.png') }}" alt="User Avatar TESTE" class="img-size-64 ml-auto img-circle m-4" style="text-align: center;"
                      />
                     </div>
                     <div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('privacidade') }}" class="dropdown-item">
                          <i class="fas fa-lock mr-2"></i> <span>Alterar Password</span> 
                          <!-- <span class="float-right text-muted text-sm">2 days</span> -->
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('utilizadores.edit', Auth::user()->id) }}" class="dropdown-item">
                          <i class="fas fa-user-edit mr-2"></i> <span>Actualizar Dados</span> 
                          <!-- <span class="float-right text-muted text-sm">2 days</span> -->
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('congelamento-pin-create') }}" class="dropdown-item">
                          <i class="fas fa-lock mr-2"></i> <span>Congelar Aplicação</span> 
                          <!-- <span class="float-right text-muted text-sm">2 days</span> -->
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        {{-- <a class="dropdown-item text-danger" data-widget="control-sidebar" data-slide="true" href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('formLoggout').submit();" role="button">
                            <i class="fas fa-sign-out-alt"></i> Terminar sessão
                        </a> --}}
                        <a class="dropdown-item text-danger delete-record" data-widget="control-sidebar" data-slide="true" role="button">
                            <i class="fas fa-sign-out-alt"></i> Terminar sessão
                        </a>
                          
                      </div>
                          
                    </div>
                </li>
                
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('includes.main-sidebar-container')

        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Versão 1.0.0
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2021 - @php echo date("Y"); @endphp <a href="">{{ env('APP_NAME') }}</a>.</strong> Todos direitos Reservados.
        </footer>
    </div>
    <!-- ./wrapper -->

    @if (session('caixaAberto'))
    <div class="modal fade" id="caixaAbertoModal" tabindex="-1" role="dialog" aria-labelledby="caixaAbertoModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="caixaAbertoModalLabel">Caixa Aberto</h5>
                </div>
                <div class="modal-body">
                    Você deixou um caixa aberto anteriormente. Deseja fechá-lo ou continuar as vendas?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="fecharCaixaBtn">Fechar Caixa</button>
                    <button type="button" class="btn btn-success" id="continuarVendasBtn">Continuar Vendas</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (session('FirstLoginSystem'))
    <div class="modal fade" id="FirstLoginSystemModal" tabindex="-1" role="dialog" aria-labelledby="FirstLoginSystemModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="FirstLoginSystemModalLabel">Bem-vindo ao {{ ENV('APP_NAME') }}</h5>
                </div>
                <div class="modal-body">
                    Parabéns por criar sua conta! Para garantir que o sistema funcione perfeitamente e você aproveite todos os recursos, é obrigatório configurar os pontos principais antes de começar.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="AceitoConfigurarSistemaBTN">Aceito</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- REQUIRED SCRIPTS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('caixaAberto'))
                const caixaModalElement = document.getElementById('caixaAbertoModal');
                const caixaModal = new bootstrap.Modal(caixaModalElement, {
                    backdrop: 'static', // Impede fechamento ao clicar fora
                    keyboard: false     // Impede fechamento ao pressionar "Esc"
                });
            
                
               // Verificar se o operador já clicou em "Continuar"
                if (!localStorage.getItem('continueSales')) {
                    caixaModal.show();
                }
            
                // Mostrar a modal imediatamente ao carregar a página
                // Botão "Fechar Caixa"
                document.getElementById('fecharCaixaBtn').addEventListener('click', () => {
                    // Simule o fechamento do caixa no back-end
                    fetch(`/flcai/dashboard/fechamento-caixas`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        caixaModal.hide();
                        // window.location.href = '/dashboard/fechamento-caixas'; // Redireciona para a página de login
                    })
                    .catch(error => console.error('Erro:', error));
                });
            
                // Botão "Continuar Vendas"
                document.getElementById('continuarVendasBtn').addEventListener('click', () => {
                   // Simule o fechamento do caixa no back-end
                   fetch(`/flcai/dashboard/continuar-com-caixas`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        caixaModal.hide();
                    })
                    .catch(error => console.error('Erro:', error));
                });
                
            @endif
            
            @if (session('FirstLoginSystem'))
                const caixaModalElement = document.getElementById('FirstLoginSystemModal');
                const caixaModal = new bootstrap.Modal(caixaModalElement, {
                    backdrop: 'static', // Impede fechamento ao clicar fora
                    keyboard: false     // Impede fechamento ao pressionar "Esc"
                });
                
               // Verificar se o operador já clicou em "Continuar"
                if (!localStorage.getItem('continueSalesR')) {
                    caixaModal.show();
                }
                          
                // Botão "Continuar Vendas"
                document.getElementById('AceitoConfigurarSistemaBTN').addEventListener('click', () => {
                    $.ajax({
                        url: `{{ route('aceito-configurar-sistema') }}`, // URL do endpoint no backend
                        method: 'POST', // Método HTTP definido no formulário
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        }
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            // Feche o alerta de carregamento
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                        , error: function(xhr) {
                            // Feche o alerta de carregamento
                            Swal.close();
        
                            // Trata erros e exibe mensagens para o usuário
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let messages = '';
                                $.each(errors, function(key, value) {
                                    messages += `${value}\n *`; // Exibe os erros
                                });
        
                                showMessage('Erro de Validação!', messages, 'error');
        
                            } else {
        
                                showMessage('Erro!', xhr.responseJSON.message, 'error');
        
                            }
        
                        }
                    , });
                });
                
            @endif
        });
    </script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    
    <script src="{{ asset('plugins/chart.js/Chart.js') }}"></script>
    
    <script src="{{ asset('plugins/chart.js/Chart.js') }}"></script>
    {{-- sweetalert2 --}}
    <script src="{{ asset('dist/js/sweetalert2@11.js') }}"></script>
    
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    
    {{-- JS TABELAS CARREGAMENTO DE DATA TABLE --}}
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    {{-- END JS TABELAS CARREGAMENTO DE DATA TABLE --}}
    
    <!-- JS do FullCalendar -->
    <script src="{{ asset('plugins/fullcalendar/main.min.js') }}"></script>
    
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    
    <script src="{{ asset('assets/js/chart-.js') }}"></script>

    <script>  
    
        $(document).on('click', '.delete-record', function(e) {
            e.preventDefault();
            // let recordId = $(this).data('id'); // Obtém o ID do registro
            // const url = `{{ route('clientes.destroy', ':id') }}`.replace(':id', recordId);
            Swal.fire({
                title: 'Você tem certeza?'
                , text: "Esta ação não poderá ser desfeita!"
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#d33'
                , cancelButtonColor: '#3085d6'
                , confirmButtonText: 'Sim, desejo sair!'
                , cancelButtonText: 'Cancelar'
            , }).then((result) => {
                if (result.isConfirmed) {
                    // Envia a solicitação AJAX para excluir o registro
                    $.ajax({
                        url: `{{ route('logout') }}`
                        , method: 'POST'
                        , data: {
                            _token: '{{ csrf_token() }}', // Inclui o token CSRF
                        }
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('O sucesso não espera por quem desiste!', 
                                'Antes de sair, lembre-se: cada minuto que você investe aqui é um passo a mais rumo ao seu objetivo. Volte amanhã e continue avançando!', 
                                'success');
                               
                            window.location.href = response.redirect;
    
                        }
                        , error: function(xhr) {
                            Swal.close();
                            
                            if(xhr.responseJSON.success == false){
                                showMessage('Alerta!', xhr.responseJSON.message, 'warning');
                            }
                        
                            window.location.href = xhr.responseJSON.redirect;
                            
                        }
                    , });
                }
            });
        });

        function progressBeforeSend(title = "Processando...", text = "Por favor, aguarde.", icon = 'info' ) {
            Swal.fire({
              title: title,
              text: text,
              icon: icon,
              allowOutsideClick: false,
              showConfirmButton: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
        }
    
        function showMessage(title, text, icon) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
            });
        }
    
        function showProgressModal() {
            const modal = document.getElementById("loading-modal");
            modal.style.display = "flex";
            modal.style.zIndex = "999999"; // Corrigido: zIndex ao invés de z-index
        }
    
        function hideProgressModal() {
            const modal = document.getElementById("loading-modal");
            modal.style.display = "none";
            modal.style.zIndex = "-1"; // Corrigido: zIndex ao invés de z-index
        }

                    
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
            theme: 'bootstrap4'
            })
        });

    </script>
</body>
</html>

@yield('scripts')