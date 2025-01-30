@extends('layouts.app')

@section('content')

<!-- Content Wrapper. quartoins page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe do Quarto</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('quartos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Quarto</li>
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
            @if ($quarto)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Designação</th>
                    <th>Tipo</th>
                    <th>Andar</th>
                    <th>Ocupação</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $quarto->id }}</td>
                    <td>{{ $quarto->nome }}</td>
                    <td>{{ $quarto->tipo->nome }}</td>
                    <td>{{ $quarto->andar->nome }}</td>
                    <td>{{ $quarto->solicitar_ocupacao }}</td>
                    <td>{{ $quarto->status }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="card-footer clearfix d-flex">
              <a href="{{ route('quartos.edit', $quarto->id) }}" class="btn btn-sm btn-success mx-1">
                <i class="fas fa-edit"></i>
              </a>
              <a href="{{ route('quartos.associar_tarefario', $quarto->id) }}" class="btn btn-sm btn-primary mx-1">
                <i class="fas fa-edit"></i> Associonar à Tarifários
              </a>
              <form action="{{ route('quartos.destroy', $quarto->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger mx-1"
                  onclick="return confirm('Tens Certeza que Desejas excluir esta quarto?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
            @endif

          </div>
          <!-- /.card -->
          <div class="card">
            <div class="card-header"> 
              <h5>Tarifários Associados ao Quarto</h5>
            </div>
              @if ($quarto->quartos)
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Designação</th>
                      <th>Valor</th>
                      <th>Modo Tarifário</th>
                      <th>Tipo Cobrança</th>
                      <th>Estado</th>
                      <th class="text-right">Acções</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($quarto->quartos as $q)
                      <tr>
                        <td><a href="{{ route('tarefarios.show', $q->tarefario->id) }}">{{ $q->tarefario->id }}</a></td>
                        <td><a href="{{ route('tarefarios.show', $q->tarefario->id) }}">{{ $q->tarefario->nome ?? '' }}</a></td>
                        <td>{{ number_format($q->tarefario->valor ?? 0, 2, ',', '.') }}</td>
                        <td>{{ $q->tarefario->modo_tarefario ?? '' }}</td>
                        <td>{{ $q->tarefario->tipo_cobranca ?? '' }}</td>
                        <td>{{ $q->tarefario->status ?? '' }}</td>
                        <td>
                          <a href="{{ route('tarefarios.desassociar_tarefario', $q->id) }}" class="btn btn-danger btn-sm float-right"><i class="fas fa-trash"></i> Desassociar do Tarifário</a>
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