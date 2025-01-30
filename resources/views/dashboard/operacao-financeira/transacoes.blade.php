@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transações Financeiras</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-financeiro') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Financeiras</li>
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

                <div class="col-12 col-md-12">
                    @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                    @endif

                    @if (Session::has('danger'))
                    <div class="alert alert-danger">
                        {{ Session::get('danger') }}
                    </div>
                    @endif

                    @if (Session::has('warning'))
                    <div class="alert alert-warning">
                        {{ Session::get('warning') }}
                    </div>
                    @endif
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                            </h3>
                            <div class="card-tools">
                                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
                            </div>
                        </div>

                        @if ($operacoes)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Referência</th>
                                        <th>Estado</th>
                                        <th>Dispesa/Receita</th>
                                        <th>Fornecedor/Cliente</th>
                                        <th style="text-right">Data</th>
                                        <th>Pagamento/Recebimento</th>
                                        <th style="text-right">Motante</th>
                                        <th><span class="float-right">Acções</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operacoes as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->nome }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->type == "D" ? ( $item->dispesa ? $item->dispesa->nome : "") : ($item->receita ? $item->receita->nome : "") }}</td>
                                        <td>{{ $item->type == "D" ? ( $item->fornecedor ? $item->fornecedor->nome : "") : ($item->cliente ? $item->cliente->nome : "") }}</td>
                                        <td style="text-right">{{ $item->date_at }}</td>
                                        <td>{{ $item->type == "D" ? ($item->forma_pagamento ? $item->forma_pagamento->titulo : "") : ($item->forma_recebimento ? $item->forma_recebimento->titulo : "") }}</td>
                                        <td style="text-right">{{ number_format($item->motante, 2, ',', '.')  }}</td>

                                        <td class="text-right">
                                            <button type="button" class="btn btn-default">Ações</button>
                                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('listar dispesa'))
                                                <a class="dropdown-item" href="{{ route('operacaoes-financeiras.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                @endif
                                                
                                                @if (Auth::user()->can('editar dispesa'))
                                                <a class="dropdown-item" href="{{ route('operacaoes-financeiras.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                @endif
                                                <div class="dropdown-divider"></div>
                                                @if (Auth::user()->can('eliminar dispesa'))
                                                <form action="{{ route('operacaoes-financeiras.destroy', $item->id ) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta dispesa?')">
                                                        <i class="fas fa-trash text-danger"></i> Eliminar
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach

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
