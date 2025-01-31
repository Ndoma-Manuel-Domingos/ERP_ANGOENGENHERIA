@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe da Factura - {{ $factura->factura_next }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('produtos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Factura</li>
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
                <div class="col-12 col-md-9">
                    <div class="invoice p-3 mb-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                Empresa
                                <address>
                                    <strong>{{ $empresa->nome }}</strong><br>
                                    {{ $empresa->nif }}<br>
                                    {{ $empresa->cidade }}<br>
                                    Telefone: {{ $empresa->telefone }}<br>
                                    E-mail | Website: <a href="/cdn-cgi/l/email-protection" >[{{ $empresa->website }}]</a>
                                </address>
                            </div>

                            <div class="col-sm-4 invoice-col">
                                Cliente
                                <address>
                                    <strong><a href="{{ route('clientes.show', $factura->cliente->id) }}">{{ $factura->cliente->nome }}</a></strong><br>
                                    {{ $factura->cliente->nif }}<br>
                                    {{ $factura->cliente->localidade }}<br>
                                    Telefone: {{ $factura->cliente->telefone }}<br>
                                    E-mail: <a href="/cdn-cgi/l/email-protection">[{{ $factura->cliente->email }}]</a>
                                </address>
                            </div>

                            <div class="col-sm-4 invoice-col">
                                <b>{{ $factura->factura_next }}</b><br>
                                <br>
                                <b>Data:</b> {{ $factura->created_at }}<br>
                                <b>Pagamento:</b> {{ $factura->forma_pagamento($factura->pagamento) }}<br>
                                <b>Operador:</b> {{ $factura->user->name }}<br>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Descrição</th>
                                            <th>P.Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Taxa IVA</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($factura->items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><a href="{{ route('produtos.show', $item->produto->id) }}">{{ $item->produto->nome ?? "" }}</a></td>
                                            <td>{{ number_format($item->preco_unitario, 2, ',', '.')  }}</td>
                                            <td>{{ number_format($item->quantidade, 2, ',', '.')  }}</td>
                                            <td>{{ number_format($item->iva_taxa, 1, ',', '.')  }}%</td>
                                            <td class="text-right">{{ number_format($item->valor_pagar, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <h6>
                                            @if ($factura->status_factura == "por pagar")
                                            <span class="bg-warning p-2 float-right"><i class="fas fa-exclamation-triangle"></i> Por Pagar</span>
                                            @else
                                            @if ($factura->status_factura == "pago")
                                            <span class="bg-success p-2 float-right"><i class="fas fa-check"></i> Pago</span>
                                            @endif
                                            @endif
                                        </h6>
                                        <h6 class="lead">Descrição:</h6>
                                        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">{{ $factura->observacao ?? 'Documento emitido para fins de Formação. Não tem validade fiscal.'}}</p>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        @if ($factura->status_factura == "por pagar")
                                        <table class="table">
                                            <thead>
                                                <th>Vencimento</th>
                                                <th>
                                                    <span class="float-right">{{ $factura->data_vencimento }}</span>
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Pago</td>
                                                    <td>
                                                        <span class="float-right">{{ number_format($factura->valor_pago, 2, ',', '.') }} {{ $loja->empresa->moeda }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Por Pagar</td>
                                                    <td>
                                                        <span class="float-right">{{ number_format($factura->valor_total - $factura->valor_pago, 2, ',', '.') }} {{ $loja->empresa->moeda }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                {{-- <p class="lead">Amount Due 2/22/2014</p> --}}
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Valor Entregue/Ultimo Pagamento:</th>
                                            <td class="text-right">{{ number_format($factura->valor_entregue, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td class="text-right">{{ number_format(($factura->valor_total + $factura->total_retencao_fonte) - ($factura->total_iva + $factura->desconto), 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th>Iva: </th>
                                            <td class="text-right">{{ number_format($factura->total_iva, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th>Desconto:</th>
                                            <td class="text-right">{{ number_format($factura->desconto, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th>Retenção Na Fonte:</th>
                                            <td class="text-right">{{ number_format($factura->total_retencao_fonte, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td class="text-right">{{ number_format($factura->valor_total, 2, ',', '.')  }} <small class="text-secondary">{{ $empresa->moeda }}</small></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="row no-print">
                            <div class="col-12">
                                @if ($factura->factura == "FT")
                                <a href="{{ route('factura-factura', $factura->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @else
                                @if ($factura->factura == "FR")
                                <a href="{{ route('factura-recibo', $factura->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @else
                                @if ($factura->factura == "PP")
                                <a href="{{ route('factura-proforma', $factura->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @else
                                @if ($factura->factura == "RG")
                                <a href="{{ route('factura-recibo-recibo', $factura->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @else
                                <a href="{{ route('factura-nota-credito', $factura->code) }}" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                                @endif
                                @endif
                                @endif
                                @endif

                                @if ($factura->anulado == 'N')
                                <a href="{{ route('facturas.edit', $factura->id) }}" class="btn btn-secondary float-right mx-2"><i class="far fa-edit"></i> Retificar Factura </a>
                                @if ($factura->factura == "FP")
                                <a href="{{ route('converter_factura', $factura->id) }}" class="btn btn-primary float-right mx-2"><i class="fas fa-file"></i> Converter Factura</a>
                                @endif
                                <a href="{{ route('anular-factura', $factura->id) }}" class="btn btn-danger float-right mx-2"><i class="fas fa-cancel"></i> Anular</a>

                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                @if ($factura->anulado == 'Y')
                <div class="col-12 col-md-3">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <h1>FACTURA ANULADA</h1>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-12 col-md-3">
                    <form action="{{ route('emitir_recibo') }}" method="post">
                        @csrf
                        @if ($factura->status_factura == "por pagar" || $factura->status == "FT")
                        <div class="card">
                            <div class="card-header bg-light">
                                <h4 class="card-title w-100 text-uppercase"><i class="fas fa-file"></i> Emitir Recibo</h4>
                            </div>
                            <div class="card-body">

                                <input type="hidden" value="{{ $total_pagar }}" name="total_pagar" class="total_pagar" id="total_pagar">
                                <input type="hidden" value="{{ $factura->id }}" name="factura_id">

                                <div class="row">
                                
                                    <div class="col-12 col-md-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="date" class="form-control form-control-lg data_pagamento" id="data_pagamento" value="{{ date("Y-m-d") }}" name="data_pagamento" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <select name="forma_de_pagamento" id="forma_de_pagamentos" class="form-control form-control-lg">
                                                <option value="">Forma de Pagamento</option>
                                                @foreach ($forma_pagmento as $forma)
                                                <option value="{{ $forma->tipo }}" class="text-uppercase">
                                                    {{ $forma->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-12" id="form_caixas" style="display: none">
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
    
                                    <div class="col-12 col-md-12" id="form_bancos" style="display: none">
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
                                
                                    <div class="col-12 col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control valor_entregue form-control-lg text-right" id="valor_entregue" value="{{ $total_pagar }}" name="valor_entregue" />
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control valor_entregue_multicaixa form-control-lg text-right" disabled id="valor_entregue_multicaixa" name="valor_entregue_multicaixa" />
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="valor_entregue_multicaixa_input" class="valor_entregue_multicaixa_input form-control-lg" id="valor_entregue_multicaixa_input" value="0">
                                    <input type="hidden" name="valor_entregue_input" class="valor_entregue_input" id="valor_entregue_input" value="0">

                                </div>
                            </div>
                            <div class="card-footer mt-3">
                                <button type="submit" class="btn btn-primary" id="botao_submit">Emitir Recibo</button>
                            </div>
                        </div>
                        @endif
                        <!-- /.row -->
                    </form>
                </div>
                @endif
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

                // $('#valor_entregue_multicaixa').val(0);
                // $('#valor_entregue_multicaixa').val(f2);

                $('#valor_entregue_multicaixa_input').val(restante);
                $('#valor_entregue_input').val(valor_entregue);
            }else{
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

                // $('#valor_entregue').val(0);
                // $('#valor_entregue').val(f2);

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
            
        } else if (forma_pagamento == "MB" || forma_pagamento == "TE" || forma_pagamento == "DE" ) {
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
</script>

<script>
    $(document).ready(function () {
        $('form').on('submit', function (e) {
            e.preventDefault(); // Impede o envio tradicional do formulário
  
            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário
  
            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                beforeSend: function () {
                  // Você pode adicionar um loader aqui, se necessário
                  progressBeforeSend();
                },
                success: function (response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    if (response.success) {
                        // Gerar a URL usando o Laravel Blade
                        const url = `{{ route('factura-recibo-recibo', ':code') }}`.replace(':code', response.factura.code);
                        // Redirecionar
                        window.location.href = url;
                        // window.location.reload();
                    }
                    // alert(response.mensagem || 'Arquivo exportado com sucesso!');
                    showMessage('Sucesso!', 'Exportação concluída com sucesso!', 'success');
                },
                error: function (xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function (key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', 'Erro ao processar o pedido. Tente novamente.', 'error');
                    }
                },
            });
        });
    });
</script>

@endsection
