@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Configurações</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Painel Principal</a></li>
                        <li class="breadcrumb-item active">Configurações</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-md-12">
                    @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                    @endif

                    @if (Session::has('danger'))
                    <div class="alert alert-danger">
                        {{ Session::get('danger') }}
                    </div>
                    @endif

                    @if (Session::has('warning'))
                    <div class="alert alert-warning">
                        {{ Session::get('warning') }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h4">Configurações de Inicialização</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h5 mb-3">Deseja fazer abertura do caixa automaticamente após iniciar a sessão(Login)?</h2>

                            {{-- <h2 class="h6 mb-3">Neste Momento o caixa não é inicializado após inicio de sessão.</h2> --}}
                        </div>  
                        <div class="card-footer">
                            @if($tipo_entidade_logado->empresa->inicializacao == "Y")
                            <a href="{{ route('dashboard.configuracao-inicializacao') }}" class="btn-sm btn-danger">NÃO</a>
                            @endif
                            @if($tipo_entidade_logado->empresa->inicializacao == "N")
                            <a href="{{ route('dashboard.configuracao-inicializacao') }}" class="btn-sm btn-success">SIM</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h4">Configurações de Finalização</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="h5 mb-3">Deseja fazer o fecho do caixa automaticamente após terminar a sessão(Login)?</h2>

                            {{-- <h2 class="h6 mb-3">Neste Momento o caixa não é fechado após finalização da sessão.</h2> --}}
                        </div>  
                        <div class="card-footer">
                            @if($tipo_entidade_logado->empresa->finalizacao == "Y")
                            <a href="{{ route('dashboard.configuracao-finalizacao') }}" class="btn-sm btn-danger">NÃO</a>
                            @endif
                            @if($tipo_entidade_logado->empresa->finalizacao == "N")
                            <a href="{{ route('dashboard.configuracao-finalizacao') }}" class="btn-sm btn-success">SIM</a>
                            @endif
                        </div>
                    </div>
                </div>

            
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h4">TPA Activo</h5>
                        </div>
                        <div class="card-body">
                            @if ($bancoActivo)
                            <h2 class="h5 mb-3 text-success">Olá {{ Auth::user()->name }}, informamos que o neste momento tens o TPA: "{{ $bancoActivo->nome }}" activo.</h2>
                            @else
                            <h2 class="h5 mb-3 text-danger">Olá {{ Auth::user()->name }}, informamos que o neste momento não tens nenhum TPA activo.</h2>
                            @endif
                        </div>  
                        <div class="card-footer">
                            @if ($bancoActivo)
                                <a href="{{ route('contas-bancarias.fechamento', $bancoActivo->id) }}" data-id="{{ $bancoActivo->id }}" class="btn-sm btn-danger fechar-conta-bancaria">Fecha o TPA activo</a>
                            @else
                                <a href="{{ route('contas-bancarias.abertura') }}" class="btn-sm btn-success">Activar TPA</a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h4">Caixa Activo</h5>
                        </div>
                        <div class="card-body">
                            @if ($caixaActivo)
                            <h2 class="h5 mb-3 text-success">Olá {{ Auth::user()->name }}, informamos que o neste momento tens o caixa: "{{ $caixaActivo->nome }}" activo.</h2>
                            @else
                            <h2 class="h5 mb-3 text-danger">Olá {{ Auth::user()->name }}, informamos que o neste momento não tens nenhum caixa aberto.</h2>
                            @endif
                        </div>  
                        <div class="card-footer">
                            @if ($caixaActivo)
                                <a href="{{ route('caixa.fechamento_caixa', $caixaActivo->id) }}" class="btn-sm btn-danger">Fecha o Caixa aberto</a>
                            @else
                                <a href="{{ route('caixa.abertura_caixa') }}" class="btn-sm btn-success">Abertura do Caixa</a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- /.row -->
            </div>
    
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

{{-- @include('dashboard.config.modal.dados-empresa') --}}
@endsection



@section('scripts')
<script>
    $(document).on('click', '.fechar-conta-bancaria', function(e) {

        e.preventDefault();
        let recordId = $(this).data('id'); // Obtém o ID do registro
        Swal.fire({
            title: 'Você tem certeza?'
            , text: "Esta ação não poderá ser desfeita!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#3085d6'
            , confirmButtonText: 'Sim, desejo!'
            , cancelButtonText: 'Cancelar'
        , }).then((result) => {
            if (result.isConfirmed) {
                // Envia a solicitação AJAX para excluir o registro
                $.ajax({
                    url: `{{ route('contas-bancarias.fechamento', ':id') }}`.replace(':id', recordId)
                    , method: 'GET'
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
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', 'Ocorreu um erro ao excluir o registro. Tente novamente.', 'error');
                    }
                , });
            }
        });
    });

    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
