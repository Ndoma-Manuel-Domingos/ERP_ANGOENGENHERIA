@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe Factura - {{ $factura->factura }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <div class="btn-group">
            <button type="button" class="btn btn-default">Opções</button>
            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="{{ route('fornecedores-encomendas.edit', $factura->id) }}">Editar</a>
              <a class="dropdown-item" href="{{ route('encomenda-liquidar-factura-compra', $factura->id) }}">Liquidar Factura</a>
              <a class="dropdown-item" href="{{ route('encomenda-duplicar-factura', $factura->id) }}">Duplicar Factura</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Imprimir PDF</a>
              <div class="dropdown-divider"></div>
              
              <form action="{{ route('fornecedores-encomendas.destroy', $factura->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item"
                  onclick="return confirm('Tens Certeza que Desejas excluir esta Encomenda?')">
                  Apagar Encomenda
                </button>
              </form>
            </div>
          </div>
        
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
              <div class="card-header">
                <h5>Nº Factura: {{ $factura->factura ?? '--' }}</h5>  
              </div>
              <div class="card-body">
                <h6>Fornecedor:<a href="{{ route('fornecedores.show',  $factura->fornecedor->id) }}" class="float-right">{{ $factura->fornecedor->nome ?? '--' }}</a></h6>  
                <h6>Data da Factura:<span class="float-right">{{ $factura->data_factura ?? '--' }}</span></h6>  
                <h6>Data Vencimento:<span class="float-right">{{ $factura->data_vencimento ?? '--' }}</span></h6>  
              </div>  
            </div>  
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
              <div class="card-header">
                <h5>Totais</h5>
              </div>
              <div class="card-body">
                <h6>Valor Factura:<span class="float-right">{{ number_format($factura->valor_factura ?? '0', 2, ',', '.') }} {{ $loja->empresa->moeda }}</span></h6>  
                <h6>Valor A Pago (No Momento):<span class="float-right">{{ number_format($factura->valor_pago ?? '0' , 2, ',', '.')}} {{ $loja->empresa->moeda }}</span></h6>  
                <h6>Em Dívida:<span class="float-right">{{ number_format($factura->valor_divida ?? '0', 2, ',', '.') }} {{ $loja->empresa->moeda }}</span></h6>  
              </div>
            </div>
        </div>

        <div class="col-12"  id="accordion">
          <div class="card card-info card-outline">
              <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-6">
                        <h4 class="card-title w-100 mb-2 text-left text-secondary">
                          >> Produtos (0% recebidos)
                        </h4>  
                      </div>

                      <div class="col-6">
                        <h4 class="card-title w-100 mb-2 text-right text-secondary">
                          <strong>Total: </strong> {{ number_format($factura->valor_factura, 2, ',', '.') }} {{ $loja->empresa->moeda }}
                        </h4>  
                      </div>
                    </div>
                  </div>
              </a>
              <div id="collapseTwo" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th></th>
                          <th></th>
                          <th colspan="4" class="text-center bg-light">P. Custo</th>
                          <th colspan="2" class="text-center bg-secondary">Qtd</th>
                          <th></th>
                        </tr>
                        <tr>
                          <th>Ref.</th>
                          <th>Produto</th>
                          <th class="text-center bg-light">IVA</th>
                          <th class="text-center bg-light">Desconto</th>
                          <th class="text-center bg-light">Encomendado</th>
                          <th class="text-center bg-light">Atual</th>
                          <th class="text-center bg-secondary">Encomendado</th>
                          <th class="text-center bg-secondary">Recebido</th>
                          <th class="text-right">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if ($items)
                          @foreach ($items as $item)
                           <tr>
                            <td>{{  $item->produto->referencia }}</td>
                            <td>{{  $item->produto->nome }}</td>
                            <td class="text-center">{{  $item->iva }} %</td>
                            <td class="text-center">{{  $item->desconto }} %</td>
                            <td class="text-center">
                              @if ($item->custo != $item->produto->preco_custo)
                                <span style="text-decoration: line-through;">{{  number_format($item->produto->preco_custo, 2, ',', '.') }}  {{ $loja->empresa->moeda }} |</span>
                              @endif
                              <span>{{  number_format($item->preco_venda, 2, ',', '.') }} {{ $loja->empresa->moeda }}</span></td>
                            <td class="text-center">{{  number_format($item->produto->preco_custo, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                            <td class="text-center">{{  $item->quantidade ?? 0 }} Uni</td>
                            <td class="text-center">{{  $item->quantidade_recebida ?? 0 }} Uni</td>
                            <td class="text-right">{{ number_format($item->totalSiva, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                          </tr>    
                          @endforeach  
                          <tr>
                            <td class="text-right text-uppercase" colspan="8">SubTotal:</td>
                            <td class="text-right">{{ number_format($encomenda->total_sIva, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                          </tr>  
                          <tr>
                            <td class="text-right text-uppercase" colspan="8">Descontos:</td>
                            <td class="text-right">{{ number_format($encomenda->desconto, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                          </tr> 
                          <tr>
                            <td class="text-right text-uppercase" colspan="8">Imposto:</td>
                            <td class="text-right">{{ number_format($encomenda->imposto, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                          </tr>  
                          <tr>
                            <td class="text-right text-uppercase" colspan="8">Total:</td>
                            <td class="text-right">{{ number_format($encomenda->total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                          </tr>                       
                        @endif
                      </tbody>
                  </table>
                  </div>
              </div>
          </div>
        </div>

        <div class="col-12"  id="accordion">
          <div class="card card-success card-outline">
              <a class="d-block w-100" data-toggle="collapse" href="#adicionarPagamentos">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-6">
                        <h4 class="card-title w-100 mb-2 text-left text-secondary">
                          >> Pagamentos ({{ $totalFacturaJPagas }})
                        </h4>  
                      </div>

                      <div class="col-6">
                        <h4 class="card-title w-100 mb-2 text-right text-secondary">
                          <strong>Total: </strong> {{ number_format($totalValorFacturaJPagas, 2, ',', '.') }} {{ $loja->empresa->moeda }}
                        </h4>  
                      </div>
                    </div>
                  </div>
              </a>
              <div id="adicionarPagamentos" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        
                        <tr>
                          <th>Nº Fatura</th>
                          <th class="text-right">Data Pagamento</th>
                          <th class="text-right">Valor</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @if ($facturasPagas && count($facturasPagas) > 0)
                          @foreach ($facturasPagas as $item)
                           <tr>
                            <td><a href="{{ route('fornecedores-facturas-encomendas.show', $item->id) }}">{{  $item->factura }}</a></td>
                            <td class="text-right"><span class="text-success"> <i class="fas fa-check-circle"></i> {{  $item->data_factura }}</span> <br> Vencimento {{  $item->data_vencimento }}</td>
                            <td class="text-right">{{  number_format($item->valor_pago, 2, ',', '.') }}  {{ $loja->empresa->moeda }}</span>
                            <td>

                              <form action="{{ route('fornecedores-facturas-encomendas.destroy', $item->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger mx-1 float-right"
                                  onclick="return confirm('Tens Certeza que Desejas excluir Este pagamento?')">
                                  <i class="fas fa-trash"></i>
                                </button>
                              </form>
                              
                              {{-- <a href="{{ route('encomenda-liquidar-factura-compra', $item->id) }}" class="btn btn-danger btn-sm float-right">
                                <i class="fas fa-close"></i> Apagar
                              </a> --}}
                            </td>
                          </tr>    
                          @endforeach  
                        @else
                          <tr>
                            <td colspan="4">Não existem Pagamentos. <br>
                              Adicione Facturas relacionadas com esta encomenda e posteriomente registe os respetivos pagamentos.</td>
                          </tr>
                        @endif
                      </tbody>
                  </table>
                  </div>
              </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection