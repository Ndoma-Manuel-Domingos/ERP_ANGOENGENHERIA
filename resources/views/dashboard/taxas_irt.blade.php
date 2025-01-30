@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Taxas IRT</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-recurso-humanos') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Taxa IRT</li>
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
                    <div class="card">
                        @if ($taxas)
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Remunerações</th>
                                        <th>Taxas</th>
                                        <th>Abatimentos</th>
                                        <th>Valor Fixo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($taxas as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ number_format($item->remuneracao ?? 0, 1, ',', '.' ) }}</td>
                                        <td>{{ number_format($item->taxa ?? 0, 1, ',', '.' ) }}</td>
                                        <td>{{ number_format($item->abatimento ?? 0, 1, ',', '.' ) }}</td>
                                        <td>{{ number_format($item->valor_fixo ?? 0, 1, ',', '.' ) }}</td>
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

