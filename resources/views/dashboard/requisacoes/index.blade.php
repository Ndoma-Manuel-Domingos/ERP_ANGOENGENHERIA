@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Requisições</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Requisições</li>
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
                    <form action="{{ route('requisacoes.index') }}" method="get" class="mt-3">
                        <div class="card">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">Estado Requisições</label>
                                        <select type="text" class="form-control select2" name="tipo_documento">
                                            <option value="">Todas</option>
                                            <option value="pendente" {{ $requests['tipo_documento'] == "pendente" ? 'selected' : '' }}>Pendentes</option>
                                            <option value="rejeitada" {{ $requests['tipo_documento'] == "rejeitada" ? 'selected' : '' }}>Rejeitada</option>
                                            <option value="aprovada" {{ $requests['tipo_documento'] == "aprovada" ? 'selected' : '' }}>Aprovada</option>
                                            <option value="rascunho" {{ $requests['tipo_documento'] == "rascunho" ? 'selected' : '' }}>Rascunho</option>
                                        </select>
                                        <p class="text-danger">
                                            @error('tipo_documento')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">Data Inicio</label>
                                        <input type="date" class="form-control" name="data_inicio" value="{{ $requests['data_inicio'] ?? '' }}">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="" class="form-label">Data Final</label>
                                        <input type="date" class="form-control" name="data_final" value="{{ $requests['data_final'] ?? '' }}">
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
                                <a href="{{ route('requisacoes.create') }}" class="btn btn-sm btn-primary">Nova Requisição</a>
                            </h3>
                            
                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" target="_blink" href="{{ route('imprimir-requisicao-colectivas', ['tipo_documento' => $requests['tipo_documento'] ?? "", 'data_inicio' => $requests['data_inicio'] ?? "", 'data_final' => $requests['data_final'] ?? "" ]) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                                {{-- <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a> --}}
                            </div>
                              
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nº Requisição</th>
                                        <th>Requisitante</th>
                                        <th>Data</th>
                                        <th>Estado</th>
                                        <th class="text-right">Qtd Produtos</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requisicoes as $item)
                                    <tr>
                                        <td><a href="{{ route('requisacoes.show', $item->id) }}">REQ Nº {{ $item->id }}</a></td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->data_emissao }}</td>

                                        @if ($item->status == 'pendente')
                                        <td><span class="bg-primary p-1 text-uppercase">{{ $item->status }}</span></td>
                                        @endif

                                        @if ($item->status == 'aprovada')
                                        <td><span class="bg-success p-1 text-uppercase">{{ $item->status }}</span></td>
                                        @endif

                                        @if ($item->status == 'rejeitada')
                                        <td><span class="bg-danger p-1 text-uppercase">{{ $item->status }}</span></td>
                                        @endif

                                        @if ($item->status == 'rascunho')
                                        <td><span class="bg-warning p-1 text-uppercase">{{ $item->status }}</span></td>
                                        @endif

                                        <td class="text-right">{{ count($item->items) }}</td>

                                        <td>
                                            <div class="btn-group float-right">
                                                <button type="button" class="btn btn-default">Opções</button>
                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('requisacoes.show', $item->id) }}">Detalhes</a>
                                                    <a class="dropdown-item" href="{{ route('requisacoes.edit', $item->id) }}">Editar</a>
                                                    
                                                    @if (Auth::user()->can('aprovar requisicao'))
                                                        @if ($item->status != 'aprovada')
                                                        <a class="dropdown-item text-success" href="{{ route('requisacoes.aprovada', $item->id) }}">Marcar como Apravada</a>
                                                        @endif
                                                    @endif
                                                    
                                                    @if ($item->status != 'rascunho')
                                                    <a class="dropdown-item text-warning" href="{{ route('requisacoes.rascunho', $item->id) }}">Marcar como Rascunho</a>
                                                    @endif
                                                    
                                                    @if (Auth::user()->can('rejeitar requisicao'))
                                                        @if ($item->status != 'rejeitada')
                                                        <a class="dropdown-item text-danger" href="{{ route('requisacoes.rejeitar', $item->id) }}">Marcar como Rejeitado</a>
                                                        @endif
                                                    @endif
                                                    
                                                    <div class="dropdown-divider"></div>

                                                    <form action="{{ route('requisacoes.destroy', $item->id ) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Requisição?')">
                                                            Apagar Requisição
                                                        </button>
                                                    </form>
                                                    
                                                    <a class="dropdown-item" target="_blink" href="{{ route('imprimir-requisicao-individual', $item->id) }}">Imprimir</a>

                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
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
