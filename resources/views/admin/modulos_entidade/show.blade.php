@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe Modulo Entidade</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('modulos-entidade.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Modulo Entidade</li>
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
          <div class="card">
            @if ($modulo_entidade)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>Modulo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $modulo_entidade->modulo }}</td>
                  </tr>

                  <tr>
                    <td>{{ $modulo_entidade->descricao }}</td>
                  </tr>

                  {{-- <tr>
                    <th>Lista de Permissões</th>
                  </tr>
                  @foreach ($modulo_entidade->permissions as $item)
                    <tr>
                      <td>{{ $item->nome }} <a href="{{ route('eliminar_permissao', $item->id) }}" class="float-right text-danger" title="remover permissão ao Perfil"><i class="fas fa-close"></i></a></td>
                    </tr>    
                  @endforeach --}}
                </tbody>
              </table>
            </div>

            <div class="card-footer clearfix d-flex">
              <a href="{{ route('modulos-entidade.edit', $modulo_entidade->id) }}" class="btn btn-sm btn-success mx-1">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('modulos-entidade.destroy', $modulo_entidade->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger mx-1"
                  onclick="return confirm('Tens Certeza que Desejas excluir esta Modulo de entidade?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
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