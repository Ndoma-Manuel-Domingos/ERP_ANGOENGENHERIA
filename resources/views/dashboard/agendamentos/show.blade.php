@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe Agendamento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('agendamentos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Agendamento</li>
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
                        @if ($agenda)
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="row">
                            
                                <div class="col-12 col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">Dados do Clientes</th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 120px">#</th>
                                                    <th style="width: 120px">Nome</th>
                                                    <th style="width: 120px">Telefone</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $agenda->id }}</td>
                                                    <td>{{ $agenda->cliente ? $agenda->cliente->nome : "" }}</td>
                                                    <td>{{ $agenda->cliente ? $agenda->cliente->telefone : "" }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                
                                <div class="col-12 col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">Dados da Agenda</th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 120px">Hora</th>
                                                    <th style="width: 120px">Data</th>
                                                    <th style="width: 120px">Status</th>
                                                    <th style="width: 120px">Operador</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $agenda->hora }}</td>
                                                    <td>{{ $agenda->data_at }}</td>
                                                    <td>{{ $agenda->status }}</td>
                                                    <td>{{ $agenda->user->name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer clearfix d-flex">
                            <a href="{{ route('agendamentos.edit', $agenda->id) }}" class="btn btn-sm btn-success mx-1">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('agendamentos.imprimir', $agenda->id) }}" target="_blink" class="btn btn-sm btn-info mx-1">
                                <i class="fas fa-print"></i> Imprimir
                            </a>
                            <form action="{{ route('agendamentos.destroy', $agenda->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm('Tens Certeza que Desejas excluir esta Agendamento?')">
                                    <i class="fas fa-trash"></i> Eliminar
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
