@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Fornecedores</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Fornecedores</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <h6>Dívidas a Fornecedores</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text text-right">Saldo</span>
                  <h5 class="info-box-number text-right text-danger">{{ number_format($saldo, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</h5>
                  <span class="info-box-text text-right">Valor que deve aos Fornecedores</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text text-right">Dívida Corrente</span>
                  <h5 class="info-box-number text-right">{{ number_format($dividaCorrente, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</h5>
                  <span class="info-box-text text-right">Não existem pagamentos pendentes</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-12">
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text text-right">Dívida Vencida</span>
                  <h5 class="info-box-number text-right">{{ number_format($dividaVencida, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</h5>
                  <span class="info-box-text text-right">Não existem pagamentos fora do prazo</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
    
            <!-- /.col -->
          </div>
        </div>
      </div>

      <!-- /.row -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <a href="{{ route('fornecedores.create') }}" class="btn btn-sm btn-primary">Novo Fornecedor</a>
                <a href="{{ route('create_import.fornecedores') }}" class="btn btn-sm btn-success">Importar Excel</a>
              </h3>

              <div class="card-tools">
                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
              </div>
            </div>

            @if ($fornecedores)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>Conta</th>
                    <th>Nome</th>
                    <th>Nif</th>
                    <th>Tipo Fornecedor</th>
                    <th>Tipo Pessoa</th>
                    <th>Codigo Postal</th>
                    <th>Telelefone/Telemóvel</th>
                    <th>Estado</th>
                    <th class="text-right">Acções</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($fornecedores as $fornecedor)
                  <tr>
                    <td>{{ $fornecedor->conta ?? '------' }}</td>
                    <td><a href="{{ route('fornecedores.show', $fornecedor->id) }}">{{ $fornecedor->nome }} </a></td>
                    <td>{{ $fornecedor->nif ?? '------' }}</td>
                    <td>{{ $fornecedor->tipo_fornecedor ?? '------' }}</td>
                    <td>{{ $fornecedor->tipo_pessoa ?? '------' }}</td>
                    <td>{{ $fornecedor->codigo_postal ?? '------' }}</td>
                    <td>{{ $fornecedor->telefone ?? '--- --- ---' }} / {{ $fornecedor->telemovel ?? '--- --- --- ---' }}</td>
                    @if ($fornecedor->status == true)
                      <td>Activo</td>
                    @else
                      <td>Inactivo</td>
                    @endif
                   
                    <td class="text-right">
                      <div class="btn-group">
                          <button type="button" class="btn btn-default">Ações</button>
                          <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                              <span class="sr-only">Toggle Dropdown</span>
                          </button>
                          <div class="dropdown-menu" role="menu">
                              <a class="dropdown-item" href="{{ route('fornecedores.show', $fornecedor->id) }}"><i class="fas fa-eye text-info"></i> Detalhes</a>
                              <a class="dropdown-item" href="{{ route('fornecedores.edit', $fornecedor->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                              <div class="dropdown-divider"></div>
                              <form action="{{ route('fornecedores.destroy', $fornecedor->id ) }}" method="post">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta fornecedor?')">
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
