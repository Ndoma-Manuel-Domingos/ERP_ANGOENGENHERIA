@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe Fornecedor</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Fornecedor</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">

    <div class="card">
      <div class="card-body">
        <div class="row">

          <div class="col-12 col-md-6">
            <table class="table text-nowrap">
              <tbody>
                <tr>
                  <th>Nome</th>
                  <td class="text-right">{{ $fornecedor->nome ?? '-------------' }}</td>
                </tr>
                <tr>
                  <th>Tipo Pessoa</th>
                  <td class="text-right text-uppercase">{{ $fornecedor->tipo_pessoa ?? '-------------' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="col-12 col-md-6">
            <table class="table text-nowrap">
              <tbody>
                <tr>
                  <th>NIF</th>
                  <td class="text-right">{{ $fornecedor->nif ?? '-------------' }}</td>
                </tr>
                
                <tr>
                  <th>Tipo Fornecedor</th>
                  <td class="text-right text-uppercase">{{ $fornecedor->tipo_fornecedor ?? '-------------' }}</td>
                </tr>

                <tr>
                  <td>{{ $fornecedor->pais }}</td>
                  <td class="text-right">{{ $fornecedor->pais ?? '-------------' }}</td>
                </tr>
                
              </tbody>
            </table>
          </div>

          <div class="col-12 col-md-12">
            <table class="table text-nowrap">
              <tbody>
                {{-- -------------------------------------------- --}}
                <tr>
                  <th colspan="4">Morada & Contactos</th>
                </tr>
                <tr>
                  <td colspan="2">Morada: {{ $fornecedor->morada ?? '-------------' }} <br>{{ $fornecedor->codigo_postal ?? '-------------' }}</td>
                  <td>Telefone: {{ $fornecedor->telefone ?? '-------------' }}</td>
                  <td>Telemóvel: {{ $fornecedor->telemovel ?? '-------------' }}</td>
                </tr>

                <tr>
                  <th colspan="2">Observação</th>
                  <th>E-mail</th>
                  <th>Website</th>
                </tr>

                <tr>
                  <td colspan="2">{{ $fornecedor->observacao ?? '-------------' }}</td>
                  <td>{{ $fornecedor->email ?? '-------------' }}</td>
                  <td>{{ $fornecedor->website ?? '-------------' }}</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12" id="accordion">

          <div class="card card-success card-outline">
              <a href="{{ route('fornecedores-nova-encomenda', $fornecedor->id ) }}" class=" btn btn-success btn-sm"><i class="fas fa-plus-circle"></i> Adicionar Encomenda</a>
              <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                  <div class="card-header">
                      <h4 class="card-title w-100 mb-2">
                        >> Encomendas ({{ $totalEncomendas }})
                      </h4>
                  </div>
              </a>
              <div id="collapseOne" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                      
                    <div class="card-outline-tabs">
                      <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Pendentes</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Entregues</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Cancelados</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                          <div class="tab-pane fade show active table-responsive p-0" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                             <table class="table table-hover text-nowrap">
                                <thead>
                                  <tr>
                                    <th style="width: 30%">Nº Encomenda</th>
                                    <th>Data</th>
                                    <th>Data Entrega</th>
                                    <th>Estado</th>
                                    <th>Nº Produto</th>
                                    <th>Total S/IVA</th>
                                    <th>Total</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                @if ($encomendasPendetes && count($encomendasPendetes) != 0)
                                  @foreach ($encomendasPendetes as $pendente)
                                    <tr>
                                      <td style="width: 30%"><a href="{{ route('fornecedores-encomendas.show', $pendente->id) }}">{{ $pendente->factura }}</a></td>
                                      <td><a href="{{ route('fornecedores-encomendas.show', $pendente->id) }}">{{ $pendente->data_emissao }}</a></td>
                                      <td>{{ $pendente->previsao_entrega }}</td>
                                      <td><span class="bg-warning text-white p-1 text-uppercase">{{ $pendente->status }}</span></td>
                                      <td>{{ number_format($pendente->total_produto, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                      <td>{{ number_format($pendente->total_sIva, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                      <td>{{ number_format($pendente->total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                      <td>
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-default">Opções</button>
                                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            <span class="sr-only">Toggle Dropdown</span>
                                          </button>
                                          <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="#">Detalhes</a>
                                            <a class="dropdown-item" href="#">Editar</a>
                                            <a class="dropdown-item" href="{{ route('encomenda-receber-produto', $pendente->id) }}">Receber Encomenda</a>
                                            <div class="dropdown-divider"></div>
                                            
                                            <form action="{{ route('fornecedores-encomendas.destroy', $pendente->id ) }}" method="post">
                                              @csrf
                                              @method('DELETE')
                                              <button type="submit" class="dropdown-item"
                                                onclick="return confirm('Tens Certeza que Desejas excluir esta Encomenda?')">
                                                Apagar Encomenda
                                              </button>
                                            </form>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>    
                                  @endforeach
                                @else
                                  <tr>
                                    <td colspan="8">Não foram encontrados resultados</td>
                                  </tr>
                                @endif
                                </tbody>
                             </table>
                          </div>
                          <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                            <table class="table table-hover text-nowrap">
                              <thead>
                                <tr>
                                  <th style="width: 30%">Nº Encomenda</th>
                                  <th>Data</th>
                                  <th>Data Entrega</th>
                                  <th>Estado</th>
                                  <th>Nº Produto</th>
                                  <th>Total S/IVA</th>
                                  <th>Total</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                              @if ($encomendasEntregues && count($encomendasEntregues) != 0)
                                @foreach ($encomendasEntregues as $entregue)
                                  <tr>
                                    <td style="width: 30%"><a href="{{ route('fornecedores-encomendas.show', $entregue->id) }}">{{ $entregue->factura }}</a></td>
                                    <td><a href="{{ route('fornecedores-encomendas.show', $entregue->id) }}">{{ $entregue->data_emissao }}</a></td>
                                    <td>{{ $entregue->previsao_entrega }}</td>
                                    <td><span class="bg-primary text-white p-1 text-uppercase">{{ $entregue->status }}</span></td>
                                    <td>{{ number_format($entregue->total_produto, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                    <td>{{ number_format($entregue->total_sIva, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                    <td>{{ number_format($entregue->total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                    <td>

                                      <div class="btn-group">
                                        <button type="button" class="btn btn-default">Opções</button>
                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                          <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                          <a class="dropdown-item" href="#">Detalhes</a>
                                          <a class="dropdown-item" href="#">Editar</a>
                                          <a class="dropdown-item" href="{{ route('encomenda-receber-produto', $entregue->id) }}">Receber Encomenda</a>
                                          <div class="dropdown-divider"></div>
                                          
                                          <form action="{{ route('fornecedores-encomendas.destroy', $entregue->id ) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                              onclick="return confirm('Tens Certeza que Desejas excluir esta Encomenda?')">
                                              Apagar Encomenda
                                            </button>
                                          </form>
                                        </div>
                                      </div>

                                    </td>
                                  </tr>    
                                @endforeach
                              @else
                                <tr>
                                  <td colspan="8">Não foram encontrados resultados</td>
                                </tr>
                              @endif
                              </tbody>
                           </table>
                          </div>
                          <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                            <table class="table table-hover text-nowrap">
                              <thead>
                                <tr>
                                  <th style="width: 30%">Nº Encomenda</th>
                                  <th>Data</th>
                                  <th>Data Entrega</th>
                                  <th>Estado</th>
                                  <th>Nº Produto</th>
                                  <th>Total S/IVA</th>
                                  <th>Total</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                              @if ($encomendascanceladas && count($encomendascanceladas) != 0)
                                @foreach ($encomendascanceladas as $cancelada)
                                  <tr>
                                    <td style="width: 30%"><a href="{{ route('fornecedores-encomendas.show', $cancelada->id) }}">{{ $cancelada->factura }}</a></td>
                                    <td><a href="{{ route('fornecedores-encomendas.show', $cancelada->id) }}">{{ $cancelada->data_emissao }}</a></td>
                                    <td>{{ $cancelada->previsao_entrega }}</td>
                                    <td><span class="bg-danger text-white p-1 text-uppercase">{{ $cancelada->status }}</span></td>
                                    <td>{{ number_format($cancelada->total_produto, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                    <td>{{ number_format($cancelada->total_sIva, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                    <td>{{ number_format($cancelada->total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                    <td>

                                      <div class="btn-group">
                                        <button type="button" class="btn btn-default">Opções</button>
                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                          <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                          <a class="dropdown-item" href="#">Detalhes</a>
                                          <a class="dropdown-item" href="#">Editar</a>
                                          <a class="dropdown-item" href="{{ route('encomenda-receber-produto', $cancelada->id) }}">Receber Encomenda</a>
                                          <div class="dropdown-divider"></div>
                                          
                                          <form action="{{ route('fornecedores-encomendas.destroy', $cancelada->id ) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                              onclick="return confirm('Tens Certeza que Desejas excluir esta Encomenda?')">
                                              Apagar Encomenda
                                            </button>
                                          </form>
                                        </div>
                                      </div>

                                    </td>
                                  </tr>    
                                @endforeach
                              @else
                                  <tr>
                                    <td colspan="8">Não foram encontrados resultados</td>
                                  </tr>
                              @endif
                              </tbody>
                           </table>
                          </div>
                        </div>
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
              </div>
          </div>

          <div class="card card-info card-outline">
              <a href="{{ route('fornecedores-nova-factura', $fornecedor->id) }}" class=" btn btn-info btn-sm"><i class="fas fa-plus-circle"></i> Adicionar Factura</a>
              <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                  <div class="card-header">
                      <h4 class="card-title w-100 mb-2">
                        >> Facturas ({{ $totalFacturas }})
                      </h4>
                  </div>
              </a>
              <div id="collapseTwo" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                    <div class="card-outline-tabs">
                      <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="facturasPorPagar-tab" data-toggle="pill" href="#facturasPorPagar" role="tab" aria-controls="facturasPorPagar" aria-selected="true">Por Pagar</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="facturaPagas-tab" data-toggle="pill" href="#facturaPagas" role="tab" aria-controls="facturaPagas" aria-selected="false">Pagas</a>
                          </li>
                        </ul>
                      </div>
                      <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                          <div class="tab-pane fade show active table-responsive p-0" id="facturasPorPagar" role="tabpanel" aria-labelledby="facturasPorPagar-tab">
                             <table class="table table-hover text-nowrap">
                                <thead>
                                  <tr>
                                    <th style="width: 30%">Nº Factura</th>
                                    <th>Data Factura</th>
                                    <th>Data Vencimento</th>
                                    <th>Valor</th>
                                    <th>Valor Pago</th>
                                    <th>Valor Em Dívida</th>
                                    <th>Total Creditado</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @if ($facturasNaoPagas && count($facturasNaoPagas) > 0)
                                    @foreach ($facturasNaoPagas as $fact)
                                      <tr>
                                        <td style="width: 30%"><a href="{{ route('fornecedores-facturas-encomendas.show', $fact->id) }}">{{ $fact->factura }}</a></td>
                                        <td>{{ $fact->data_factura }}</td>
                                        <td>{{ $fact->data_vencimento }}</td>
                                        <td>{{  number_format($fact->valor_factura, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                        <td>{{  number_format($fact->valor_pago, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                        <td>{{  number_format($fact->valor_divida, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                        <td>{{ number_format("00", 2, ',', '.') }}</td>
                                      </tr>      
                                    @endforeach
                                  @else
                                  <tr>
                                    <td colspan="8">Não foram encontrados resultados</td>
                                  </tr>    
                                  @endif
                                </tbody>
                             </table>
                          </div>
                          <div class="tab-pane fade" id="facturaPagas" role="tabpanel" aria-labelledby="facturaPagas-tab">
                            <table class="table table-hover text-nowrap">
                              <thead>
                                <tr>
                                  <th style="width: 30%">Nº Factura</th>
                                  <th>Data Factura</th>
                                  <th>Data Vencimento</th>
                                  <th>Valor</th>
                                  <th>Valor Pago</th>
                                  <th>Valor Em Dívida</th>
                                  <th>Total Creditado</th>
                                </tr>
                              </thead>
                              <tbody>
                                @if ($facturasPagas && count($facturasPagas) > 0)
                                  @foreach ($facturasPagas as $fact)
                                    <tr>
                                      <td style="width: 30%"><a href="{{ route('fornecedores-facturas-encomendas.show', $fact->id) }}">{{ $fact->factura }}</a></td>
                                      <td>{{ $fact->data_factura }}</td>
                                      <td>{{ $fact->data_vencimento }}</td>
                                      <td>{{  number_format($fact->valor_factura, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                      <td>{{  number_format($fact->valor_pago, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                      <td>{{  number_format($fact->valor_divida, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                                      <td>{{ number_format("00", 2, ',', '.') }}</td>
                                    </tr>      
                                  @endforeach
                                @else
                                <tr>
                                  <td colspan="8">Não foram encontrados resultados</td>
                                </tr>    
                                @endif
                              </tbody>
                           </table>
                          </div>
                        </div>
                      </div>
                      <!-- /.card -->
                    </div>
                  </div>
              </div>
          </div>

          <div class="card card-primary card-outline">
              <a href="{{ route('fornecedores-movimentos', $fornecedor->id) }}" class="btn btn-primary btn-sm "><i class="fas fa-list"></i>  Movimentos</a>
              <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                  <div class="card-header">
                      <h4 class="card-title w-100 mb-2">
                        >> Conta Corrente
                      </h4> 
                  </div>
              </a>
              <div id="collapseThree" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                          <div class="info-box-content">
                            <span class="info-box-text">Em Atraso</span>
                            <h5 class="info-box-number">{{ number_format($facturaAtraso, 2, ',', '.')  }} </h5>
                            <span class="info-box-text">Número de faturas em atraso</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
            
                      <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                          <div class="info-box-content">
                            <span class="info-box-text">Saldoss</span>
                            <h5 class="info-box-number text-danger">{{ number_format(($dividasVencidas + $dividasCorrente) , 2, ',', '.')  }}  {{ $loja->empresa->moeda }}</h5>
                            @if (($dividasVencidas + $dividasCorrente) > 0)
                              <span class="info-box-text text-success">Valor que deve ao Fornecedor</span>
                            @else
                              <span class="info-box-text">Não Existe dividas</span>
                            @endif
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>

                      <!-- /.col -->
                      <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                          <div class="info-box-content">
                            <span class="info-box-text">Dívida Corrente</span>
                            <h5 class="info-box-number">{{ number_format($dividasCorrente, 2, ',', '.')  }}  {{ $loja->empresa->moeda }}</h5>
                            @if ($dividasCorrente > 0)
                              <span class="info-box-text text-success">Existem pagamentos pendentes</span>
                            @else
                              <span class="info-box-text">Não existem pagamentos pendentes</span>
                            @endif
                           
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                          <div class="info-box-content">
                            <span class="info-box-text">Dívida Vencida</span>
                            <h5 class="info-box-number">{{ number_format($dividasVencidas, 2, ',', '.') }}  {{ $loja->empresa->moeda }}</h5>
                            @if ($dividasVencidas > 0)
                              <span class="info-box-text text-success">Existem pagamentos fora do prazo</span>
                            @else
                              <span class="info-box-text">Não existem pagamentos fora do prazo</span>
                            @endif
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
              
                      <!-- /.col -->
                    </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

    <div class="card"></div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection