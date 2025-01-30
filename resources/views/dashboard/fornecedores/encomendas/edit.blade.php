@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Encomenda</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">fornecedor</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="card">
        <form action="{{ route('fornecedores-encomendas.update', $encomenda->id) }}" method="post" class="">
          @csrf
          @method('put')
          <div class="card-body row">
            <div class="col-12 col-md-12">
              <div class="form-group row">
                <label for="numero" class="col-sm-2 col-form-label text-right">Nº Encomenda:</label>
                <div class="col-sm-7 mb-3">
                  <input type="text" class="form-control" id="numero" name="numero" value="{{ $encomenda->factura }}"
                    placeholder="Número da Encomenda:">
                </div>

                <input type="hidden" name="encomenda_id" id="encomenda_id" value="{{ $encomenda->id }}">
                <p class="text-danger col-sm-3">
                  @error('numero')
                  {{ $message }}
                  @enderror
                </p>              
              </div>
            </div>

            <div class="col-12 col-md-12">
              <div class="form-group row">
                <label for="fornecedor_id" class="col-sm-2 col-form-label text-right">Fornecedor:</label>
                <div class="col-sm-7 mb-3">
                  <select class="form-control" id="fornecedor_selecionado" name="fornecedor_selecionado">
                    @foreach ($fornecedores as $fornecedor)
                      <option value="{{ $fornecedor->id}}" {{ $fornecedor->id == $encomenda->fornecedor->id ? 'selected': '' }}>{{ $fornecedor->nome }}</option>  
                    @endforeach
                  </select>
                </div>
                <p class="text-danger col-sm-3">
                  @error('fornecedor_selecionado')
                  {{ $message }}
                  @enderror
                </p>              
              </div>
            </div>

            <div class="col-12 col-md-12">
              <div class="form-group row">
                <label for="loja_id" class="col-sm-2 col-form-label text-right">Loja/Armazém:</label>
                <div class="col-sm-7 mb-3">
                  <select class="form-control" id="loja_id" name="loja_id">
                    @foreach ($lojas as $loja)
                      <option value="{{ $loja->id}}" {{ $loja->id == $encomenda->loja->id  ? 'selected': '' }}>{{ $loja->nome }}</option>  
                    @endforeach
                  </select>
                </div>
                <p class="text-danger col-sm-3">
                  @error('loja_id')
                  {{ $message }}
                  @enderror
                </p>              
              </div>
            </div>

            <div class="col-12 col-md-12">
              <div class="form-group row">
                <label for="data_previsao" class="col-sm-2 col-form-label text-right">Previsão de Entrega:</label>
                <div class="col-sm-7 mb-3">
                  <input type="date" class="form-control" id="data_previsao" name="data_previsao" value="{{ $encomenda->previsao_entrega }}"
                    placeholder="">
                </div>
                <p class="text-danger col-sm-3">
                  @error('data_previsao')
                  {{ $message }}
                  @enderror
                </p>              
              </div>
            </div>

            <div class="col-12 col-md-12">
              <div class="form-group row">
                <label for="observacao" class="col-sm-2 col-form-label text-right">Observações:</label>
                <div class="col-sm-7 mb-3">
                  <input type="text" class="form-control" id="observacao" name="observacao" value="{{ $encomenda->observacao }}" placeholder="Observações ">
                </div>
                <p class="text-danger col-sm-3">
                  @error('observacao')
                  {{ $message }}
                  @enderror
                </p>              
              </div>
            </div>

            <div class="col-12 col-md-12">
              <div class="form-group row">
                <label for="produto" class="col-sm-2 col-form-label text-right">Pesquisar Produto:</label>
                <div class="col-sm-6 mb-3">
                  <select class="form-control select2" id="produto" name="produto">
                    <option value="">Selecione o produto</option>
                    @if ($produtos)
                      @foreach ($produtos as $item2)
                        <option value="{{ $item2->id }}">{{ $item2->nome }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="col-sm-2">
                  <a href="" class="btn btn-primary" id="salvarItem">Confirmar</a>             
                </div>
              </div>
            </div>
            @if ($items)
            <div class="col-12">
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
                        <input type="text" class="form-control iva" value="{{ $item->iva ?? 0 }}" name="iva{{ $item->id }}" id="{{ $item->id }}">
                      </td>
                      <td>
                        <input type="text" class="form-control desonto" value="{{ $item->desconto ?? 0 }}" name="desonto{{ $item->id }}" id="{{ $item->id }}">
                      </td>
                      <td class="totalValor{{ $item->id }}" id="{{ $item->id }}">
                        {{ $item->totalSiva ?? '' }}
                      </td>
                      <input type="hidden" name="ids[]" value="{{ $item->id }}">
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-success">Actualizar</button>
          </div>
        </form>
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
      
      
      
      // Obter os valores dos campos
      const produtoId = $("#produto").val();
      const encomendaId = $("#encomenda_id").val();
      
      if(produtoId != ""){
        // Gerar a URL com múltiplos parâmetros
        const url = `{{ route('items-nova-encomenda-sem-fornecedora-editar', [':produto', ':encomenda_id']) }}`
            .replace(':produto', produtoId)
            .replace(':encomenda_id', encomendaId);
        
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