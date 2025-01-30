@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Marcações de Ferias</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Ferias</li>
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
        
        <div class="col-12 col-md-12 bg-light">
            <div class="card">
                <form action="{{ route('marcacoes-ferias.index') }}" method="get" class="mt-3">
                    @csrf
                    <div class="card-body row">
                      <div class="col-12 col-md-4">
                          <label for="data_inicio" class="form-label">Data Inicio</label>
                          <div class="input-group mb-3">
                              <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                          </div>
                      </div>

                      <div class="col-12 col-md-4">
                          <label for="data_final" class="form-label">Data Final</label>
                          <div class="input-group mb-3">
                              <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                          </div>
                      </div>

                      <div class="col-12 col-md-4">
                        <label for="funcionario_id" class="form-label">Funcionarios</label>
                        <select type="text" class="form-control select2" name="funcionario_id">
                          <option value="">Selecione Operador</option>
                          @foreach ($funcionarios as $item)
                          <option value="{{ $item->id }}" {{ $requests['funcionario_id'] == $item->id ? 'selected' : ''}}>{{ $item->numero_mecanografico }} - {{ $item->nome }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary btn-sm ml-2 text-right"> <i class="fas fa-search"></i> Pesquisar</button>
                    </div>

                </form>
            </div>
        </div>
        
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                {{-- @if (Auth::user()->can('criar subsidio')) --}}
                <a href="{{ route('marcacoes-ferias.create') }}" class="btn btn-sm btn-primary">Marcar Novas Ferias</a>
                {{-- @endif --}}
              </h3>

              <div class="card-tools">
                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
              </div>
            </div>

            @if ($ferias)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nome Completo</th>
                    <th>Data Inicio</th>
                    <th>Data Final</th>
                    <th>Exercício</th>
                    <th>Periodo</th>
                    <th>Dias</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($ferias as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td><a href="{{ route('funcionarios.show', $item->funcionario->id) }}">{{ $item->funcionario->numero_mecanografico }} - {{ $item->funcionario->nome }}</a></td>
                    <td>{{ $item->data_inicio }}</td>
                    <td>{{ $item->data_final }}</td>
                    <td>{{ $item->exercicio->nome }}</td>
                    <td>{{ $item->periodo->nome }}</td>
                    <td>{{ $item->total_dias }}</td>
                    
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
           
            @endif
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
