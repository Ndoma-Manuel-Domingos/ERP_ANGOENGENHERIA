@extends('layouts.vendas')

@section('section')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Trocar Itens da Facturas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                <li class="breadcrumb-item active">Stock</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <!-- /.row -->
      <div class="row">
        <div class="col-12 col-md-2"></div>
        <div class="col-12 col-md-8">
          <div class="card">
            <div class="card-body">
                <div class="bs-stepper">
                  <div class="bs-stepper-header bg-light mb-3" role="tablist">
                    <!-- your steps here -->
                    <div class="step" data-target="#identificarFactura">
                      <button type="button" class="step-trigger" role="tab" aria-controls="identificarFactura" id="identificarFactura-trigger">
                        <span class="bs-stepper-circle">1</span>
                        <span class="bs-stepper-label">Identificação da Factura</span>
                      </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#itensParaTroca">
                      <button type="button" class="step-trigger" role="tab" aria-controls="itensParaTroca" id="itensParaTroca-trigger">
                        <span class="bs-stepper-circle">2</span>
                        <span class="bs-stepper-label">Itens para troca</span>
                      </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#valorAdevolver">
                      <button type="button" class="step-trigger" role="tab" aria-controls="valorAdevolver" id="valorAdevolver-trigger">
                        <span class="bs-stepper-circle">3</span>
                        <span class="bs-stepper-label">Valor a devolver</span>
                      </button>
                    </div>

                    <div class="line"></div>
                    <div class="step" data-target="#Motivo">
                      <button type="button" class="step-trigger" role="tab" aria-controls="Motivo" id="Motivo-trigger">
                        <span class="bs-stepper-circle">4</span>
                        <span class="bs-stepper-label">Motivo </span>
                      </button>
                    </div>

                    <div class="line"></div>
                    <div class="step" data-target="#finalizar">
                      <button type="button" class="step-trigger" role="tab" aria-controls="finalizar" id="finalizar-trigger">
                        <span class="bs-stepper-circle">5</span>
                        <span class="bs-stepper-label">Finalizar</span>
                      </button>
                    </div>
                  </div>
                  <div class="bs-stepper-content">
                    <form action="{{ route('pronto-venda.facturas-trocarItens-create') }}" method="post">
                      @csrf
                      <!-- your steps content here -->
                      <div id="identificarFactura" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                          <h5> >> Indique o número da factura</h5>
                            <div class="form-group mt-4">
                                <div class="input-group input-group">
                                    <select type="search" class="form-control form-control select2" id="buscar_factura" name="buscar_factura" placeholder="Indique o número da factura" value="">
                                      @if ($facturas)
                                          @foreach ($facturas as $item)
                                            <option value="{{ $item->id }}">{{ $item->factura_next }}</option>
                                          @endforeach
                                      @endif
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-default" class="buscarFactura" id="buscarFactura">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>  
                          
                          <button class="btn btn-primary buscarFactura">Avançar</button>
                      </div>

                      <div id="itensParaTroca" class="content" role="tabpanel" aria-labelledby="itensParaTroca-trigger">
                        <h5> >> Escolha os itens e respectiva quantidade dos itens sujeitos a troca</h5>
                          <table class="table table-head-fixed text-nowrap px-5">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th style="width: 140px" class="text-center">Qtd</th>
                                    <th class="text-right">Preço</th>
                                </tr>
                            </thead>
                            <tbody id="tableMovimento">
                            </tbody>    
                            <tfoot id="">
                              <th colspan="3" class="text-right h3" id="somatorio"></th>
                            </tfoot>
                          </table>
                          <a class="btn btn-primary" onclick="stepper.previous()">Recuar</a>
                          <a class="btn btn-primary" onclick="stepper.next()">Avançar</a>
                      </div>

                      <div id="valorAdevolver" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                        <h5> >> Selecione de que forma irá efetuar a devolução do valor</h5>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox bg-light py-3">
                              <input class="custom-control-input" name="forma_pagamento" type="radio" id="dinheiro" value="dinheiro" checked>
                              <label for="dinheiro" class="custom-control-label">Dinheiro</label>
                            </div>
                            <div class="custom-control custom-checkbox bg-light py-3">
                              <input class="custom-control-input" name="forma_pagamento" type="radio" id="multibanco" value="multibanco" >
                              <label for="multibanco" class="custom-control-label">Multibanco</label>
                            </div>
                            <div class="custom-control custom-checkbox bg-light py-3">
                              <input class="custom-control-input" name="forma_pagamento" type="radio" id="cartao_credito" value="cartao_credito" >
                              <label for="cartao_credito" class="custom-control-label">Cartão Crédito</label>
                            </div>
        
                            <div class="custom-control custom-checkbox bg-light py-3">
                              <input class="custom-control-input" name="forma_pagamento" type="radio" id="compensao" value="compensao" >
                              <label for="compensao" class="custom-control-label">Compensão de Saldos(C/C)</label>
                            </div>
        
                          </div>
                          <a class="btn btn-primary" onclick="stepper.previous()">Recuar</a>
                          <a class="btn btn-primary" onclick="stepper.next()">Avançar</a>
                      </div>

                      <div id="Motivo" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                        <h5> >> Selecione ou insira um motivo para a troca</h5>
                        <div class="form-group ">
                          <div class="custom-control custom-checkbox bg-light py-3">
                            <input class="custom-control-input" name="motivo" type="radio" id="devolucao_produto_motivo" value="devolucao_produto_motivo" checked>
                            <label for="devolucao_produto_motivo" class="custom-control-label">Devolução de Produtos</label>
                          </div>
                          <div class="custom-control custom-checkbox bg-light py-3">
                            <input class="custom-control-input" name="motivo" type="radio" id="erro_factura_motivo" value="erro_factura_motivo" >
                            <label for="erro_factura_motivo" class="custom-control-label">Erro na factura</label>
                          </div>
                          <div class="custom-control custom-checkbox bg-light py-3">
                            <input class="custom-control-input" name="motivo" type="radio" id="troca_produto_motivo" value="troca_produto_motivo" >
                            <label for="troca_produto_motivo" class="custom-control-label">Troca de Produtos</label>
                          </div>
                          <div class="custom-control custom-checkbox bg-light py-3">
                            <input class="custom-control-input" name="motivo" type="radio" id="outros_motivo" value="outros_motivo" >
                            <label for="outros_motivo" class="custom-control-label">Outros</label>
                          </div>
                      
                        </div>
                          <a class="btn btn-primary" onclick="stepper.previous()">Recuar</a>
                          <a class="btn btn-primary" onclick="stepper.next()">Avançar</a>
                      </div>

                      <div id="finalizar" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                        <h5> >> Confirme os procedimentos para finalizar a troca</h5>
                        <h6 class="bg-light p-4"><span class="text-success"><i class="fas fa-check"></i></span> Troca de Itens - FT T01P2022/5</h6>
                        <h6 class="bg-light p-4"><span class="text-success"><i class="fas fa-check"></i></span> Será criada uma Nota de Crédito</h6>
                        <h6 class="bg-light p-4"><span class="text-success"><i class="fas fa-check"></i></span> Motivo: Devolução de Produtos</h6>
                        <h6 class="bg-light p-4"><span class="text-success"><i class="fas fa-check"></i></span> Imprima e entregue o Documento ao Cliente</h6>
                        <a class="btn btn-primary" onclick="stepper.previous()">Recuar</a>
                        <button type="submit" class="btn btn-primary">Terminar</button>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
          </div>
          <!-- /.card -->          
        </div>
        <div class="col-12 col-md-2"></div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection


@section('scripts')
<script>

    $(function(){
      $(".buscarFactura").on('click', function(e){
        e.preventDefault();
        data = {
          'factura': $("#buscar_factura").val(),
        };

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "GET",
          url: "{{ route('pronto-venda.facturas-buscar') }}",
          data: data,
          dataType: "json",
          success: function (response){
            if(response.status == 200){
              $("#tableMovimento").html("");
              for (let index = 0; index < response.movimentos.length; index++) {
                $("#tableMovimento").append('<tr><td>'+ response.movimentos[index].produto.nome+'</td>\
                  <td><input type="text" name="quantidade'+response.movimentos[index].id+'" value="'+ response.movimentos[index].quantidade+'" class="form-control"></td>\
                  <td class="text-right">'+ response.movimentos[index].valor_pagar+'</td>\
                  <input type="hidden" name="facturasHidden[]" value="'+ response.movimentos[index].id+'"/>\
                  </tr>');
              }

              $("#somatorio").html(response.totalPagar);
              stepper.next();
            }else{
              alert("Factura não Registrada");
            }
            
          }
        }); 

      })
      
    });
  // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
      window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    });

</script>
@endsection