@extends('layouts.vendas')

@section('section')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header"> </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7">
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
                                            <input type="text" name="" class="form-control form-control-lg valor_total_pagar_fixo" disabled value="{{ number_format($total_pagar, 2, ',', '.')  }}">
                                            <input type="hidden" name="total_pagar" class="form-control form-control-lg total_pagar" value="{{ $total_pagar }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-edit"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="input-group input-group-lg">
                                            <select name="cliente_id" id="" class="form-control form-control-lg">
                                                <option value="">Cliente</option>
                                                @if ($clientes)
                                                    @foreach ($clientes as $item)
                                                        <option value="{{ $item->id }}" selected>{{ $item->nome }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-edit"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="input-group input-group-lg">
                                            <select name="pagamento" id="inputSuccess" class="form-control form-control-lg">
                                                <option value="">Pagamento</option>
                                                <option value="NU" selected>NUMERÁRIO</option>
                                                <option value="MB">Multicaixa</option>
                                                <option value="CC">Cartão Crédico</option>
                                                <option value="TB">Transfêrencia Bancária</option>
                                                <option value="CS">Compensação de Saldos C/C</option>
                                                <option value="multiplo_pagamentos">Multiplo Pagamentos</option>
                                                <option value="alterar_unico_pagamentos">Alterar Único Pagamentos</option>
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-edit"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12 mb-4">
                                        <div class="input-group input-group-lg">
                                        <textarea name="observacao" id="" cols="30" rows="2" class="form-control form-control-lg" placeholder="Observações"></textarea>
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
                                            <input type="radio" id="radioPrimary_super_factura_recibo" name="documento" value="FR" checked>
                                            <label for="radioPrimary_super_factura_recibo">
                                            Factura Recibo
                                            </label>
                                        </div>
                                        {{-- <div class="icheck-primary d-block bg-light p-2">
                                            <input type="radio" id="radioPrimary_super_factura_pro_forma" name="documento" value="PP" >
                                            <label for="radioPrimary_super_factura_pro_forma">
                                            Factura Pró-forma
                                            </label>
                                        </div>
                
                                        <div class="icheck-primary d-block bg-light p-2">
                                            <input type="radio" id="radioPrimary_factura_global" name="documento" value="FG">
                                            <label for="radioPrimary_factura_global">
                                            Factura Global
                                            </label>
                                        </div> --}}
                                    </div>

                                    <div class="col-md-4 col-12 mb-4">
                                        {{-- <div class="icheck-primary d-block bg-light p-2">
                                            <input type="radio" id="radioPrimary_encomenda" name="documento" value="EC" >
                                            <label for="radioPrimary_encomenda">
                                            Encomenda
                                            </label>
                                        </div>
                
                                        <div class="icheck-primary d-block bg-light p-2">
                                            <input type="radio" id="radioPrimary_orcamento" name="documento" value="OT">
                                            <label for="radioPrimary_orcamento">
                                            Orçamento
                                            </label>
                                        </div> --}}


                                        <div class="icheck-primary d-block bg-light p-2">
                                            <input type="radio" id="radioPrimary_super_factura_pro_forma" name="documento" value="PP" >
                                            <label for="radioPrimary_super_factura_pro_forma">
                                            Factura Pró-forma
                                            </label>
                                        </div>
                


                                    </div>

                                    <div class="col-md-4 col-12 mb-4">
                                        {{-- <div class="icheck-primary d-block bg-light p-2">
                                            <input type="radio" id="radioPrimary_super_factura_recibo" name="documento" value="FR" checked>
                                            <label for="radioPrimary_super_factura_recibo">
                                            Factura Recibo
                                            </label>
                                        </div> --}}
                
                                        <div class="icheck-primary d-block bg-light p-2">
                                            <input type="radio" id="radioPrimary_factura" name="documento" value="FT">
                                            <label for="radioPrimary_factura">
                                            Factura
                                            </label>
                                        </div>
                                    </div>

                                </div>
                        </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="card">
                                    <button type="submit"  class="btn btn-primary btn-flat col-12 col-md-12 p-4 text-center float-right" >
                                        <span class="h3 text-white text-uppercase"><i class="fas fa-check"></i> Confirmar venda </span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-5 card pt-3" >
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                {{ $loja->moeda }}
                                                </span>
                                            </div>
                                            {{-- <input type="text" class="form-control py-3" id="defaultKeypad" height="40"> --}}
                                            <input type="text" name="valor_entregue" class="form-control py-3 valor_entregue" height="40" value="{{ $total_pagar }}">
                                            {{-- <input type="text" name="valor_entregue" class="form-control py-3" height="40" value="{{ number_format($total_pagar, 2, ',', '.') }}"> --}}
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-calculator"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <p class="p-1 text-right">
                                            <span class="h5" id="valor_troco_apresenta">0</span> 
                                            <small>{{ $loja->moeda ?? 'KZ' }}</small>  <br> 
                                            <span class="text-uppercase">Troco</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-4 col-md-5">
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
                                            <td class="text-right">{{ number_format($item->valor_pagar, 2, ',', '.') }} <small>{{ $loja->moeda }}</small></td>
                                        </tr>    
                                    @endforeach
                                </tbody>    
                                @endif
                                
                            </table>
                        </div>
                        <!-- /.card-body -->

                        <div class="bg-info">
                            {{-- <div class="row"> --}}
                                <a href="{{ route('pronto-venda') }}" class="btn btn-info btn-flat col-12 col-md-12 p-4 text-center float-right" >
                                    <span class="h3 text-white text-uppercase"><i class="fas fa-close"></i> Fechar </span>
                                </a>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
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

        $(function () {
        
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
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

        });

        $(function () {
            $('#defaultKeypad').keypad();
            $('#inlineKeypad').keypad({onClose: function() {
                alert($(this).val());
            }});
        });

        $('.valor_entregue').on('input', function(e){
            e.preventDefault();
            if($(this).val() > 0){
                // valor total a pagar
                var valor_total = $('.total_pagar').val();
                var valor_entregue = $(this).val();

                var troco = valor_entregue - valor_total;

                // var f = troco.toLocaleString('pt-br',{style: 'currency', currency: 'AOA'});
                var f2 = troco.toLocaleString('pt-br', {minimumFractionDigits: 2});

                $("#valor_troco_apresenta").html("")
                $("#valor_troco_apresenta").append(f2)
                
            }else{
                console.log("false")
            }
        })

        // $(function(){
        //     $('#inlineKeypad').keypad({onClose: useValue, prompt: '', 
        //         closeText: 'Done', closeStatus: 'Use this value'}); 
                    
        //     function useValue(value) { 
        //         alert('The entered value is ' + value); 
        //     } 
            
        //     $('#inlineDisable').click(function() { 
        //         var enable = $(this).text() === 'Enable'; 
        //         $(this).text(enable ? 'Disable' : 'Enable'); 
        //         $('#inlineKeypad').keypad(enable ? 'enable' : 'disable'); 
        //     });
        // })
    </script>
@endsection