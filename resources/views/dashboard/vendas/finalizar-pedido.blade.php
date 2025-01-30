@extends('layouts.app')

@php
    $checkCaixa = App\Models\Caixa::where([['active', true], ['status', '=', 'aberto'], ['user_id', '=', Auth::user()->id]])->first();
@endphp

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
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
                    <div class="col-lg-8 col-md-8 col-12">
                        <form action="{{ route('finalizar-venda-create') }}" method="post" id="quickForm">
                            @csrf
                            <div class="card">
                                <div class="card-body" style="height: 580px;">
                                    <div action="" class="row">
                                        <div class="col-md-12 col-12 mb-4">
                                            <div class="input-group input-group-lg">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        {{ $loja->moeda }}
                                                    </span>
                                                </div>
                                                <input type="text" name=""
                                                    class="form-control form-control-lg valor_total_pagar_fixo" disabled
                                                    value="{{ number_format($total_pagar, 2, ',', '.') }}">
                                                <input type="hidden" name="total_pagar"
                                                    class="form-control form-control-lg total_pagar"
                                                    value="{{ $total_pagar }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fas fa-edit"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="input-group input-group-lg">
                                                <select name="cliente_id" id=""
                                                    class="form-control form-control-lg">
                                                    <option value="">Cliente</option>
                                                    @if ($clientes)
                                                        @foreach ($clientes as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->nome == 'CONSUMIDOR FINAL' ? 'selected' : '' }}>
                                                                {{ $item->nome }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fas fa-edit"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="mesa_id" value="{{ $mesa->id }}">
                                        <input type="hidden" name="venda_realizado" value="MESA">

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="input-group input-group-lg">
                                                <select name="pagamento" id="forma_de_pagamentos"
                                                    class="form-control form-control-lg">
                                                    <option value="" selected>Pagamento</option>
                                                    @foreach ($forma_pagmento as $forma)
                                                        <option value="{{ $forma->tipo }}" class="text-uppercase">
                                                            {{ $forma->titulo }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fas fa-edit"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-12 mb-4">
                                            <div class="input-group input-group-lg">
                                                <textarea name="observacao" id="" cols="30" rows="2" class="form-control form-control-lg"
                                                    placeholder="Observações"></textarea>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fas fa-edit"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-12">
                                            <label for="">Documento</label>
                                        </div>

                                        <div class="col-md-4 col-12 mb-4">

                                            <div class="icheck-primary d-block bg-light p-2">
                                                <input type="radio" id="radioPrimary_super_factura_recibo"
                                                    name="documento" value="FR" checked>
                                                <label for="radioPrimary_super_factura_recibo">
                                                    Factura Recibo
                                                </label>
                                            </div>

                                        </div>

                                        <div class="col-md-4 col-12 mb-4">
                                            <div class="icheck-primary d-block bg-light p-2">
                                                <input type="radio" id="radioPrimary_super_factura_pro_forma"
                                                    name="documento" value="PP">
                                                <label for="radioPrimary_super_factura_pro_forma">
                                                    Factura Pró-forma
                                                </label>
                                            </div>

                                        </div>

                                        <div class="col-md-4 col-12 mb-4">
                                            <div class="icheck-primary d-block bg-light p-2">
                                                <input type="radio" id="radioPrimary_factura" name="documento"
                                                    value="FT">
                                                <label for="radioPrimary_factura">
                                                    Factura
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-12 col-md-4">
                                    <div class="card">
                                        <button type="submit"
                                            class="btn btn-dark col-12 col-md-12 p-4 text-center float-right">
                                            <span class="h3 text-white text-uppercase"><i class="fas fa-check"></i>
                                                Confirmar venda </span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-8 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                {{ $loja->moeda }}
                                                            </span>
                                                        </div>
                                                        {{-- <input type="text" class="form-control py-3" id="defaultKeypad" height="40"> --}}
                                                        <input type="text" name="valor_entregue_multicaixa"
                                                            id="valor_entregue_multicaixa"
                                                            class="form-control py-3 valor_entregue_multicaixa" disabled
                                                            height="40" value="0">
                                                        <input type="hidden" name="valor_entregue_multicaixa_input"
                                                            class="valor_entregue_multicaixa_input"
                                                            id="valor_entregue_multicaixa_input" value="">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i
                                                                    class="fas fa-credit-card"></i></div>
                                                            {{-- <i class="far fa-credit-card"></i> --}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                {{ $loja->moeda }}
                                                            </span>
                                                        </div>
                                                        {{-- <input type="text" class="form-control py-3" id="defaultKeypad" height="40"> --}}
                                                        <input type="text" name="valor_entregue" id="valor_entregue"
                                                            class="form-control py-3 valor_entregue" height="40"
                                                            value="{{ $total_pagar }}">
                                                        <input type="hidden" name="valor_entregue_input"
                                                            class="valor_entregue_input" id="valor_entregue_input"
                                                            value="">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-wallet"></i>
                                                            </div>
                                                            {{-- <i class="fas fa-wallet"></i> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12 col-md-6">
                                    <div class="card bg-dark">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <p class="p-1 text-right">
                                                    <span class="h5" id="valor_troco_apresenta">0</span>
                                                    <small>{{ $loja->moeda ?? 'KZ' }}</small> <br>
                                                    <span class="text-uppercase">Troco</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card bg-dark">
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <p class="p-3 text-center">
                                                    <span class="text-uppercase h5">FINALIZAR PEDIDO:
                                                        {{ $mesa ? $mesa->nome : '' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </form>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-lg-3">
                        <div class="card bg-dark">
                            <div class="card-body table-responsive" style="height: 600px;">
                                <table class="table table-head-fixed text-nowrap bg-dark">
                                    <thead>
                                        <tr>
                                            <th class="text-dark">Produto</th>
                                            <th class="text-dark">Qtd</th>
                                            <th class="text-right text-dark">Preço</th>
                                        </tr>
                                    </thead>
                                    @if ($movimentos)
                                        <tbody>
                                            @foreach ($movimentos as $item)
                                                <tr>
                                                    <td>{{ $item->produto->nome }}</td>
                                                    <td>{{ $item->quantidade }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($item->valor_pagar, 2, ',', '.') }}
                                                        <small>{{ $loja->moeda }}</small></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif

                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="bg-info">
                                {{-- <div class="row"> --}}
                                <a href="{{ route('pronto-venda-mesas') }}"
                                    class="btn btn-info btn-flat col-12 col-md-12 p-4 text-center float-right">
                                    <span class="h3 text-white text-uppercase"><i class="fas fa-close"></i> Cancelar
                                    </span>
                                </a>
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection

@section('scripts')
    <script>
        $(function() {

            $('#quickForm').validate({
                rules: {
                    pagamento: {
                        required: true,
                    },
                    cliente_id: {
                        required: true,
                    },
                },
                messages: {
                    pagamento: {
                        required: "Please enter a email address",
                    },
                    cliente_id: {
                        required: "Please enter a email address",
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('#forma_de_pagamentos').on('change', function(e) {
                e.preventDefault();

                var forma_pagamento = $('#forma_de_pagamentos').val();
                var valor_entregue_multicaixa = document.getElementById('valor_entregue_multicaixa');
                var valor_entregue = document.getElementById('valor_entregue');

                var valor_total = $('.total_pagar').val();

                if (forma_pagamento == "NU") {
                    valor_entregue.disabled = false;
                    valor_entregue_multicaixa.disabled = true;

                    $('.valor_entregue_multicaixa').val(0);
                    $('.valor_entregue').val(valor_total);
                } else if (forma_pagamento == "MB") {
                    valor_entregue.disabled = true;
                    valor_entregue_multicaixa.disabled = false;

                    $('.valor_entregue_multicaixa').val(valor_total);
                    $('.valor_entregue').val(0);

                } else if (forma_pagamento == "OU") {
                    valor_entregue.disabled = false;
                    valor_entregue_multicaixa.disabled = false;

                    $('.valor_entregue').val(valor_total);
                    $('.valor_entregue_multicaixa').val(0);
                }
            })
        });

        $(function() {
            $('#defaultKeypad').keypad();
            $('#inlineKeypad').keypad({
                onClose: function() {
                    alert($(this).val());
                }
            });
        });

        $('.valor_entregue').on('input', function(e) {
            e.preventDefault();
            if ($(this).val() > 0) {
                // valor total a pagar
                var valor_total = $('.total_pagar').val();
                var valor_entregue = $(this).val();

                var forma_pagamento = $('#forma_de_pagamentos').val();

                if (forma_pagamento == "NU") {

                    var troco = valor_entregue - valor_total;

                    var f2 = troco.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $("#valor_troco_apresenta").html("")
                    $("#valor_troco_apresenta").append(f2)

                } else if (forma_pagamento == "OU") {

                    var valor_restante = valor_entregue - valor_total;

                    var restante = valor_restante * (-1);

                    var f2 = restante.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $('#valor_entregue_multicaixa').val(0);
                    $('#valor_entregue_multicaixa').val(f2);

                    $('#valor_entregue_multicaixa_input').val(restante);
                    $('#valor_entregue_input').val(valor_entregue);


                    if ((restante + valor_entregue) > valor_total) {
                        var novo_troco = (restante + valor_entregue) - valor_total;


                        var f3 = troco.toLocaleString('pt-br', {
                            minimumFractionDigits: 2
                        });

                        $("#valor_troco_apresenta").html("")
                        $("#valor_troco_apresenta").append(f3)
                    }


                }

            } else {
                console.log("false")
            }
        })

        $('.valor_entregue_multicaixa').on('input', function(e) {
            e.preventDefault();
            if ($(this).val() > 0) {
                // valor total a pagar
                var valor_total = $('.total_pagar').val();
                var valor_entregue = $(this).val();

                // var valor_entregue_outra_forma = $('#valor_entregue').val();

                var forma_pagamento = $('#forma_de_pagamentos').val();

                if (forma_pagamento == "MB") {

                    var troco = valor_entregue - valor_total;

                    // var f = troco.toLocaleString('pt-br',{style: 'currency', currency: 'AOA'});
                    var f2 = troco.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $("#valor_troco_apresenta").html("")
                    $("#valor_troco_apresenta").append(f2)


                } else if (forma_pagamento == "OU") {

                    var valor_restante = valor_entregue - valor_total;

                    var restante = valor_restante * (-1);

                    var f2 = restante.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $('#valor_entregue').val(0);
                    $('#valor_entregue').val(f2);

                    $('#valor_entregue_input').val(restante)
                    $('#valor_entregue_multicaixa_input').val(valor_entregue)

                    if ((restante + valor_entregue) > valor_total) {

                        var novo_troco = (restante + valor_entregue) - valor_total;

                        var f3 = troco.toLocaleString('pt-br', {
                            minimumFractionDigits: 2
                        });

                        $("#valor_troco_apresenta").html("")
                        $("#valor_troco_apresenta").append(f3)
                    }

                }
            } else {
                console.log("false")
            }
        })
    </script>
@endsection
