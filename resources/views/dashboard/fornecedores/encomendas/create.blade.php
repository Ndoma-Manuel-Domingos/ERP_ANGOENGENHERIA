@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Adicionar Encomenda</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('fornecedores-encomendas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Encomendas</li>
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
          <div class="card">
            <form action="{{ route('fornecedores-encomendas.store') }}" method="post" class="">
              @csrf
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-4">
                    <label for="numero" class="form-label text-right">Nº Encomenda:</label>
                    <input type="text" class="form-control" id="numero" name="numero" value="{{ $totalEncomendas }}" placeholder="Número da Encomenda:">
                    <p class="text-danger col-sm-3">
                      @error('numero')
                      {{ $message }}
                      @enderror
                    </p>              
                  </div>
      
                  <div class="col-12 col-md-4">
                    <label for="fornecedor_id" class="form-label text-right">Fornecedor:</label>
                    <select class="form-control select2" id="fornecedor_selecionado" name="fornecedor_selecionado">
                      @foreach ($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id ?? old('fornecedor_selecionado') }}">{{ $fornecedor->nome }}</option>  
                      @endforeach
                    </select>
                    <p class="text-danger col-sm-3">
                      @error('fornecedor_selecionado')
                      {{ $message }}
                      @enderror
                    </p>              
                  </div>
      
                  <div class="col-12 col-md-4">
                    <label for="loja_id" class="form-label text-right">Loja/Armazém:</label>
                      <select class="form-control select2" id="loja_id" name="loja_id">
                        @foreach ($lojas as $loja)
                          <option value="{{ $loja->id ?? old('fornecedor_selecionado') }}">{{ $loja->nome }}</option>  
                        @endforeach
                      </select>
                      <p class="text-danger col-sm-3">
                        @error('loja_id')
                        {{ $message }}
                        @enderror
                      </p>              
                  </div>
                  
                  <div class="col-12 col-md-4">
                    <label for="custo_transporte" class="form-label text-right">Custos de Transporte:</label>
                    <input type="number" class="form-control" id="custo_transporte" name="custo_transporte" value="{{ old('custo_transporte') ?? 0 }}" placeholder="Custos de Transporte">
                    <p class="text-danger col-sm-3">
                      @error('custo_transporte')
                      {{ $message }}
                      @enderror
                    </p>              
                  </div>
                  
                  <div class="col-12 col-md-4">
                    <label for="custo_manuseamento" class="form-label text-right">Custos de Manuseamento:</label>
                    <input type="number" class="form-control" id="custo_manuseamento" name="custo_manuseamento" value="{{ old('custo_manuseamento') ?? 0 }}" placeholder="Custos de Manuseamento">
                    <p class="text-danger col-sm-3">
                      @error('custo_manuseamento')
                      {{ $message }}
                      @enderror
                    </p>              
                  </div>
                  
                  <div class="col-12 col-md-4">
                    <label for="outros_custos" class="form-label text-right">Outros Custos</label>
                    <input type="number" class="form-control" id="outros_custos" name="outros_custos" value="{{ old('outros_custos') ?? 0 }}" placeholder="Outros Custos direitamente atribuíveis à compra dos bens">
                    <p class="text-danger col-sm-3">
                      @error('outros_custos')
                      {{ $message }}
                      @enderror
                    </p>              
                  </div>
      
                  <div class="col-12 col-md-4">
                      <label for="data_previsao" class="form-label text-right">Previsão de Entrega:</label>
                      <input type="date" class="form-control" id="data_previsao" name="data_previsao" value="{{ old('data_previsao') ?? date("Y-m-d") }}"
                          placeholder="">
                      <p class="text-danger col-sm-3">
                        @error('data_previsao')
                        {{ $message }}
                        @enderror
                      </p>              
                  </div>
      
                  <div class="col-12 col-md-4">
                      <label for="observacao" class="form-label text-right">Observações:</label>
                      <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') ?? "" }}" placeholder="Observações ">
                      <p class="text-danger col-sm-3">
                        @error('observacao')
                        {{ $message }}
                        @enderror
                      </p>              
                  </div>
      
                  <div class="col-12 col-md-3">
                    <label for="produto" class="form-label text-right">Pesquisar Produto:</label>
                    <select class="form-control select2" id="produto" name="produto">
                      <option value="">Selecione o produto</option>
                      @if ($produtos)
                        @foreach ($produtos as $item2)
                          <option value="{{ $item2->id }}">{{ $item2->nome }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                      
                  <div class="col-12 col-md-1">
                    <label for="" class="form-label">.</label><br>
                    <a href="" class="btn btn-primary" id="salvarItem">Confirmar</a>             
                  </div>
                  
                  @if ($items)
                  <div class="col-12 col-md-12 mt-5">
                    <table class="table table-head-fixed text-nowrap">
                      <thead>
                        <tr>
                          <th style="width: 5px"></th>
                          <th>Produto</th>
                          <th>Qtd</th>
                          <th>Custo</th>
                          <th>IVA</th>
                          <th>Desconto</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($items as $item)
                          <tr>
                            <td class="bg-light">
                              <a href="{{ route('items-nova-encomenda-remover-sem-fornecedora-ctualizar', $item->id) }}" id="remover_id" class="text-danger bg-danger p-1 img-circle"><i class="fas fa-close text-white"></i></a>
                            </td>
                            <td>
                              <input type="text" class="form-control produto_id" value="{{ $item->produto->nome ?? '' }}" name="produto_id{{ $item->id }}" id="{{ $item->id }}">
                            </td>
                            <td>
                              <input type="text" class="form-control quantidade quantidade{{ $item->id }}" value="{{ $item->quantidade ?? 0 }}" data-custo="{{ $item->custo ?? 0 }}" data-total="{{ $item->total }}" name="quantidade{{ $item->id }}" id="{{ $item->id }}">
                            </td>
                            <td>
                              <input type="text" class="form-control custo custo{{ $item->id }}" value="{{ $item->custo ?? 0 }}" name="custo{{ $item->id }}" id="{{ $item->id }}">
                            </td>
                            <td>
                              <select name="iva{{ $item->id }}" id="{{ $item->id }}" class="form-control iva">
                                @foreach ($impostos as $item2)
                                <option value="{{ $item2->valor }}" {{ $item->iva ==  $item2->id ? 'selected' : '' }}>{{ $item2->descricao }}</option>
                                @endforeach
                              </select>
                            </td>
                            <td>
                              <input type="text" class="form-control desonto" value="{{ $item->desconto ?? 0 }}" name="desonto{{ $item->id }}" id="{{ $item->id }}">
                            </td>
                            <td class="totalValor{{ $item->id }}" id="{{ $item->id }}"> {{ $item->total ?? '' }} </td>
                            <input type="hidden" name="ids[]" value="{{ $item->id }}">
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  @endif
                </div>
              </div>
    
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
              </div>
            </form>
          </div>
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
      $("#botao_submit").html("Criar " + text_factura);
    }

    $(".quantidade").on('input', function(){
        var quantidade =  parseInt($(this).val());
        var id =  $(this).attr('id');
        
        var total = parseInt($(this).data('total'));
        var custo = parseInt($(this).data('custo'));
        
        var resultado = quantidade * custo;

        $('.totalValor'+id).html("");
        $('.totalValor'+id).append(resultado);

    });

    $(".custo").on('input', function(){
      var custo = parseInt($(this).val());
      var quantidade =  parseInt($('.quantidade').val());
      
      var id =  $(this).attr('id');
        
      var resultado = quantidade * custo;

      $('.totalValor'+id).html("");
      $('.totalValor'+id).append(resultado);

    });
    
    $(".desonto").on('input', function(){
      var desconto = parseInt($(this).val());

      var id =  $(this).attr('id');
      var quantidade =  parseInt($('.quantidade'+id).val());
      var custo =  parseInt($('.custo'+id).val());
      var resultado = quantidade * custo;

      if(desconto >= 1 && desconto <= 100){
        var resultadoDesconto = (resultado) - ((resultado) * (desconto / 100));
        var valorDescontado = (resultado) * (desconto / 100);
        $('.totalValor'+id).html("");
        $('.totalValor'+id).append(resultadoDesconto);
      }else{
        $('.totalValor'+id).html("");
        $('.totalValor'+id).append(resultado);
      }

    });

    $("#salvarItem").on('click', function(e){
      e.preventDefault();
      
      // Supondo que o valor do produto está em um campo com id 'produto'
      const produtoId = $("#produto").val();
      if(produtoId != ""){
        // Gerar a URL usando o Laravel Blade
        const url = `{{ route('items-nova-encomenda-sem-fornecedora-ctualizar', ':produto') }}`.replace(':produto', produtoId);
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