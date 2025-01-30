@extends('layouts.formadores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe Turma</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('formadores-turmas') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Turma</li>
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
                        @if ($turma)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Curso</th>
                                        <th>Turno</th>
                                        <th>Sala</th>
                                        <th>Estado</th>
                                        <th>Create At</th>
                                        <th>Update At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $turma->id }}</td>
                                        <td>{{ $turma->nome }}</td>
                                        <td>{{ $turma->curso->nome }}</td>
                                        <td>{{ $turma->turno->nome }}</td>
                                        <td>{{ $turma->sala->nome }}</td>
                                        <td>{{ $turma->status }}</td>
                                        <td>{{ $turma->created_at }}</td>
                                        <td>{{ $turma->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif

                    </div>
                    <!-- /.card -->
                </div>
                
                <div class="col-12 col-md-12">
                  <div class="card">
                      <div class="card-header">
                        <h5>
                          Lista de Alunos
                          @if (count($pautas) > 0)
                          <a href="{{ route('formadores-turma-visualizar-pautas', $turma->id) }}" class="btn btn-sm btn-info float-right mx-1">Visualizar Pauta</a>
                          @endif
                          <a href="{{ route('turma-distribuir-pautas', $turma->id) }}" class="btn btn-sm btn-primary float-right mx-1">Distribuir Pauta</a>
                        </h5>
                      </div>
                     <!-- /.card-header -->
                      <div class="card-body table-responsive">
                        <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                          <thead>
                            <tr>
                              <th>Codigo</th>
                              <th>Nome</th>
                              <th>Genero</th>
                              <th>Estado Cívil</th>
                              <th>Nif</th>
                              <th>Codigo Postal</th>
                              <th>Telelefone/Telemóvel</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($alunos as $aluno)
                            <tr>
                              <td>{{ $aluno->aluno->id }}</td>
                              <td>{{ $aluno->aluno->nome }}</td>
                              <td>{{ $aluno->aluno->genero ?? '------' }}</td>
                              <td>{{ $aluno->aluno->estado_civil ?? '------' }}</td>
                              <td>{{ $aluno->aluno->nif ?? '------' }}</td>
                              <td>{{ $aluno->aluno->codigo_postal ?? '------' }}</td>
                              <td>{{ $aluno->aluno->telefone ?? '--- --- ---' }} / {{ $aluno->aluno->telemovel ?? '--- --- --- ---' }}</td>
                              @if ($aluno->aluno->status == true)
                                <td>Activo</td>
                              @else
                                <td>Inactivo</td>
                              @endif
                            </tr>
                            @endforeach
          
                          </tbody>
                        </table>
                      </div>
                      <!-- /.card-body -->
                  </div>
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
