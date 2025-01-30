@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Criar Documento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('produtos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Produto</li>
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
                    <form action="{{ route('facturas.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-8 bg-white p-2" id="accordion">

                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="" href="#selecionarItens">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                1. Itens <br>
                                                <small>Produtos e Serviços</small>
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="selecionarItens" class="" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-12 col-md-12">
                                                    <div class="custom-control custom-radio">
                                                        <select type="text" class="form-control select2" id="produto" name="produto">
                                                            <option value="">Itens</option>
                                                            @if ($produtos)
                                                            @foreach ($produtos as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($movimentos)
                                            <table class="table table-head-fixed text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5px"></th>
                                                        <th>Produto</th>
                                                        <th class="text-right">Desc.</th>
                                                        <th class="text-right">IVA</th>
                                                        <th class="text-right">P.Unit.</th>
                                                        <th class="text-right">Qtd</th>
                                                        <th class="text-right">Retenção</th>
                                                        <th class="text-right">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($movimentos as $item)
                                                    <tr>
                                                        <td class="bg-light">
                                                            <a href="{{ route('remover-produto', [$item->id, 'factura']) }}"><i class="fas fa-close text-danger"></i></a>
                                                        </td>
                                                        <td><a href="{{ route('actualizar-venda-factura', $item->id) }}">{{ $item->produto->nome??"" }}</a></td>
                                                        <td class="text-right"><a href="{{ route('actualizar-venda-factura', $item->id) }}">{{ number_format($item->desconto_aplicado_valor??0, 2, ',', '.') }}</a></td>
                                                        <td class="text-right"><a href="{{ route('actualizar-venda-factura', $item->id) }}">{{ $item->produto->taxa_imposto->valor??0 }}%</a></td>
                                                        <td class="text-right"><a href="{{ route('actualizar-venda-factura', $item->id) }}">{{ number_format($item->preco_unitario??0, 2, ',', '.') }} {{ $loja->empresa->moeda ?? "" }}</a></td>
                                                        <td class="text-right"><a href="{{ route('actualizar-venda-factura', $item->id) }}">{{ number_format($item->quantidade??0, 2, ',', '.') }}</a></td>
                                                        <td class="text-right"><a href="{{ route('actualizar-venda-factura', $item->id) }}">{{ number_format($item->retencao_fonte ?? 0, 2, ',', '.') }} {{ $loja->empresa->moeda??"" }}</a></td>
                                                        <td class="text-right"><a href="{{ route('actualizar-venda-factura', $item->id) }}">{{ number_format($item->valor_pagar??0, 2, ',', '.') }} {{ $loja->empresa->moeda??"" }}</a></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="6"></th>
                                                    <th class="text-right">Total</th>
                                                    <th class="text-right">{{ number_format($total_pagar - $total_retencao , 2, ',', '.') }} {{ $loja->empresa->moeda??"" }} </th>
                                                </tfoot>
                                            </table>

                                            <input type="hidden" value="{{ $total_pagar ??0 }}" name="total_pagar">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="cards card-secondary card-outline">
                                    <a class="d-block w-100" data-toggle="collapse" href="#facturaVenda">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                <span class="img-circle bg-danger p-2 float-right" id="carregar_factura_js">FT</span>
                                                <span id="text_factura">2. Factura </span><br>
                                                <small>Data de Emissão: Hoje</small>
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="facturaVenda" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            {{-- <h4>Facturação</h4> --}}
                                            <div class="form-group">
                                                <div class="row bg-light py-2">
                                                    <div class="col-md-4">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio" id="factura_factura" value="FT" name="factura" checked>
                                                            <label for="factura_factura" class="custom-control-label">Factura</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio" id="factura_recibo" value="FR" name="factura">
                                                            <label for="factura_recibo" class="custom-control-label">Factura Recibo</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="custom-control custom-radio">
                                                            <input class="custom-control-input" type="radio" id="factura_pro_forma" value="PP" name="factura">
                                                            <label for="factura_pro_forma" class="custom-control-label">Factura Pro-forma</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="collapse" href="#selcioneCliente">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                3. Selecione o Cliente
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="selcioneCliente" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    <select type="text" class="form-control select2" id="cliente_id" name="cliente_id">
                                                        @if ($clientes)
                                                        @foreach ($clientes as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="" href="#observacao">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                4. Observações <br>
                                                <small>Referência Externa</small>
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="observacao" class="" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="col-12 col-md-12">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') }}" placeholder="Observações">
                                                </div>
                                                <p class="text-danger">
                                                    @error('observacao')
                                                    {{ $message }}
                                                    @enderror
                                                </p>
                                            </div>

                                            <div class="col-12 col-md-12">
                                                <div class="input-group mb-3">
                                                    <textarea name="referencia" class="form-control" id="referencia" cols="30" rows="2" placeholder="Informe Referência">{{ old('referencia') }}</textarea>
                                                </div>
                                                <p class="text-danger">
                                                    @error('referencia')
                                                    {{ $message }}
                                                    @enderror
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-12 col-md-4 bg-white p-2" id="accordion">
                                <h5 class="bg-white p-2">Definições do Documento</h5>
                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="collapse" href="#caixaPrincipal">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                Caixa Principal (Loja Principal)
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="caixaPrincipal" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="form-group">
                                                @if ($caixa)
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="caixaSelecionado" value="{{ $caixa->id }}" name="caixa_id" checked>
                                                    <label for="caixaSelecionado" class="custom-control-label">{{ $caixa->nome ?? "" }}</label>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="collapse" href="#dataEmissao">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                Data de Emissão
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="dataEmissao" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                        <div class="input-group-text"> Data</div>
                                                    </div>
                                                    <input type="date" name="data_emissao" class="form-control datetimepicker-input" value="{{ date("Y-m-d") }}" data-target="#reservationdate" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="collapse" href="#dataVencimento">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                Data de Vencimento
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="dataVencimento" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="apronto" value="0" name="data_vencimento" checked>
                                                <label for="apronto" class="custom-control-label">A Pronto</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="apronto15" value="15" name="data_vencimento">
                                                <label for="apronto15" class="custom-control-label">A Pronto de 15 Dias</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="apronto30" value="30" name="data_vencimento">
                                                <label for="apronto30" class="custom-control-label">A Pronto de 30 Dias</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="apronto45" value="45" name="data_vencimento">
                                                <label for="apronto45" class="custom-control-label">A Pronto de 45 Dias</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="apronto60" value="60" name="data_vencimento">
                                                <label for="apronto60" class="custom-control-label">A Pronto de 60 Dias</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="apronto90" value="90" name="data_vencimento">
                                                <label for="apronto90" class="custom-control-label">A Pronto de 90 Dias</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="collapse" href="#dataDisponivel">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                Data de Disponibilização
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="dataDisponivel" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                                        <div class="input-group-text"> Data</div>
                                                    </div>
                                                    <input type="date" value="{{ date("Y-m-d") }}" name="data_disponivel" class="form-control datetimepicker-input" data-target="#reservationdate" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="collapse" href="#desconto">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                Desconto
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="desconto" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-md-12">
                                                    <div class="form-group">
                                                        <div class="input-group date" id="timepicker">
                                                            <input type="number" class="form-control" name="desconto" value="0" data-target="#timepicker" disabled />
                                                            <div class="input-group-append" data-target="#timepicker">
                                                                <div class="input-group-text">Kz</div>
                                                            </div>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12">
                                                    <div class="form-group">
                                                        <div class="input-group date" id="timepicker">
                                                            <input type="number" class="form-control" min="0" max="100" value="0" name="desconto_percentagem" data-target="#timepicker" />
                                                            <div class="input-group-append" data-target="#timepicker">
                                                                <div class="input-group-text">%</div>
                                                            </div>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="cards card-secondary card-outline mt-2">
                                    <a class="d-block w-100" data-toggle="collapse" href="#pagamentos">
                                        <div class="card-header bg-light">
                                            <h4 class="card-title w-100">
                                                Pagamentos
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="pagamentos" class="collapse" data-parent="#accordion">
                                        <div class="card-body row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <select name="forma_de_pagamento" id="forma_de_pagamentos" class="form-control form-control-lg">
                                                        <option value="">Forma de Pagamento</option>
                                                        @foreach ($forma_pagmento as $forma)
                                                        <option value="{{ $forma->tipo }}" class="text-uppercase"> {{ $forma->titulo }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6" id="form_caixas" style="display: none">
                                                <div class="form-group">
                                                    <select class="form-control form-control-lg" id="caixa_id" name="caixa_id">
                                                        <option value="">Escolha o Caixa</option>
                                                        @foreach ($caixas as $item)
                                                        <option value="{{ $item->code }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger col-sm-3">
                                                        @error('caixa_id')
                                                        {{ $message }}
                                                        @enderror
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6" id="form_bancos" style="display: none">
                                                <div class="form-group">
                                                    <select class="form-control form-control-lg" id="banco_id" name="banco_id">
                                                        <option value="">Escolha a Conta Bancária</option>
                                                        @foreach ($bancos as $item)
                                                        <option value="{{ $item->code }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger col-sm-3">
                                                        @error('banco_id')
                                                        {{ $message }}
                                                        @enderror
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control valor_entregue form-control-lg text-right" id="valor_entregue" value="{{ $total_pagar }}" name="valor_entregue" />
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control valor_entregue_multicaixa form-control-lg text-right" disabled id="valor_entregue_multicaixa" name="valor_entregue_multicaixa" />
                                                </div>
                                            </div>

                                            <input type="hidden" name="valor_entregue_multicaixa_input" class="valor_entregue_multicaixa_input form-control-lg" id="valor_entregue_multicaixa_input" value="0">
                                            <input type="hidden" name="valor_entregue_input" class="valor_entregue_input" id="valor_entregue_input" value="0">

                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer mt-3">
                                    <button type="submit" class="btn btn-primary" id="botao_submit">Criar Factura</button>
                                </div>

                            </div>
                        </div>
                        <!-- /.row -->
                    </form>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')

<script>
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                beforeSend: function() {
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

    $('#valor_entregue_input').val($('#total_pagar').val());

    $('.valor_entregue').on('input', function(e) {
        e.preventDefault();

        if ($(this).val() > 0) {
            // valor total a pagar
            var valor_total = $('#total_pagar').val();

            var total = parseInt(valor_total.replace(',', '.'));

            var valor_entregue = parseFloat($(this).val());

            var forma_pagamento = $('#forma_de_pagamentos').val();

            var troco = valor_entregue - total;

            if (forma_pagamento == "OU") {

                var valor_restante = valor_entregue - total;

                var restante = valor_restante * (-1);

                var f2 = restante.toLocaleString('pt-br', {
                    minimumFractionDigits: 2
                });

                $('#valor_entregue_multicaixa_input').val(restante);
                $('#valor_entregue_input').val(valor_entregue);
            } else {
                $('#valor_entregue_input').val(valor_entregue);
            }

        } else {
            console.log("false")
        }
    })

    $('.valor_entregue_multicaixa').on('input', function(e) {
        e.preventDefault();
        if ($(this).val() > 0) {
            // valor total a pagar
            var valor_total = $('#total_pagar').val();
            var total = parseInt(valor_total.replace(',', '.'));
            var valor_entregue = parseFloat($(this).val());

            var forma_pagamento = $('#forma_de_pagamentos').val();

            if (forma_pagamento == "OU") {

                var valor_restante = valor_entregue - total;

                var restante = valor_restante * (-1);

                var f2 = restante.toLocaleString('pt-br', {
                    minimumFractionDigits: 2
                });

                $('#valor_entregue_input').val(restante)
                $('#valor_entregue_multicaixa_input').val(valor_entregue)
            }
        } else {
            console.log("false")
        }
    })

    $('#forma_de_pagamentos').on('change', function(e) {
        e.preventDefault();

        var forma_pagamento = $('#forma_de_pagamentos').val();
        var valor_entregue_multicaixa = document.getElementById('valor_entregue_multicaixa');
        var valor_entregue = document.getElementById('valor_entregue');

        var valor_total = $('#total_pagar').val();

        if (forma_pagamento == "NU") {
            valor_entregue.disabled = false;
            valor_entregue_multicaixa.disabled = true;

            $('.valor_entregue_multicaixa').val(0);
            $('.valor_entregue').val(valor_total);

            $('#valor_entregue_multicaixa_input').val(0);
            $('#valor_entregue_input').val(valor_total);

            form_caixas.style.display = 'block';
            form_bancos.style.display = 'none';

        } else if (forma_pagamento == "MB" || forma_pagamento == "TE" || forma_pagamento == "DE") {
            valor_entregue.disabled = true;
            valor_entregue_multicaixa.disabled = false;

            $('.valor_entregue_multicaixa').val(valor_total);
            $('.valor_entregue').val(0);

            $('#valor_entregue_multicaixa_input').val(valor_total);
            $('#valor_entregue_input').val(0);

            form_bancos.style.display = 'block';
            form_caixas.style.display = 'none';

        } else if (forma_pagamento == "OU") {
            valor_entregue.disabled = false;
            valor_entregue_multicaixa.disabled = false;

            $('.valor_entregue').val(valor_total);
            $('.valor_entregue_multicaixa').val(0);

            $('#valor_entregue_multicaixa_input').val(0);
            $('#valor_entregue_input').val(valor_total);

            form_bancos.style.display = 'block';
            form_caixas.style.display = 'block';
        } else {
            form_caixas.style.display = 'none';
            form_bancos.style.display = 'none';
        }
    })


    $(function() {

        $("#factura_recibo").on('click', function() {
            var factura = $(this).val();
            carregar_novos_dados(factura, "#carregar_factura_js", "Factura Recibo");
        });

        $("#factura_global").on('click', function() {
            var factura = $(this).val();
            carregar_novos_dados(factura, "#carregar_factura_js", "Factura Global");
        });

        $("#factura_factura").on('click', function() {
            var factura = $(this).val();
            carregar_novos_dados(factura, "#carregar_factura_js", "Factura");
        });

        $("#factura_orcamento").on('click', function() {
            var factura = $(this).val();
            carregar_novos_dados(factura, "#carregar_factura_js", "Orçamento");
        });

        $("#factura_pro_forma").on('click', function() {
            var factura = $(this).val();
            carregar_novos_dados(factura, "#carregar_factura_js", "Factura Pró-forma");
        });

        $("#encomenda").on('click', function() {
            var factura = $(this).val();
            carregar_novos_dados(factura, "#carregar_factura_js", "Encomenda");
        });

        function carregar_novos_dados(OqueCarregar, ondeCarregar, text_factura) {
            $(ondeCarregar).html("");
            $(ondeCarregar).html(OqueCarregar);
            $("#text_factura").html("");
            $("#text_factura").html(text_factura);

            $("#botao_submit").html("");
            $("#botao_submit").html("Criar " + text_factura);
        }

        $("#produto").on('change', function(e) {
            e.preventDefault();
            // Supondo que o valor do produto está em um campo com id 'produto'
            const produtoId = $("#produto").val();

            if (produtoId != "") {
                // Gerar a URL usando o Laravel Blade
                const url = `{{ route('factura-adicionar-produto', ':produto') }}`.replace(':produto', produtoId);
                // Redirecionar
                window.location.href = url;
            }
        })
        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'
        });
    });

</script>
@endsection
