@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Registros de Compras</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Registrod de Compras</li>
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

                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('registros-compras-produtos.index') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">

                                <div class="col-3">
                                    <label for="" class="form-label">Data Inicio</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label for="" class="form-label">Data Final</label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary btn-sm ml-2 text-right"> <i class="fas fa-search"></i> Pesquisar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

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
                            <a href="{{ route('registros-compras-produtos.create') }}" class="btn btn-sm btn-primary">Novo Registro</a>
                        </h3>

                        <div class="card-tools">
                            <a class="btn btn-sm btn-danger" target="_blink" href="{{ route('pdf-registros-compras-produtos', ['data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '']) }}"><i class="fas fa-file-pdf"></i> IMPRIMIR REGISTROS</a>
                        </div>
                    </div>

                    @if ($registros)
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Quantidade</th>
                                    <th>Total Pago</th>
                                    <th><span class="float-right">Acções</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registros as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->produto->nome ?? "" }}</td>
                                    <td>{{ $item->quantidade ?? "" }}</td>
                                    <td>{{ number_format($item->valor_pago ?? 0, 2, ',', '.') }}</td>

                                    <td class="text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default">Ações</button>
                                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item" href="{{ route('registros-compras-produtos.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                <a class="dropdown-item" href="{{ route('registros-compras-produtos.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('registros-compras-produtos.destroy', $item->id ) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Produto?')">
                                                        <i class="fas fa-trash text-danger"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
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
