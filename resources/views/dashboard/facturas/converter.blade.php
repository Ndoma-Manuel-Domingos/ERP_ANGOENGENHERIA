@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Converter Documento {{ $factura->factura_next }}</h1>
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
      <form action="{{ route('converter_factura_put', $factura->id) }}" method="post">
        @csrf
        @method('put')
        <div class="row">
          <div class="col-12 col-md-8 bg-white p-2" id="accordion">

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
                            <i class="fas fa-check text-dark"></i>
                          </td>
                          <td class="text-dark">{{ $item->produto->nome ?? "" }}</td>
                          <td class="text-dark">{{ number_format($item->desconto_aplicado_valor??0, 2, ',', '.') }}</td>
                          <td class="text-dark">{{ $item->produto->taxa_imposto->valor ?? 0 }} %</td>
                          <td class="text-dark">{{ number_format($item->preco_unitario??0, 2, ',', '.') }} {{ $loja->empresa->moeda??"" }}</td>
                          <td class="text-dark">{{ number_format($item->quantidade??0, 2, ',', '.') }}</td>
                          <td class="text-dark">{{ number_format($item->valor_pagar??0, 2, ',', '.') }} {{ $loja->empresa->moeda??"" }}</td>
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
                      <th>{{ number_format($total_pagar??0, 2, ',', '.') }} {{ $loja->empresa->moeda??"" }} </th>
                    </tfoot>
                  </table>

                  @endif
                </div>
              </div>
            </div>

          </div>

          <div class="col-12 col-md-4 bg-white p-2" id="accordion">
            
            <div class="cards card-secondary card-outline">
              <a class="d-block w-100" data-toggle="" href="#facturaVenda">
                <div class="card-header bg-light">
                  <h4 class="card-title w-100">
                    <span class="img-circle bg-danger p-2 float-right" id="carregar_factura_js">FT</span> 
                    <span id="text_factura">Factura </span><br>
                    <small>Selecione o tipo de factura que pretendes converter</small>
                  </h4>
                </div>
              </a>
              <div id="facturaVenda" class="" data-parent="#accordion">
                <div class="card-body">
                  <h4>Facturação</h4>
                  <div class="form-group">

                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="recibo" value="RG" name="factura" {{ $factura->factura == "RG" ? 'checked' : '' }} >
                      <label for="recibo" class="custom-control-label">Recibo</label>
                    </div>

                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="factura_factura" value="FT" name="factura" {{ $factura->factura == "FT" ? 'checked' : '' }}>
                      <label for="factura_factura" class="custom-control-label">Factura</label>
                    </div>
                  </div>
                  <h4>Informativo</h4>
                  <div class="form-group">

                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="factura_pro_forma" value="PP" name="factura" {{ $factura->factura == "PP" ? 'checked' : '' }}>
                      <label for="factura_pro_forma" class="custom-control-label">Factura Pro-forma</label>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer mt-3">
              <button type="submit" class="btn btn-primary" id="botao_submit">Converter Factura</button>
            </div>

          </div>
        </div>
        <!-- /.row -->
      </form>
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

    $("#recibo").on('click', function(){
      var factura = $(this).val();
      carregar_novos_dados(factura, "#carregar_factura_js", "Recibo");
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
      $("#botao_submit").html("Converter " + text_factura);
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