@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Pacotes Salariais</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Pacote</li>
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
            <div class="card-header">
              <h3 class="card-title">
                @if (Auth::user()->can('criar pacote'))
                <a href="{{ route('pacotes-salarial.create') }}" class="btn btn-sm btn-primary">Novo Pacote</a>
                @endif
              </h3>

              <div class="card-tools">
                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
              </div>
            </div>

            @if ($pacotes)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Cargo</th>
                    <th>Categoria</th>
                    <th>Salário Base</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Estado</th>
                    <th><div class="float-right">Acções</div></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($pacotes as $item)
                  <tr>
                    <th>{{ $item->id }}</th>
                    <th>{{ $item->cargo->nome ??  "" }}</th>
                    <th>{{ $item->categoria->nome ??  "" }}</th>
                    <th>{{ number_format($item->salario_base, 2, ',', '.')  }}</th>
                    <th>Limite Isenção</th>
                    <th>Sujeito IRT</th>
                    <th>Sujeito INSS</th>
                    <th>{{ $item->status }}</th>
                    <th class="text-right">
                      <div class="btn-group">
                          <button type="button" class="btn btn-default">Ações</button>
                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                            @if (Auth::user()->can('listar pacote'))
                            <a class="dropdown-item" href="{{ route('pacotes-salarial.show', $item->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                            @endif
                            @if (Auth::user()->can('editar pacote'))
                            <a class="dropdown-item" href="{{ route('pacotes-salarial.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            @if (Auth::user()->can('eliminar pacote'))
                            <form action="{{ route('pacotes-salarial.destroy', $item->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta pacote?')">
                                    <i class="fas fa-trash text-danger"></i> Eliminar
                                </button>
                            </form>
                            @endif
                          </div>
                    </th>
                  </tr>
                    <tr>
                      <th colspan="2">.</th>
                      <th colspan="7">SUBSÍDIOS</th>
                    </tr>
                    @foreach ($item->subsidios_pacotes as $item1)
                    <tr>
                      <td colspan="2">.</td>
                      <td>{{ $item1->subsidio->nome }}</td>
                      <td>{{ number_format($item1->salario, 2, ',', '.') }}</td>
                      <td>{{ number_format($item1->limite_isencao, 2, ',', '.') }}</td>
                      <td>{{ $item1->irt == "Y" ? 'SIM' : 'NÃO' }}</td>
                      <td>{{ $item1->inss == "Y" ? 'SIM' : 'NÃO' }}</td>
                      <td>{{ $item1->processamento->nome }}</td>
                      <td><a href="" class="btn btn-danger float-right"><i class="fas fa-trash"></i> Remover</a></td>
                    </tr>
                    @endforeach
                    
                    <tr>
                      <th colspan="2">.</th>
                      <th colspan="7">DESCONTOS</th>
                    </tr>
                  
                    @foreach ($item->desconto_pacotes as $item1)
                    <tr>
                      <td colspan="2">.</td>
                      <td>{{ $item1->desconto->nome }}</td>
                      <td>{{ number_format($item1->salario, 2, ',', '.') }}</td>
                      <td>{{ $item1->tipo_valor == "P" ? "%": "Kz" }}</td>
                      <td>{{ $item1->irt == "Y" ? 'SIM' : 'NÃO' }}</td>
                      <td>{{ $item1->inss == "Y" ? 'SIM' : 'NÃO' }}</td>
                      <td>{{ $item1->processamento->nome }}</td>
                      <td><a href="" class="btn btn-danger float-right"><i class="fas fa-trash"></i> Remover</a></td>
                    </tr>
                    @endforeach
                  
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
