@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Facturas Listagem</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
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

      <div class="row">
        <div class="col-12 bg-light">
          <form action="{{ route('fornecedores-facturas-encomendas.index') }}" method="get" class="mt-3">
            @csrf
            <div class="card-body row">
  
              <div class="col-3">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Tipo Factura</span>
                  </div>
                  <select type="text" class="form-control select2" name="facturas">
                    <option value="all">Todas</option>
                    <option value="false">Por Pagar</option>
                    <option value="true">Pagas</option>
                  </select>
                </div>
                <p class="text-danger">
                  @error('facturas')
                  {{ $message }}
                  @enderror
                </p>
              </div>
              
              <div class="col-12 col-md-6">
                <div class="row">
                  <div class="col-sm-12 col-md-10">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Data Emissão</span>
                      </div>
                      <input type="date" class="form-control" name="data1" value=""
                        placeholder="Informe o nome do caixa">
                      <div class="input-group-prepend">
                        <span class="input-group-text"> Até </span>
                      </div>
                      <input type="date" class="form-control" name="data2" value="{{ old('data2') }}"
                        placeholder="Informe o nome do caixa">
                      <button type="submit" class="btn btn-primary ml-2 text-right"> <i class="fas fa-search"></i>
                        Pesquisar</button>
                    </div>
                  </div>
                </div>
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
                {{-- <a href="{{ route('fornecedores-facturas-encomendas.create') }}" class="btn btn-sm btn-primary">Adicionar Factura</a> --}}
              </h3>
            </div>

            @if ($facturas)
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-hover" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>Fornecedor</th>
                    <th class="text-center">Nº Factura</th>
                    <th class="text-center">Data Factura</th>
                    <th class="text-center">Data Vencimento</th>
                    <th class="text-center">Valor da Factura</th>
                    <th class="text-center">Valor Pago</th>
                    <th class="text-center">Em Dívida</th>
                    <th class="text-center">Estado</th>
                    <th style="width: 10px"></th>
                  </tr>
                </thead>
                <tbody>
                @if ($facturas && count($facturas) != 0)
                  @foreach ($facturas as $factura)
                    <tr>
                      <td><a href="{{ route('fornecedores.show', $factura->fornecedor->id) }}">{{ $factura->fornecedor->nome }}</a></td>
                      <td class="text-center"><a href="{{ route('fornecedores-facturas-encomendas.show', $factura->id) }}">{{ $factura->factura }}</a></td>
                      <td class="text-center">{{ $factura->data_factura }}</td>
                      <td class="text-center">{{ $factura->data_vencimento }}</td>
                      <td class="text-center">{{ number_format($factura->valor_factura, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                      <td class="text-center">{{ number_format($factura->valor_pago, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                      <td class="text-center">{{ number_format($factura->vaolr_divida, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                      <td class="text-center">{{ $factura->status2 == "concluido" ? 'Pago' : 'Não Pago' }}</td>
                      <td>

                        <div class="btn-group">
                          <button type="button" class="btn btn-default">Opções</button>
                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('fornecedores-facturas-encomendas.show', $factura->id) }}">Detalhes</a>
                            {{-- <a class="dropdown-item" href="{{ route('fornecedores-facturas-encomendas.edit', $factura->id) }}">Editar</a> --}}
                            <a class="dropdown-item" href="{{ route('encomenda-liquidar-factura-compra', $factura->id) }}">Liquidar Factura</a>
                            <a class="dropdown-item" href="{{ route('encomenda-duplicar-factura', $factura->id) }}">Duplicar Factura</a>
                            <div class="dropdown-divider"></div>
                            
                            <form action="{{ route('fornecedores-facturas-encomendas.destroy', $factura->id ) }}" method="post">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="dropdown-item"
                                onclick="return confirm('Tens Certeza que Desejas excluir esta Factura?')">
                                Apagar Factura
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
            <!-- /.card-body -->

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
