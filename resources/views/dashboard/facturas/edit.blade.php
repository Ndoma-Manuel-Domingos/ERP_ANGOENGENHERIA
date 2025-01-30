@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Documento {{ $factura->factura_next }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('facturas.show', $factura->id) }}">Voltar</a></li>
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
          <form action="{{ route('facturas.update', $factura->id) }}" method="post">
            @csrf
            @method('put')
            <div class="row">
              <div class="col-12 col-md-8 bg-white p-2" id="accordion">
    
                <div class="cards card-secondary card-outline">
                  <a class="d-block w-100" data-toggle="collapse" href="#facturaVenda">
                    <div class="card-header bg-light">
                      <h4 class="card-title w-100">
                        <span class="img-circle bg-danger p-2 float-right" id="carregar_factura_js">FT</span> 
                        <span id="text_factura">Factura </span><br>
                        <small>Data de Emissão: Hoje</small>
                      </h4>
                    </div>
                  </a>
                  <div id="facturaVenda" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="form-group row">
                        <div class="custom-control custom-radio col-12 col-md-4">
                          <input class="custom-control-input" type="radio" id="factura_recibo" value="FR" name="factura" {{ $factura->factura == "FR" ? 'checked' : '' }}>
                          <label for="factura_recibo" class="custom-control-label">Factura Recibo</label>
                        </div>
    
                        <div class="custom-control custom-radio col-12 col-md-4">
                          <input class="custom-control-input" type="radio" id="factura_pro_forma" value="PP" name="factura" {{ $factura->factura == "PP" ? 'checked' : '' }}>
                          <label for="factura_pro_forma" class="custom-control-label">Factura Pro-forma</label>
                        </div>
    
                        <div class="custom-control custom-radio col-12 col-md-4">
                          <input class="custom-control-input" type="radio" id="factura_factura" value="FT" name="factura" {{ $factura->factura == "FT" ? 'checked' : '' }} >
                          <label for="factura_factura" class="custom-control-label">Factura</label>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
    
                <div class="cards card-secondary card-outline mt-2">
                  <a class="d-block w-100" data-toggle="collapse" href="#selcioneCliente">
                    <div class="card-header bg-light">
                      <h4 class="card-title w-100">
                        Selecione o Cliente
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
                            <option value="{{ $item->id }}" {{ $factura->cliente_id == $item->id ? "selected" : "" }} >{{ $item->nome }}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
    
                <div class="cards card-secondary card-outline mt-2">
                  <a class="d-block w-100" data-toggle="" href="#selecionarItens">
                    <div class="card-header bg-light">
                      <h4 class="card-title w-100">
                        Itens <br>
                        <small>Produtos e Serviços</small>
                      </h4>
                    </div>
                  </a>
                  <div id="selecionarItens" class="" data-parent="#accordion">
                    <div class="card-body">
                     
                      @if ($movimentos)
                      <table class="table table-head-fixed text-nowrap">
                        <thead>
                          <tr>
                            <th style="width: 5px"></th>
                            <th>Produto</th>
                            <th>Desc.</th>
                            <th>IVA</th>
                            <th class="text-right">P.Unit.</th>
                            <th>Qtd</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($movimentos as $item)
                            <tr>
                              <td class="bg-light">
                                <a href="{{ route('retificar-venda-remover-produto', $item->id) }}"><i class="fas fa-close text-danger"></i></a>
                              </td>
                              <td><a href="{{ route('retificar-venda', $item->id ) }}">{{ $item->produto->nome ?? "" }}</a></td>
                              <td><a href="{{ route('retificar-venda', $item->id) }}">{{ number_format(($item->desconto_aplicado_valor ?? 0), 2, ',', '.') }}</a></td>
                              <td><a href="{{ route('retificar-venda', $item->id) }}">{{ $item->produto->taxa_imposto->valor??0 }}</a></td>
                              <td class="text-right"><a href="{{ route('retificar-venda', $item->id) }}">{{ number_format(($item->preco_unitario ?? 0), 2, ',', '.') }} {{ $loja->empresa->moeda ?? "AOA" }}</a></td>
                              <td><a href="{{ route('retificar-venda', $item->id) }}">{{ number_format(($item->quantidade ?? 0), 2, ',', '.') }}</a></td>
                              <td><a href="{{ route('retificar-venda', $item->id) }}">{{ number_format(($item->valor_pagar ?? 0), 2, ',', '.') }} {{ $loja->empresa->moeda ?? "AOA" }}</a></td>
                            </tr>
                          @endforeach
                        </tbody>
                        <tfoot>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th>Total</th>
                          <th>{{ number_format($total_pagar ?? 0, 2, ',', '.') }} {{ $loja->empresa->moeda ?? "AOA" }} </th>
                        </tfoot>
                      </table>
    
                      <input type="hidden" value="{{ $total_pagar ?? 0 }}" name="total_pagar">
                      @endif
                    </div>
                  </div>
                </div>
    
                <div class="cards card-secondary card-outline mt-2">
                  <a class="d-block w-100" data-toggle="" href="#observacao">
                    <div class="card-header bg-light">
                      <h4 class="card-title w-100">
                        Observações <br>
                        <small>Referência Externa</small>
                      </h4>
                    </div>
                  </a>
                  <div id="observacao" class="" data-parent="#accordion">
                    <div class="card-body">
                      <div class="col-12 col-md-12">
                        <div class="input-group mb-3">
                          <input type="text" class="form-control" id="observacao" name="observacao" value="{{ $factura->observacao ?? "" }}"
                            placeholder="Observações">
                        </div>
                        <p class="text-danger">
                          @error('observacao')
                          {{ $message }}
                          @enderror
                        </p>              
                      </div>
    
                      <div class="col-12 col-md-12">
                        <div class="input-group mb-3">
                          <textarea name="referencia" class="form-control" id="referencia" cols="30" rows="2" placeholder="Informe Referência">{{ $factura->referencia ?? "" }}</textarea>
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
                          <input class="custom-control-input" type="radio" id="caixaSelecionado" value="{{ $caixa->id }}" name="caixa_id" {{ $factura->caixa_id == $caixa->id ? "checked" : "" }} >
                          <label for="caixaSelecionado" class="custom-control-label">{{ $caixa->nome }}</label>
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
                          <input type="date" name="data_emissao" class="form-control datetimepicker-input" value="{{ $factura->data_emissao }}" data-target="#reservationdate" />
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
                        <input class="custom-control-input" type="radio" id="apronto" value="0" name="data_vencimento"  {{ $factura->prazo == "0" ? "checked" : "" }}>
                        <label for="apronto" class="custom-control-label">A Pronto</label>
                      </div>
    
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="apronto15" value="15" name="data_vencimento" {{ $factura->prazo == "15" ? "checked" : "" }}>
                        <label for="apronto15" class="custom-control-label">A Pronto de 15 Dias</label>
                      </div>
    
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="apronto30" value="30" name="data_vencimento" {{ $factura->prazo == "30" ? "checked" : "" }}>
                        <label for="apronto30" class="custom-control-label">A Pronto de 30 Dias</label>
                      </div>
    
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="apronto45" value="45" {{ $factura->prazo == "45" ? "checked" : "" }} name="data_vencimento">
                        <label for="apronto45" class="custom-control-label">A Pronto de 45 Dias</label>
                      </div>
    
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="apronto60" value="60" {{ $factura->prazo == "60" ? "checked" : "" }} name="data_vencimento">
                        <label for="apronto60" class="custom-control-label">A Pronto de 60 Dias</label>
                      </div>
    
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="apronto90" value="90" {{ $factura->prazo == "90" ? "checked" : "" }} name="data_vencimento">
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
                          <input type="date"  value="{{ $factura->data_disponivel }}" name="data_disponivel" class="form-control datetimepicker-input" data-target="#reservationdate" />
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
                    <div class="card-body">
                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" name="forma_pagamento" type="checkbox" id="dinheiro" value="NU" {{ $factura->pagamento == "NU" ? "checked" : "" }} >
                          <label for="dinheiro" class="custom-control-label">Dinheiro</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" name="forma_pagamento" type="checkbox" id="multibanco" value="MB" {{ $factura->pagamento == "MB" ? "checked" : "" }} >
                          <label for="multibanco" class="custom-control-label">Multibanco</label>
                        </div>
                        {{-- <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" name="forma_pagamento" type="checkbox" id="cartao_credito" value="cartao_credito" {{ $factura->pagamento == "cartao_credito" ? "checked" : "" }} >
                          <label for="cartao_credito" class="custom-control-label">Cartão Crédito</label>
                        </div>
    
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" name="forma_pagamento" type="checkbox" id="compensao" value="compensao" {{ $factura->pagamento == "compensao" ? "checked" : "" }}>
                          <label for="compensao" class="custom-control-label">Compensão de Saldos(C/C)</label>
                        </div> --}}
    
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
                        <div class="col-6">
                          <div class="form-group">
                            <div class="input-group date" id="timepicker">
                              <input type="text" class="form-control" value="{{ $factura->desconto }}" name="desconto" data-target="#timepicker" />
                              <div class="input-group-append" data-target="#timepicker">
                                <div class="input-group-text">Kz</div>
                              </div>
                            </div>
                            <!-- /.input group -->
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <div class="input-group date" id="timepicker">
                              <input type="text" class="form-control" value="{{ $factura->desconto_percentagem }}" name="desconto_percentagem" data-target="#timepicker" />
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
    
                  <div class="card-footer mt-3">
                    <button type="submit" class="btn btn-primary" id="botao_submit">Actualizar Factura</button>
                  </div>
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
  $(function () {


    //#####################################################
    //###  
    if($("#factura_recibo").is(":checked")){
      var factura = $("#factura_recibo").val();
      $("#carregar_factura_js").html("");      
      $("#carregar_factura_js").html(factura);
    }
    if($("#factura_global").is(":checked")){
      var factura = $("#factura_global").val();
      $("#carregar_factura_js").html("");      
      $("#carregar_factura_js").html(factura);
    }
    if($("#factura_factura").is(":checked")){
      var factura = $("#factura_factura").val();
      $("#carregar_factura_js").html("");      
      $("#carregar_factura_js").html(factura);
    }
    if($("#factura_orcamento").is(":checked")){
      var factura = $("#factura_orcamento").val();
      
      $("#carregar_factura_js").html("");      
      $("#carregar_factura_js").html(factura);
    }
    if($("#factura_pro_forma").is(":checked")){
      var factura = $("#factura_pro_forma").val();
      $("#carregar_factura_js").html("");      
      $("#carregar_factura_js").html(factura);
    }
    if($("#encomenda").is(":checked")){
      var factura = $("#encomenda").val();
      $("#carregar_factura_js").html("");      
      $("#carregar_factura_js").html(factura);
    }
    //#####################################################

    $("#factura_recibo").on('click', function(){
      var factura = $(this).val();
      carregar_novos_dados(factura, "#carregar_factura_js", "Factura Recibo");
    });

    $("#factura_global").on('click', function(){
      var factura = $(this).val();
      carregar_novos_dados(factura, "#carregar_factura_js", "Factura Global");
    });

    $("#factura_factura").on('click', function(){
      var factura = $(this).val();
      carregar_novos_dados(factura, "#carregar_factura_js", "Factura");
    });

    $("#factura_orcamento").on('click', function(){
      var factura = $(this).val();
      carregar_novos_dados(factura, "#carregar_factura_js", "Orçamento");
    });

    $("#factura_pro_forma").on('click', function(){
      var factura = $(this).val();
      carregar_novos_dados(factura, "#carregar_factura_js", "Factura Pró-forma");
    });

    $("#encomenda").on('click', function(){
      var factura = $(this).val();
      carregar_novos_dados(factura, "#carregar_factura_js", "Encomenda");
    });

    function carregar_novos_dados(OqueCarregar, ondeCarregar, text_factura){
      $(ondeCarregar).html("");
      $(ondeCarregar).html(OqueCarregar);
      $("#text_factura").html("");
      $("#text_factura").html(text_factura);

      $("#botao_submit").html("");
      $("#botao_submit").html("Actualizar " + text_factura);
    }

    $("#salvarItem").on('click', function(e){
      e.preventDefault();
      
      // Obter os valores dos campos
      const produtoId = $("#produto").val();
      const codigoFactura = $("#codigo_factura").val();
      
      if(produtoId != ""){
        
        // Gerar a URL com múltiplos parâmetros
        const url = `{{ route('retificar-venda-adicionar-produto', [':produto', ':codigo_factura']) }}`
          .replace(':produto', produtoId)
          .replace(':codigo_factura', codigoFactura);
        
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