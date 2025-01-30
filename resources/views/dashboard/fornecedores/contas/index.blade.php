@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Listagem Conta Corrente</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Conta</li>
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
            @if ($movimentos)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th class="text-right">Saldo</th>
                    <th class="text-right">Dívidas Corrente</th>
                    <th class="text-right">Dívidas Vencidas</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($movimentos as $movimento)
                  <tr>
                    <td><a href="{{ route('clientes-movimentos-conta', $movimento->cliente->id) }}">{{ $movimento->cliente->nome }}</a></td>
                    <td class=" text-success text-right">{{ number_format($movimento->saldo, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</td>
                    <td class=" text-success text-right">{{ number_format($movimento->divida_corrente, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</td>
                    <td class=" text-success text-right">{{ number_format($movimento->divida_vencida, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</td>
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
