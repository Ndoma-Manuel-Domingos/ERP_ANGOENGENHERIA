@extends('layouts.formadores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Provas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-formadores') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Provas</li>
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
                <div class="col-12">
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
            </div>
            
            <div class="row">
              <div class="col-12 col-md-12">
                  <form action="{{ route('formadores-provas.index') }}" method="GET">
                      @csrf
                      <div class="card">
                          <div class="card-body row">
                              <div class="col-12 col-md-4">
                                  <label for="modulo_id" class="form-label">Modulos</label>
                                  <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                      </div>
                                      <select type="text" class="form-control @error('record') is-invalid @enderror" id="modulo_id" name="modulo_id">
                                          <option value="">Todos</option>
                                          @foreach ($modulos as $item)
                                          <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>
                              
                              <div class="col-12 col-md-4">
                                  <label for="data_inicio" class="form-label">Data Inicio</label>
                                  <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                      </div>
                                      <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" placeholder="Informe">
                                  </div>
                              </div>
                              
                              <div class="col-12 col-md-4">
                                  <label for="data_final" class="form-label">Data Final</label>
                                  <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                      </div>
                                      <input type="date" class="form-control @error('data_final') is-invalid @enderror" id="data_final" name="data_final" value="{{ old('data_final') }}" placeholder="Informe">
                                  </div>
                              </div>
                              
                          </div>
                          <div class="card-footer">
                              <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Pesquisar</button>
                          </div>
                      </div>
                  </form>
              </div>
            </div>

            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('formadores-provas.create') }}" class="btn btn-sm btn-primary">Nova Prova</a>
                            </h3>
                        </div>

                        @if ($provas)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Prova</th>
                                        <th>Nota Maxima</th>
                                        <th>Modulo</th>
                                        <th>Turma</th>
                                        <th>Data</th>
                                        <th class="text-right">Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($provas as $item)
                                    <tr>
                                        <td>{{ $item->id ?? '' }}</td>
                                        <td><a href="{{ route('formadores-provas.show', $item->id) }}">{{ $item->nome ?? '' }} </a></td>
                                        <td>{{ $item->nota_maxima ?? '' }}</td>
                                        <td>{{ $item->modulo->nome ?? '---' }}</td>
                                        <td>{{ $item->turma->nome ?? '' }}</td>
                                        <td>{{ $item->data_at ?? '' }}</td>

                                        <td class="text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">Ações</button>
                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item" href="{{ route('formadores-provas.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                                                    <a class="dropdown-item" href="{{ route('formadores-provas.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('formadores-provas.destroy', $item->id ) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta prova?')">
                                                            <i class="fas fa-trash text-danger"></i> Eliminar
                                                        </button>
                                                    </form>
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
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
