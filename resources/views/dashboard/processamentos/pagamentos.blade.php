@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pagamentos de Processamento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Pagamentos</li>
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

                <div class="col-12 col-md-12">
                    <form action="{{ route('pagamentos-processamentos-store') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="processamento_id" class="form-label">Tipo Processamento</label>
                                    <select type="text" class="form-control select2" id="processamento_id" name="processamento_id">
                                        <option value="">Selecione</option>
                                        @foreach ($tipo_processamentos as $item)
                                        <option value="{{ $item->id }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="exercicio_id" class="form-label">Exercícios</label>
                                    <select type="text" class="form-control select2" id="exercicio_id" name="exercicio_id">
                                        <option value="">Selecione</option>
                                        @foreach ($exercicios as $item)
                                        <option value="{{ $item->id }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="periodo_id" class="form-label">Perídos</label>
                                    <select type="text" class="form-control select2" id="periodo_id" name="periodo_id">
                                        <option value="">Selecione</option>
                                        @foreach ($periodos as $item)
                                        <option value="{{ $item->id }}"> - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_inicio" class="form-label">Data Inicio</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="" id="data_inicio" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="data_final" class="form-label">Data Final</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="" id="data_final" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="dias_processados" class="form-label">Dias processados</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" value="22" name="dias_processados" id="dias_processados" placeholder="Dias processados">
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label for="forma_de_pagamento" class="form-label">Formas de Pagamentos</label>
                                    <div class="form-group">
                                        <select name="forma_de_pagamento" id="forma_de_pagamentos" class="form-control">
                                            <option value="">Forma de Pagamento</option>
                                            @foreach ($forma_pagmento as $forma)
                                            <option value="{{ $forma->tipo }}" class="text-uppercase"> {{ $forma->titulo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3" id="form_caixas" style="display: none">
                                    <label for="caixa_id" class="form-label">Escolha o Caixa</label>
                                    <div class="form-group">
                                        <select class="form-control" id="caixa_id" name="caixa_id">
                                            <option value="">Caixas</option>
                                            @foreach ($caixas as $item)
                                            <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3" id="form_bancos" style="display: none">
                                    <label for="caixa_id" class="form-label">Escolha a Conta Bancária</label>
                                    <div class="form-group">
                                        <select class="form-control" id="banco_id" name="banco_id">
                                            <option value="">Contas</option>
                                            @foreach ($bancos as $item)
                                            <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label for="dispesa_id" class="form-label">Tipos de Custos</label>
                                    <div class="form-group">
                                        <select name="dispesa_id" id="dispesa_id" class="form-control">
                                            <option value="">Dispesas</option>
                                            @foreach ($dispesas as $item)
                                            <option value="{{ $item->id }}" class="text-uppercase"> {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                {{-- @if (Auth::user()->can('criar subsidio')) --}}
                                <button type="submit" class="btn btn-primary">Pagamento</button>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <h3 class="card-title">
                                    <a href="{{ route('processamentos.create') }}" class="btn btn-sm btn-primary">Novo Processamento</a>
                            </h3> --}}
                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i>
                                    EXCEL</a>
                            </div>
                        </div>

                        @if ($processamentos)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Proc Nº</th>
                                        <th>Nº MAC</th>
                                        <th>Nome Completo</th>
                                        <th>Processamento</th>
                                        <th>Status</th>
                                        <th>Salário Base</th>
                                        <th>Salário Iliquido</th>
                                        <th>Desconto</th>
                                        <th>Salário líquido</th>
                                        <th>Exercício</th>
                                        <th>Período</th>
                                        <th>Operador</th>
                                        <th>Data</th>
                                        <th>Imprimir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($processamentos as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td><a href="{{ route('funcionarios.show', $item->funcionario->id) }}">{{ $item->funcionario->numero_mecanografico }}</a>
                                        </td>
                                        <td>{{ $item->funcionario->nome }}</td>
                                        <td>{{ $item->processamento->nome }}</td>
                                        @if ($item->status == 'Pendente')
                                        <td><span class="badge bg-info">{{ $item->status }}</span></td>
                                        @endif
                                        @if ($item->status == 'Pago')
                                        <td><span class="badge bg-success">{{ $item->status }}</span></td>
                                        @endif
                                        @if ($item->status == 'Anulado')
                                        <td><span class="badge bg-warning">{{ $item->status }}</span></td>
                                        @endif
                                        <td>{{ number_format($item->valor_base, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->valor_iliquido, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->total_desconto, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->valor_liquido, 2, ',', '.') }}</td>

                                        <td>{{ $item->exercicio->nome }}</td>
                                        <td>{{ $item->periodo->nome }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->data_registro }}</td>

                                        <td><a href="{{ route('recibo-processamentos', $item->id) }}" class="text-center" target="_blink"><i class="fas fa-print"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- /.card-body -->
                        @endif


                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');

    $('#forma_de_pagamentos').on('change', function(e) {
        e.preventDefault();

        var forma_pagamento = $('#forma_de_pagamentos').val();

        if (forma_pagamento == "NU") {
            form_caixas.style.display = 'block';
            form_bancos.style.display = 'none';

        } else if (forma_pagamento == "MB" || forma_pagamento == "TE" || forma_pagamento == "DE") {
            form_bancos.style.display = 'block';
            form_caixas.style.display = 'none';

        } else if (forma_pagamento == "OU") {
            form_bancos.style.display = 'block';
            form_caixas.style.display = 'block';
        } else {
            form_caixas.style.display = 'none';
            form_bancos.style.display = 'none';
        }
    })


    $("#exercicio_id").change(() => {
        let id = $("#exercicio_id").val();
        $.get('../carregar-periodos/' + id, function(data) {
            $("#periodo_id").html("")
            $("#periodo_id").html(data)
        })
    })

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
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
                    if (response.success) {

                        if (response.factura.factura == 'FT') {
                            // Gerar a URL usando o Laravel Blade
                            const url = `{{ route('factura-factura', ':code') }}`.replace(':code', response.factura.code);
                            // Redirecionar
                            window.location.href = url;
                        }

                        if (response.factura.factura == 'FR') {
                            // Gerar a URL usando o Laravel Blade
                            const url = `{{ route('factura-recibo', ':code') }}`.replace(':code', response.factura.code);
                            // Redirecionar
                            window.location.href = url;
                        }

                        if (response.factura.factura == 'PP') {
                            // Gerar a URL usando o Laravel Blade
                            const url = `{{ route('factura-proforma', ':code') }}`.replace(':code', response.factura.code);
                            // Redirecionar
                            window.location.href = url;
                        }

                    }

                    showMessage('Sucesso!', 'Exportação concluída com sucesso!', 'success');

                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });

                        showMessage('Erro de Validação!', messages, 'error');

                    } else {

                        showMessage('Erro!', xhr.responseJSON.message, 'error');

                    }

                }
            , });
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
