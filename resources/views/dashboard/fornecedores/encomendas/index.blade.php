@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Encomendas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
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
          <form action="{{ route('fornecedores-encomendas.index') }}" method="get" class="mt-3">
            <div class="card">
              @csrf
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-3">
                    <label for="status" class="form-label">Tipo Encomenda</label>
                    <select type="text" class="form-control select2" name="status" id="status">
                      <option value="">Todas</option>
                      <option value="pendente" {{ $requests['status'] == "pendente" ? 'selected' : '' }}>Pendentes</option>
                      <option value="cancelada" {{ $requests['status'] == "cancelada" ? 'selected' : '' }}>Canceladas</option>
                      <option value="entregue" {{ $requests['status'] == "entregue" ? 'selected' : '' }}>Entregues</option>
                      <option value="rascunho" {{ $requests['status'] == "rascunho" ? 'selected' : '' }}>Rascunho</option>
                    </select>
                    <p class="text-danger">
                      @error('status')
                      {{ $message }}
                      @enderror
                    </p>
                  </div>
                  
                  <div class="col-12 col-md-3">
                    <label for="data_inicio" class="form-label">Data Inicio</label>
                    <input type="date" class="form-control" name="data_inicio" id="data_inicio" value="{{ $requests['data_inicio'] ?? '' }}">
                  </div>
                  
                  <div class="col-12 col-md-3">
                    <label for="data_final" class="form-label">Data Final</label>
                    <input type="date" class="form-control" id="data_final" name="data_final" value="{{ $requests['data_final'] ?? '' }}">
                  </div>
                
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary ml-2 text-right"> <i class="fas fa-search"></i> Pesquisar</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- /.row -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <a href="{{ route('fornecedores-encomendas.create') }}" class="btn btn-sm btn-primary">Adicionar Encomenda</a>
              </h3>
              <div class="card-tools">
                <a class="btn btn-sm btn-danger" target="_blink" href="{{ route('imprimir-encomenda-todas', ['status' => $requests['status'] ?? "", 'data_inicio' => $requests['data_inicio'] ?? "", 'data_final' => $requests['data_final'] ?? "" ]) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                {{-- <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a> --}}
              </div>
            </div>

            @if ($encomendas)
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>Nº Encomenda</th>
                    <th>Fornecedor</th>
                    <th>Data</th>
                    <th>Estado</th>
                    <th>Estado Pagamento</th>
                    <th class="text-right">Qtds</th>
                    <th class="text-right">Qtds Recebida</th>
                    {{-- <th>Nº Produto</th> --}}
                    <th class="text-right">Total S/IVA</th>
                    <th class="text-right">Total C/IVA</th>
                    <th class="text-right">Desconto</th>
                    <th class="text-right">Outros Custos</th>
                    <th class="text-right">Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($encomendas as $item)
                    <tr>
                      <td><a href="{{ route('fornecedores-encomendas.show', $item->id) }}">{{ $item->factura }}</a></td>
                      <td><a href="{{ route('fornecedores.show', $item->fornecedor->id) }}">{{ $item->fornecedor->nome }}</a></td>
                      <td>{{ $item->data_emissao }}</td>
                      @if ($item->status == 'pendente')
                        <td class="bg-warning text-white p-1 text-uppercase">{{ $item->status }}</td>
                      @endif

                      @if ($item->status == 'entregue')
                        <td class="bg-primary text-white p-1 text-uppercase">{{ $item->status }}</td>
                      @endif

                      @if ($item->status == 'cancelada')
                        <td class="bg-danger text-white p-1 text-uppercase">{{ $item->status }}</td>
                      @endif
                      <td>{{ $item->status_pagamento == 1 ? 'Pago': 'Não Pago' }}</td>
                      <td class="text-right">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                      <td class="text-right">{{ number_format($item->quantidade_recebida, 2, ',', '.') }}</td>
                      
                      <td class="text-right">{{ number_format($item->total_sIva, 2, ',', '.') }}</td>
                      <td class="text-right">{{ number_format($item->total_cIVa, 2, ',', '.') }}</td>
                      
                      <td class="text-right">{{ number_format($item->desconto, 1, ',', '.') }} %</td>
                      <td class="text-right">{{ number_format($item->outros_custos + $item->custo_transporte + $item->custo_manuseamento, 2, ',', '.') }}</td>
                      <td class="text-right">{{ number_format($item->total, 2, ',', '.') }}</td>
                      <td>
                        <div class="btn-group float-right">
                          <button type="button" class="btn btn-default">Opções</button>
                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('fornecedores-encomendas.show', $item->id) }}">Detalhes</a>
                            <a class="dropdown-item" href="{{ route('fornecedores-encomendas.edit', $item->id) }}">Editar</a>
                            <a class="dropdown-item" href="{{ route('encomenda-receber-produto', $item->id) }}">Receber Encomenda</a>
                            <a class="dropdown-item" href="{{ route('encomenda-criar-factura-compra', $item->id) }}">Criar Factura de Compra</a>
                            <div class="dropdown-divider"></div>
                            
                            <form action="{{ route('fornecedores-encomendas.destroy', $item->id ) }}" method="post">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="dropdown-item"
                                onclick="return confirm('Tens Certeza que Desejas excluir esta Encomenda?')">
                                Apagar Encomenda
                              </button>
                            </form>
                            
                            <a class="dropdown-item" href="{{ route('imprimir-encomenda', $item->id) }}" target="_blink">Imprimir</a>
                            
                          </div>
                        </div>
                      </td>
                    </tr>    
                  @endforeach
                </tbody>
             </table>
            </div>

            @endif

          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
  <script>
    $(function() {
      $("#carregar_tabela").DataTable({
        language: {
          url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });
  </script>
@endsection
