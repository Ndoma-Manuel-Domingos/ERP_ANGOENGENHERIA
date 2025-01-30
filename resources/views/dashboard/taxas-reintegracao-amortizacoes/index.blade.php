@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">TABELA DAS TAXAS DE REINTEGRAÇÕES E AMORTIZAÇÕES</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">TABELA DAS TAXAS DE REINTEGRAÇÕES E AMORTIZAÇÕES</li>
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
                <div class="col-12">
                    <div class="card">
                        <!-- ./card-header -->
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>
                        
                        @if ($dados)
                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <tbody>
                                    @foreach ($dados as $item)
                                        <tr data-widget="expandable-table" aria-expanded="false">
                                            <th>
                                                <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                                {{ $item->sigla }} - {{ $item->nome }}
                                            </th>
                                        </tr>
                                        <tr class="expandable-body d-none">
                                            <td>
                                                <div class="p-0" style="display: none;">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            @if (count($item->items) != 0)
                                                                @foreach ($item->items as $item1)   
                                                                    @if ($item1->taxa == 0 && $item1->vida_util == 0)
                                                                    <tr>
                                                                        <th>
                                                                            <i class="expandable-table-caret fas fa-caret-down fa-fw"></i>
                                                                            {{ $item1->numero }} - {{ $item1->nome }}
                                                                        </th>
                                                                        <th class="text-right">
                                                                            Taxas %
                                                                        </th>
                                                                        <th class="text-right">
                                                                            Vida Útil Anos
                                                                        </th>
                                                                    </tr>
                                                                    @else  
                                                                    <tr>
                                                                        <td class="pl-5">
                                                                            {{ $item1->numero }} - {{ $item1->nome }}
                                                                        </td>
                                                                        <td class="text-right pl-5">
                                                                            {{ $item1->taxa == 0 ? "Taxas %" : $item1->taxa }}
                                                                        </td>
                                                                        <td class="text-right pl-5">
                                                                            {{ $item1->vida_util == 0 ? "Vida Útil Anos" : $item1->vida_util }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
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
            {{-- <div class="row">

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
            @if ($dados)
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Sigla</th>
                            <th>Designação</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($dados as $item)
                        <tr>
                            <td>{{ $item->sigla ?? '' }}</td>
                            <td>{{ $item->nome ?? '' }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @endif
        </div>
    </div>

</div> --}}
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
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
