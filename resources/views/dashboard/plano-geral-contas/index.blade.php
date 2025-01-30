@extends('layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Plano Geral de Contas</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                            <li class="breadcrumb-item active">PGC</li>
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
                                {{-- <h3 class="card-title">
                                    @if (Auth::user()->can('criar conta'))
                                        <a href="{{ route('contas.create') }}" class="btn btn-sm btn-primary">Nova Conta</a>
                                    @endif
                                </h3> --}}

                                <div class="card-tools">
                                    <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                    <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
                                </div>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                    <tbody>
                                        @foreach ($plano as $classe)
                                            <tr>
                                                <th class="text-uppercase"><a href="{{ route('classes.edit', $classe->id) }}"><strong class="text-dark">{{ $classe->conta }} - {{ $classe->nome }}</strong></a></th>
                                            </tr>
                                            @foreach ($classe->contas as $conta)
                                                <tr>
                                                    <th style="padding-left: 70px"><a href="{{ route('contas.edit', $conta->id) }}"><strong class="text-dark">{{ $conta->conta }} - {{ $conta->nome }}</strong></a> <a href="{{ route('subcontas.create',['subconta_id' => $conta->id]) }}"><i class="fas fa-plus"></i></a></th>
                                                </tr>
                                                @foreach ($conta->subcontas as $subconta)
                                                    <tr>
                                                        @if ($subconta->tipo_conta == "G")
                                                        <td style="padding-left: 150px"><a href="{{ route('subcontas.edit', $subconta->id) }}"><strong class="text-dark">{{ $subconta->numero }} - {{ $subconta->nome }}</strong></a> <a href="{{ route('subcontas.create',['subconta_id' => $subconta->conta_id]) }}"><i class="fas fa-plus"></i></a></td>
                                                        @endif
                                                        @if ($subconta->tipo_conta == "E")
                                                        <td style="padding-left: 120px"><a href="{{ route('subcontas.edit', $subconta->id) }}"><strong class="text-dark">{{ $subconta->numero }} - {{ $subconta->nome }}</strong></a> <a href="{{ route('subcontas.create',['subconta_id' => $subconta->conta_id]) }}" class="ml-5">Nova <i class="fas fa-plus"></i> </a></td>
                                                        @endif
                                                        @if ($subconta->tipo_conta == "M")
                                                        <td style="padding-left: 180px"><a href="{{ route('subcontas.edit', $subconta->id) }}" class="text-dark">{{ $subconta->numero }} - {{ $subconta->nome }}</a></td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
    {{-- <script>
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
    </script> --}}
@endsection
