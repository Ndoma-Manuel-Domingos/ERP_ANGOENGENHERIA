@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Agendamentos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Agendamentos</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
    
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
      
      </div>
      <!-- /.row -->
      
      
              
      <div class="row">
        <div class="col-12 bg-light">
            <div class="card">
                <form action="{{ route('agendamentos.index') }}" method="get" class="mt-3">
                    @csrf
                    <div class="card-body row">
                    
                      <div class="col-3">
                        <label for="status" class="form-label">Estados</label>
                        <select type="text" class="form-control select2" name="status" id="status">
                          <option value="">TODOS</option>
                          <option value="pendente" {{ $requests['status'] == "pendente" ? 'selected' : ''}}>Pendente</option>
                          <option value="atendido" {{ $requests['status'] == "atendido" ? 'selected' : ''}}>Atendido</option>
                          <option value="cancelado" {{ $requests['status'] == "cancelado" ? 'selected' : ''}}>Cancelado</option>
                          <option value="experido" {{ $requests['status'] == "experido" ? 'selected' : ''}}>Experido</option>
                        </select>
                      </div>

                      <div class="col-3">
                          <label for="cliente_id" class="form-label">Clientes</label>
                          <select type="text" class="form-control select2" id="cliente_id" name="cliente_id">
                              <option value="">TODOS</option>
                              @foreach ($clientes as $cliente)
                              <option value="{{ $cliente->id }}" {{ $requests['cliente_id'] == $cliente->id ? 'selected' : ''}}>{{ $cliente->nome }}</option>
                              @endforeach
                          </select>
                      </div>
                   
      
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
      
      <div class="row">        
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                @if (Auth::user()->can('criar agendamento'))
                <a href="{{ route('agendamentos.create') }}" class="btn btn-sm btn-primary">Novo Agendamento</a>
                @endif
              </h3>

              <div class="card-tools">
                <a class="btn btn-sm btn-danger" target="_blink" href="{{ route('pdf-agendamentos', ['data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '', 'cliente_id' => $requests['cliente_id'], 'status' => $requests['status']]) }}"><i class="fas fa-file-pdf"></i> IMPRIMIR PDF</a>
                {{-- <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a> --}}
              </div>
            </div>

            @if ($agendas)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nº</th>
                    <th>Nome Cliente</th>
                    <th>Telefone</th>
                    <th>Estado</th>
                    <th>Serviço</th>
                    <th>Hora</th>
                    <th>Data</th>
                    <th class="text-right">Acções</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($agendas as $item)
                  <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->numero }}</td>
                    <td>{{ $item->cliente ? $item->cliente->nome : "" }}</td>
                    <td>{{ $item->cliente ? $item->cliente->telefone : "" }}</td>
                    
                    @if ($item->status == "pendente")
                    <td class="text-uppercase text-info">{{ $item->status }}</td>    
                    @endif
                    
                    @if ($item->status == "atendido")
                    <td class="text-uppercase text-success">{{ $item->status }}</td> 
                    @endif
                    
                    @if ($item->status == "cancelado")
                    <td class="text-uppercase text-danger">{{ $item->status }}</td>   
                    @endif
                    
                    @if ($item->status == "experido")
                    <td class="text-uppercase text-warning">{{ $item->status }}</td>  
                    @endif
                    
                    <td>{{ $item->produto ? $item->produto->nome : "" }}</td>
                    <td>{{ $item->hora }}</td>
                    <td>{{ $item->data_at }}</td>
                                        
                    <td class="text-right">
                      <div class="btn-group">
                          <button type="button" class="btn btn-default">Ações</button>
                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                            @if (Auth::user()->can('listar agendamento'))
                            <a class="dropdown-item" href="{{ route('agendamentos.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                            @endif
                            @if (Auth::user()->can('listar agendamento'))
                            <a class="dropdown-item" href="{{ route('agendamentos.imprimir', $item->id) }}"  target="_blink"><i class="fas fa-print text-info"></i> Imprimir</a>
                            @endif
                            @if (Auth::user()->can('editar agendamento'))
                            <a class="dropdown-item" href="{{ route('agendamentos.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            @if (Auth::user()->can('eliminar agendamento'))
                            <form action="{{ route('agendamentos.destroy', $item->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir este Agendamento?')">
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
