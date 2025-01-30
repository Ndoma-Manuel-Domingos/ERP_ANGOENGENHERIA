@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe do Tarifário</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tarefarios.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Tarifário</li>
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
                <div class="col-12 col-md-12">
                  <div class="card">
                    @if ($tarefario)
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                      <table class="table table-hover text-nowrap">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Designação</th>
                              <th>Valor</th>
                              <th>Modo Tarifário</th>
                              <th>Tipo Cobrança</th>
                              <th>Estado</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>{{ $tarefario->id }}</td>
                              <td>{{ $tarefario->nome }}</td>
                              <td>{{ number_format($tarefario->valor ??0 , 2, ',', '.')  }}</td>
                              <td>{{ $tarefario->modo_tarefario ?? '' }}</td>
                              <td>{{ $tarefario->tipo_cobranca ?? '' }}</td>
                              <td>{{ $tarefario->status }}</td>
                            </tr>
                          </tbody>
                      </table>
                    </div>
                    <div class="card-footer clearfix d-flex">
                      <a href="{{ route('tarefarios.edit', $tarefario->id) }}" class="btn btn-sm btn-success mx-1">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="{{ route('tarefarios.associar_tarefario', $tarefario->id) }}" class="btn btn-sm btn-primary mx-1">
                        <i class="fas fa-edit"></i> Associonar à Quartos
                      </a>
                      <form action="{{ route('tarefarios.destroy', $tarefario->id ) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm('Tens Certeza que Desejas excluir esta tarefario?')">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                    @endif
                  </div>
                    <!-- /.card -->
                    <div class="card">
                      <div class="card-header"> 
                        <h5>Quartos Associados ao Tarifário</h5>
                      </div>
                        @if ($tarefario->tarefarios)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                          <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Designação</th>
                                    <th>Tipo</th>
                                    <th>Andar</th>
                                    <th>Ocupação</th>
                                    <th>Estado</th>
                                    <th>Descrição</th>
                                    <th class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarefario->tarefarios as $q)
                                <tr>
                                  <td><a href="{{ route('quartos.show', $q->quarto->id) }}">{{ $q->quarto->id }}</a></td>
                                  <td><a href="{{ route('quartos.show', $q->quarto->id) }}">{{ $q->quarto->nome ?? '' }}</a></td>
                                  <td>{{ $q->quarto->tipo->nome ?? '' }}</td>
                                  <td>{{ $q->quarto->andar->nome ?? '' }}</td>
                                  <td>{{ $q->quarto->solicitar_ocupacao ?? '' }}</td>
                                  <td>{{ $q->quarto->status }}</td>
                                  <td>{{ $q->quarto->descricao }}</td>
                                  <td>
                                    <a href="{{ route('tarefarios.desassociar_tarefario', $q->id) }}" class="btn btn-danger btn-sm float-right"><i class="fas fa-trash"></i> Desassociar do Quarto</a>
                                  </td>
                                </tr>
                                @endforeach
                            </tbody>
                          </table>
                        </div>
                        <!-- /.card-body -->
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.quartoiner-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
