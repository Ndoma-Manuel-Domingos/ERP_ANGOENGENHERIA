@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Duplicar-factura</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('fornecedores-facturas-encomendas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Facturas</li>
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
        <form action="{{ route('encomenda-duplicar-factura-store') }}" method="post" class="">
          @csrf
          <div class="card-body row">

            <div class="col-12 col-md-12">
                <div class="form-group row">
                  <label for="loja_id" class="col-sm-2 col-form-label text-right">Fornecedor:</label>
                  <div class="col-sm-7 mb-3">
                    <select class="form-control" id="fornecedor_id" name="fornecedor_id">
                       @foreach ($fornecedores as $item)
                          <option value="{{  $item->id }}" {{ $factura->fornecedor_id == $item->fornecedor_id ? 'selected' : '' }} >{{ $item->nome }}</option>    
                       @endforeach
                    </select>
                  </div>
                  <p class="text-danger col-sm-3">
                    @error('fornecedor_id')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
            </div>


            <div class="col-12 col-md-12">
              <div class="form-group row">
                <label for="numero" class="col-sm-2 col-form-label text-right">Nº Factura:</label>
                <div class="col-sm-7 mb-3">
                  <input type="text" class="form-control" id="factura" name="factura" value="{{ $factura->factura }}"
                    placeholder="Número da Factura:">
                </div>
                <p class="text-danger col-sm-3">
                  @error('factura')
                  {{ $message }}
                  @enderror
                </p>              
              </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group row">
                  <label for="numero" class="col-sm-2 col-form-label text-right">Valor da Factura:</label>
                  <div class="col-sm-7 mb-3">
                    <input type="text" class="form-control" id="valor_factura" name="valor_factura" value="{{ $factura->valor_factura }}"
                      placeholder="Valor da Factura:">
                  </div>
                  <p class="text-danger col-sm-3">
                    @error('valor_factura')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group row">
                  <label for="numero" class="col-sm-2 col-form-label text-right">Desconto %:</label>
                  <div class="col-sm-7 mb-3">
                    <input type="text" class="form-control" id="desconto" name="desconto" value="{{ $factura->desconto }}"
                      placeholder="Desconto:">
                  </div>
                  <p class="text-danger col-sm-3">
                    @error('desconto')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group row">
                  <label for="numero" class="col-sm-2 col-form-label text-right">Data Factura</label>
                  <div class="col-sm-7 mb-3">
                    <input type="date" class="form-control" id="data_factura" name="data_factura" value="{{ $factura->data_factura }}"
                      placeholder="Data factura:">
                  </div>
                  <p class="text-danger col-sm-3">
                    @error('data_factura')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group row">
                  <label for="numero" class="col-sm-2 col-form-label text-right">Data Vencimento:</label>
                  <div class="col-sm-7 mb-3">
                    <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ $factura->data_vencimento }}"
                      placeholder="Data Vencimento:">
                  </div>
                  <p class="text-danger col-sm-3">
                    @error('data_vencimento')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
            </div>


            <div class="col-12 col-md-12">
              <div class="form-group row">
                <label for="observacao" class="col-sm-2 col-form-label text-right">Observações:</label>
                <div class="col-sm-7 mb-3">
                  <input type="text" class="form-control" id="observacao" name="observacao" value="{{ $factura->observacao }}" placeholder="Observações ">
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
                  <label for="loja_id" class="col-sm-2 col-form-label text-right">Marcar como paga :</label>
                  <div class="col-sm-7 mb-3">
                    <select class="form-control" id="marcar_como" name="marcar_como">
                        <option value="nao">Não</option>  
                        <option value="sim">Sim</option>  
                    </select>
                  </div>
                  <p class="text-danger col-sm-3">
                    @error('marcar_como')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group row">
                  <label for="observacao" class="col-sm-2 col-form-label text-right">Informe o Valor da factura caso queras pagar:</label>
                  <div class="col-sm-7 mb-3">
                    <input type="text" class="form-control" id="valor_pagar" name="valor_pagar" value="{{ old('valor_pagar') }}" placeholder="Informe o Valor da factura caso queras pagar">
                  </div>
                  <p class="text-danger col-sm-3">
                    @error('valor_pagar')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
            </div>

            <input type="hidden" name="encomenda_id" value="{{ $factura->encomenda_id }}">
            <input type="hidden" name="factura_id" value="{{ $factura->id }}">

          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="reset" class="btn btn-danger">Cancelar</button>
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