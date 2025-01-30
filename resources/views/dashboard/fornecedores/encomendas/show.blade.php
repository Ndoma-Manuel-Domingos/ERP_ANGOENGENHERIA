@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Encomenda - {{ $encomenda->factura }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <div class="btn-group">
            <button type="button" class="btn btn-default">Opções</button>
            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" href="{{ route('fornecedores-encomendas.edit', $encomenda->id) }}">Editar</a>
              <a class="dropdown-item" href="{{ route('encomenda-receber-produto', $encomenda->id) }}">Receber Produtos</a>
              
              <a class="dropdown-item text-success" href="{{ route('encomenda-marcar-como-entregue', $encomenda->id) }}">Marcar como Entregue</a>
              <a class="dropdown-item text-danger" href="{{ route('encomenda-marcar-como-cancelada', $encomenda->id) }}">Marcar como Cancelada</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('imprimir-encomenda', $encomenda->id) }}" target="_blink">Imprimir PDF</a>
              <div class="dropdown-divider"></div>
              
              <form action="{{ route('fornecedores-encomendas.destroy', $encomenda->id ) }}" method="post">
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
                <h5>Nº da Encomenda: {{ $encomenda->factura ?? '--' }}</h5>  
              </div>
              <div class="card-body">
                <h6>Fornecedor:<a href="{{ route('fornecedores.show',  $encomenda->fornecedor->id) }}" class="float-right">{{ $encomenda->fornecedor->nome ?? '--' }}</a></h6>  
                <h6>Data da Encomenda:<span class="float-right">{{ $encomenda->data_emissao ?? '--' }}</span></h6>  
                <h6>Utilizador:<span class="float-right">{{ $encomenda->user->name ?? '--' }}</span></h6>  
              </div>  
            </div>  
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card">
              <div class="card-header">
                <h5>Dados da Entrega</h5>
              </div>
              <div class="card-body">
                <h6>Loja/Armazém:<span class="float-right">{{ $encomenda->loja->nome ?? '--' }}</span></h6>  
                <h6>Previsão de Entrega:<span class="float-right">{{ $encomenda->previsao_entrega ?? '--' }}</span></h6>  
                @if ($encomenda->status == 'pendente')
                  <h6>Estado:<span class="float-right bg-warning p-1 text-uppercase">{{ $encomenda->status ?? '--' }}</span></h6>    
                @endif

                @if ($encomenda->status == 'entregue')
                  <h6>Estado:<span class="float-right bg-primary p-1 text-uppercase">{{ $encomenda->status ?? '--' }}</span></h6>    
                @endif

                @if ($encomenda->status == 'cancelada')
                  <h6>Estado:<span class="float-right bg-danger p-1 text-uppercase">{{ $encomenda->status ?? '--' }}</span></h6>    
                @endif
                  
              </div>
            </div>
        </div>

        <div class="col-12"  id="accordion">
          <div class="card card-info card-outline">
              <a href="{{ route('encomenda-receber-produto', $encomenda->id) }}" class=" btn btn-info btn-sm"><i class="fas fa-plus-circle"></i> Receber Produtos</a>
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
                          <strong>Total: </strong> {{ number_format($encomenda->total, 2, ',', '.') }} {{ $loja->empresa->moeda }}
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
                            <td class="text-right">{{ number_format($item->total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
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
                            <td class="text-right text-uppercase" colspan="8">Transporte:</td>
                            <td class="text-right">{{ number_format($encomenda->custo_transporte, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                          </tr>                       
                          <tr>
                            <td class="text-right text-uppercase" colspan="8">Manuseamento:</td>
                            <td class="text-right">{{ number_format($encomenda->custo_manuseamento, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                          </tr>                       
                          <tr>
                            <td class="text-right text-uppercase" colspan="8">Outros Custos:</td>
                            <td class="text-right">{{ number_format($encomenda->outros_custos, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
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
          <div class="card card-primary card-outline">
              <a href="{{ route('encomenda-criar-factura-compra', $encomenda->id) }}" class=" btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Adicionar Facturas</a>
              <a class="d-block w-100" data-toggle="collapse" href="#adicionarFacturas">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-6">
                        <h4 class="card-title w-100 mb-2 text-left text-secondary">
                          >> Facturas ({{ $totalFactura }})
                        </h4>  
                      </div>

                      <div class="col-6">
                        <h4 class="card-title w-100 mb-2 text-right text-secondary">
                          <strong>Total: </strong> {{ number_format($totalValorFactura, 2, ',', '.') }} {{ $loja->empresa->moeda }}
                        </h4>  
                      </div>
                    </div>
                  </div>
              </a>
              <div id="adicionarFacturas" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        
                        <tr>
                          <th>Nº Fatura</th>
                          <th>Pago</th>
                          <th class="text-right">Data Fatura</th>
                          <th class="text-right">Data Vencimento</th>
                          <th class="text-right">Valor Fatura</th>
                          <th class="text-right">Total Pago	</th>
                          <th class="text-right">Saldo</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if ($facturas)
                          @foreach ($facturas as $item)
                           <tr>
                            <td><a href="{{ route('fornecedores-facturas-encomendas.show', $item->id) }}">{{  $item->factura }}</a></td>
                            <td>{{  $item->status == true ? 'Pago' : 'Não Pago' }}</td>
                            <td class="text-right">{{  $item->data_factura }}</td>
                            <td class="text-right">{{  $item->data_vencimento }}</td>
                            <td class="text-right text-danger">{{  number_format($item->valor_pago, 2, ',', '.') }}  {{ $loja->empresa->moeda }}</span>
                            <td class="text-right text-info">{{  number_format($item->total_pago, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                            <td class="text-right text-primary">{{ number_format($item->valor_pago - $item->total_pago, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                          </tr>    
                          @endforeach  
                          <tr>
                            <td colspan="4" class="text-right">Total: </td>
                            <td class="text-right text-uppercase text-danger" colspan="">{{ number_format($totalValorFactura, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                            <td class="text-right text-uppercase text-info" colspan="">{{ number_format($totalPagoFactura, 2, ',', '. ') }} {{ $loja->empresa->moeda }}</td>
                            <td class="text-right text-uppercase text-primary" colspan="">{{ number_format($totalValorFactura, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
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
              <a href="" class=" btn btn-success btn-sm"><i class="fas fa-plus-circle"></i> Adicionar Pagamentos</a>
              <a class="d-block w-100" data-toggle="collapse" href="#adicionarPagamentos">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-6">
                        <h4 class="card-title w-100 mb-2 text-left text-secondary">
                          >> Pagamentos ({{ $totalFacturaPaga }})
                        </h4>  
                      </div>

                      <div class="col-6">
                        <h4 class="card-title w-100 mb-2 text-right text-secondary">
                          <strong>Total: </strong> {{ number_format($totalValorPago, 2, ',', '.') }} {{ $loja->empresa->moeda }}
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
                        @if ($facturasPagas)
                          @foreach ($facturasPagas as $item)
                           <tr>
                            <td><a href="{{ route('fornecedores-facturas-encomendas.show', $item->id) }}">{{  $item->factura }}</a></td>
                            <td class="text-right"><span class="text-success">{{  $item->data_factura }}</span> <br> data Vencimento {{  $item->data_vencimento }}</td>
                            <td class="text-right">{{  number_format($item->valor_pago, 2, ',', '.') }}  {{ $loja->empresa->moeda }}</span>
                            <td><a href="{{ route('encomenda-liquidar-factura-compra', $item->id) }}" class="btn btn-primary btn-sm float-right">Pagamento</a></td>
                          </tr>    
                          @endforeach  
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