@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Matrículas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Matricula</li>
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

        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <a href="{{ route('alunos.create') }}" class="btn btn-sm btn-primary">Nova Matrícula</a>
              </h3>

              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            @if ($matriculas)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>Codigo</th>
                    <th>Nome</th>
                    <th>Genero</th>
                    <th>Estado Cívil</th>
                    <th>Nif</th>
                    <th>Curso</th>
                    <th>Turno</th>
                    <th>Sala</th>
                    <th>Ano Lectivo</th>
                    <th>Estado</th>
                    <th class="text-right">Acções</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($matriculas as $item)
                  <tr>
                    <td>{{ $item->aluno->id }}</td>
                    <td><a href="{{ route('alunos.show', $item->aluno->id) }}">{{ $item->aluno->nome }} </a></td>
                    <td>{{ $item->aluno->genero ?? '------' }}</td>
                    <td>{{ $item->aluno->estado_civil ?? '------' }}</td>
                    <td>{{ $item->aluno->nif ?? '------' }}</td>
                    <td>{{ $item->curso->nome ?? '------' }}</td>
                    <td>{{ $item->turno->nome ?? '------' }}</td>
                    <td>{{ $item->sala->nome ?? '------' }}</td>
                    <td>{{ $item->ano_lectivo->nome ?? '------' }}</td>
                    <td>{{ $item->aluno->telefone ?? '--- --- ---' }} / {{ $item->aluno->telemovel ?? '--- --- --- ---' }}</td>
                    <td>{{ $item->status }}</td>
                   
                    <td class="d-flex">
                      <a href="{{ route('alunos.show', $item->aluno->id) }}" class="btn btn-sm btn-info mx-1">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('alunos-matriculas-editar', $item->id) }}" class="btn btn-sm btn-success mx-1">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('alunos-matriculas-excluir', $item->id ) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger mx-1"
                          onclick="return confirm('Tens Certeza que Desejas excluir esta Matricula?')">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
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
