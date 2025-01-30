@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Triagens</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Triagens</li>
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
                <a href="{{ route('triagens.create') }}" class="btn btn-sm btn-primary">Marcar Triagem</a>
              </h3>

              <div class="card-tools">
                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
              </div>
            </div>

            @if ($triagens)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>Consulta Nº</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Consulta</th>
                    <th>Estado</th>
                    <th>Peso</th>
                    <th>Temperatura</th>
                    <th>Altura</th>
                    <th>Pressão</th>
                    <th>Frequencia Respiratória</th>
                    <th>Frenquencia Cardiaca</th>
                    <th>IMC</th>
                    <th class="text-right">Acções</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($triagens as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->consulta->paciente->nome }}</td>
                    <td>{{ $item->consulta->medico->nome }}</td>
                    <td>{{ $item->consulta->produto->nome }}</td>
                    @if ($item->status == "AGENDADA")
                    <td class="text-info">{{ $item->status }}</td>   
                    @endif
                    @if ($item->status == "CONCLUIDO")
                    <td class="text-success">{{ $item->status }}</td>  
                    @endif 
                    @if ($item->status == "EM ATENDIMENTO")
                    <td class="text-warning">{{ $item->status }}</td> 
                    @endif
                    @if ($item->status == "CANCELADA")
                    <td class="text-danger">{{ $item->status }}</td>   
                    @endif
                    <td>{{ $item->peso }}</td>
                    <td>{{ $item->temperatura }}</td>
                    <td>{{ $item->altura }}</td>
                    <td>{{ $item->pressao }}</td>
                    <td>{{ $item->freq_respiratoria }}</td>
                    <td>{{ $item->freq_cardiaca }}</td>
                    <td>{{ $item->imc }}</td>
                    
                    <td class="text-right">
                      <div class="btn-group">
                          <button type="button" class="btn btn-default">Ações</button>
                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                              <a class="dropdown-item" href="{{ route('triagens.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                              <a class="dropdown-item" href="{{ route('triagens.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                              
                              @if ($item->status == "AGENDADA")
                              <a class="dropdown-item" href="{{ route('cancelar_consulta', $item->id) }}"><i class="fas fa-cancel text-danger"></i> Cancelar Consulta</a>
                              <a class="dropdown-item" href="{{ route('triagens.create', ['id' => $item->id ]) }}"><i class="fas fa-paper-plane text-info"></i> Enviar para Triagem</a>   
                              @endif
                              <div class="dropdown-divider"></div>
                              <form action="{{ route('triagens.destroy', $item->id ) }}" method="post">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Curso?')">
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
        },
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });
  </script>
@endsection
