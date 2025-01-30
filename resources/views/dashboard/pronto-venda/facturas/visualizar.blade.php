@extends('layouts.vendas')

@section('section')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Factura - {{ $factura->factura_next }}</h1>
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
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-7 bg-white p-2 mb-4" id="accordion">
  
              <div class="row">

                <div class="col-4">
                    <h6><strong>Documento</strong></h6>
                    <hr>
                    Data: {{ date_format($factura->created_at, "d-m-Y") }} <br>
                    Tipo: {{ $factura->exibir_factura($factura->factura) }}
                </div>
                <div class="col-8">
                    <h6><strong>Cliente</strong></h6>
                    <hr>
                    <p>{{ $factura->cliente->nome }} {{ $factura->cliente->nif ?? '------' }}</p>
                </div>
            
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                        </div>
                        <div class="col-6">

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
                                    <span class="float-right">{{ number_format(0, 2, ',', '.') }} {{ $loja->empresa->moeda }}</span>
                                </td>  
                                </tr>
                                <tr>
                                <td>Por Pagar</td>
                                <td>
                                    <span class="float-right">{{ number_format($factura->valor_total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</span>
                                </td>  
                                </tr>
                            </tbody>
                            </table>
                        @endif
                        </div>
                    </div>
                </div>
              </div>


                <h6><strong>Observação</strong></h6>
                <hr>
                {{ $factura->observacao == "" ? 'Documento emitido para fins de Formação. Não tem validade fiscal.' : $factura->observacao  }}
           

                @if ($movimentos)
                <table class="table table-head-fixed mt-5">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th class="text-right" style="width: 25%">P.Unit.</th>
                            <th class="text-center" style="width: 5%">Qtd</th>
                            <th class="text-right" style="width: 25%">Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($movimentos as $item)
                        <tr>
                            <td>{{ $item->produto->nome }}</td>
                            <td class="text-right">{{ number_format($item->preco_unitario, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                            <td class="text-center">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($item->valor_pagar, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="row mt-5">
                    <div class="col-5">
                        
                        <table class="table">
                            <tr>
                                <th>Pagamento</th>
                                <th class="text-right">Total</th>  
                            </tr>
    
                            <tr>
                                <td class="text-uppercase">{{ $factura->pagamento }}</td>
                                <td class="text-right">{{ number_format($total_pagar, 2, ',', '.') }} {{ $loja->empresa->moeda }} </td>  
                            </tr>
                        </table> 
                    </div>
                    <div class="col-7">
                        <table class="table">
                            <tr>
                                <th>Taxa</th>
                                <th>Base</th>
                                <th>IVA</th>
                                <th class="text-right">Total</th>
                            </tr>
                            <tr>
                                <td>{{ $taxta }} %</td>
                                <td>{{ number_format($valorBase, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                <td>{{ number_format($valorIva, 2, ',', '.') }} {{ $loja->empresa->moeda }} </td>
                                <td class="text-right">{{ number_format($valorBase + $valorIva, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                            </tr> 
                        </table> 
                    </div>
                </div>

                @if ($factura->status == "por pagar")
                    <div class="row">
                        <div class="col-6">
                            <table class="table">
                            <tr>
                                <th>Método de Pagamento</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td>A Pagar: {{ $factura->data_vencimento }}</td>
                                <td class="text-right">{{ number_format($total_pagar, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                            </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table">
                            <tr>
                                <td>Tipo</td>
                                <td>Base</td>
                                <td>IVA</td>
                                <td class="text-right">Total</td>
                            </tr>
                            @foreach ($movimentos as $item)
                            <tr>
                                <td>{{ $item->produto->taxa_imposto->valor }} %</td>
                                <td>{{ number_format($item->valor_base, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                <td>{{ number_format($item->valor_iva, 2, ',', '.') }} {{ $loja->empresa->moeda }} </td>
                                <td class="text-right">{{ number_format($item->valor_base + $item->valor_iva, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                            </tr> 
                            @endforeach
                            </table>
                        </div>
                    </div> 
                    @else
                        <div class="row">
                            <div class="col-5"></div>  
                            <div class="col-7">
                                <table class="table">
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-right">{{ number_format($factura->valor_total, 2, ',', '.')  }}  {{ $loja->empresa->moeda }}</th>
                                    </tr>
                                    @if ($factura->status_factura == "anulada")
                                    <tr>
                                        <td class="text-uppercase"></td>
                                        <td class="text-right text-danger">FACTURA ANULADA <i class="fas fa-cancel"></i> </td>
                                    </tr>    
                                    @endif
                                </table>
                            </div>  
                        </div>   
                    @endif
                @endif

              <h5><i class="fas fa-user"></i> Utitlizador</h>

              <div class="bg-light p-2">
                <h6>
                    <strong>{{ $factura->user->name }} </strong><br>
                    {{ $factura->caixa->nome }} <br>
                    {{ $factura->caixa->loja->nome }} <br>
                    {{ date_format($factura->created_at, "d/m/Y  h:i:s") }}
                </h6>
              </div>
            </div>

            <div class="col-12 col-md-4 bg-white p-2 mb-4 ml-1" id="accordion">
                <h5 class="bg-white p-2">Ações do Documento</h5>
                <div class="">
                    <a href="{{ route('pronto-venda.facturas-documento', [$factura->id, "sem-download"]) }}" target="_blink" class="btn-block btn btn-info text-left" target="_blink"><i class="fas fa-print"></i> Imprimir</a>
                </div>
                
                @if ($factura->status_factura == "por pagar")
                <div class="mt-3">
                    <a href="{{ route('facturas.edit', $factura->id) }}" class="btn-block btn btn-info text-left"><i class="fas fa-edit"></i> Retificar</a>
                </div>

                <div class="mt-3">
                    <a href="{{ route('anular-factura', $factura->id) }}" class="btn-block btn btn-info text-left"><i class="fas fa-cancel"></i> Anular</a>
                </div> 
                @endif

                @if ($factura->status_factura == "por pagar")
                    <div class="mt-3 btn-block btn btn-warning text-left"><i class="fas fa-close"></i> Factura Não Pago</div> 
                @else
                    @if ($factura->status_factura == "pago")
                        <div class="mt-3 btn-block btn btn-success text-left"><i class="fas fa-check"></i> Pago na totalidade</div>   
                    @endif
                @endif

                <div class="mt-3">
                    <a href="{{ route('pronto-venda.facturas-documento', [$factura->id, "download"]) }}" class="btn-block btn btn-info text-left"><i class="fas fa-download"></i> Download</a>
                </div>
           
  
              @if ($factura->status_factura == "por pagar")
              <div class="cards mt-2">
                <a class="d-block w-100" data-toggle="" href="#desconto">
                  <div class="card-header bg-light">
                    <h4 class="card-title w-100">
                      Emitir Recibo
                    </h4>
                  </div>
                </a>
                <div id="desconto" class="" data-parent="#accordion">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <div class="input-group date" id="timepicker">
                            <input type="text" class="form-control" value="{{ $total_pagar }}" name="desconto" data-target="#timepicker" />
                            <div class="input-group-append" data-target="#timepicker">
                              <div class="input-group-text">Kz</div>
                            </div>
                          </div>
                          <!-- /.input group -->
                        </div>
                      </div>
                    </div>
  
                  </div>
                </div>
  
                <div class="card-footer mt-3">
                  <button type="submit" class="btn btn-primary" id="botao_submit">Emitir Recibo</button>
                </div>
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


