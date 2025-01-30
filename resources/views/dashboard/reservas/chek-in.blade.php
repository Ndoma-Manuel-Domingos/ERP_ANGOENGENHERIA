@extends('layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Chech In Diário</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Voltar</a></li>
                            <li class="breadcrumb-item active">Reserva</li>
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
                            <form action="{{ route('reservas.check_in_diario') }}" method="get" class="mt-3">
                                @csrf
                                <div class="card-body row">
                                    <div class="col-12 col-md-3">
                                        <label for="data_inicio" class="form-label">Data Inicio</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control"
                                                value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio"
                                                placeholder="Data Inicio">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_final" class="form-label">Data Final</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control"
                                                value="{{ $requests['data_final'] ?? '' }}" name="data_final"
                                                placeholder="Data final">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="cliente_id" class="form-label">
                                            @if ($tipo_entidade_logado->empresa->tem_permissao('Gestão Hotelaria'))
                                                Hospodes
                                            @else
                                                Clientes
                                            @endif
                                        </label>
                                        <select type="text" class="form-control select2" name="cliente_id">
                                            <option value="">Selecione o
                                                @if ($tipo_entidade_logado->empresa->tem_permissao('Gestão Hotelaria'))
                                                    Hospode
                                                @else
                                                    Cliente
                                                @endif
                                            </option>
                                            @foreach ($clientes as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['cliente_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="cliente_id" class="form-label">
                                            Quartos
                                        </label>
                                        <select type="text" class="form-control select2" name="cliente_id">
                                            <option value="">Selecione o Quarto
                                            </option>
                                            @foreach ($quartos as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['cliente_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary btn-sm ml-2 text-right"> <i
                                            class="fas fa-search"></i> Pesquisar</button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    @if (Auth::user()->can('criar reserva'))
                                    <a href="{{ route('reservas.create') }}" class="btn btn-sm btn-primary">Fazer Nova
                                        Reserva</a>
                                    @endif
                                </h3>

                                <div class="card-tools">
                                    <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                    <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i>
                                        EXCEL</a>
                                </div>
                            </div>

                            @if ($reservas)
                                <div class="card-body table-responsive">
                                    <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">#</th>
                                                <th rowspan="2">Nome Completo</th>
                                                <th rowspan="2">Quarto</th>
                                            
                                                <th class="text-center">Previsão Entrada</th>
                                                <th class="text-center">Previsão Saída</th>
                                                <th class="text-center">Check IN</th>
                                                <th class="text-center">Check OUT</th>
                                                <th rowspan="2">Estado</th>
                                                {{-- <th rowspan="2">Exercício</th> --}}
                                                {{-- <th rowspan="2">Periodo</th> --}}
                                                <th rowspan="2">Dias</th>
                                                <th rowspan="2">Pagamento</th>
                                                <th rowspan="2">Total Factura</th>
                                                <th rowspan="2">Acções</th>
                                            </tr>
                                            
                                            <tr>
                                                {{-- <th>-</th>
                                                <th>-</th>
                                                <th>-</th>
                                                <th>-</th>
                                                <th>-</th> --}}
                                                <th class="text-center">Data/Hora</th>
                                                <th class="text-center">Data/Hora</th>
                                                
                                                <th class="text-center">Data/Hora</th>
                                                <th class="text-center">Data/Hora</th>
                                                {{-- <th>-</th>
                                                <th>-</th>
                                                <th>-</th>
                                                <th>-</th>
                                                <th>-</th> --}}
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            @foreach ($reservas as $item)
                                                <tr style="background-color: {{ $item->status == 'CANCELADO' ? 'rgba(138, 39, 39, .3)' : '' }}">
                                                    <td>{{ $item->id }}</td>
                                                    <td><a href="{{ route('clientes.show', $item->cliente->id) }}">{{ $item->cliente->nome }}</a></td>
                                                    <td><a href="{{ route('quartos.show', $item->quarto->id) }}">{{ $item->quarto->nome }}</a></td>
                                             
                                                    <td>{{ $item->data_inicio }} - {{ $item->hora_entrada }}</td>
                                                    <td>{{ $item->data_final }} - {{ $item->hora_saida }}</td>
                                                    
                                                    <td>{{ $item->data_check_in }} - {{ $item->hora_check_in }}</td>
                                                    <td>{{ $item->data_check_out }} - {{ $item->hora_check_out }}</td>
                                                    <td>{{ $item->status }}</td>
                                                    {{-- <td>{{ $item->exercicio->nome }}</td> --}}
                                                    {{-- <td>{{ $item->periodo->nome }}</td> --}}
                                                    <td>{{ $item->total_dias }}</td>
                                                    @if ($item->pagamento == "EFECTUADO")
                                                    <td class="text-success">{{ $item->pagamento }}</td>
                                                    @endif
                                                    @if ($item->pagamento == "NAO EFECTUADO")
                                                    <td class="text-danger">{{ $item->pagamento }}</td>
                                                    @endif
                                                    <td>{{ number_format($item->valor_total ??0, 2, ',', '.')  }}</td>
    
                                                    <td class="text-right">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default">Ações</button>
                                                            <button type="button"
                                                                class="btn btn-default dropdown-toggle dropdown-icon"
                                                                data-toggle="dropdown">
                                                                <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <div class="dropdown-menu" role="menu">
                                                                @if (Auth::user()->can('listar reserva'))
                                                                    <a class="dropdown-item"
                                                                      href="{{ route('reservas.show', $item->id) }}"><i
                                                                      class="fas fa-eye text-info"></i> Detalhes
                                                                    </a>
                                                                @endif
                                                                @if (Auth::user()->can('editar reserva'))
                                                                    @if ($item->pagamento == "NAO EFECTUADO" && $item->status != "CANCELADO")
                                                                    <a class="dropdown-item" href="{{ route('reservas-fazer-pagamento', $item->id) }}"><i class="fas fa-pager text-success"></i> Efecturar Pagamento</a>
                                                                    @endif
                                                                    @if ($item->status != "CANCELADO")
                                                                    <a class="dropdown-item" href="{{ route('reservas-anulacao', $item->id) }}"><i class="fas fa-cancel text-danger"></i> Anular</a>
                                                                    @endif
                                                                    
                                                                    @if ($item->pagamento == "NAO EFECTUADO" && $item->status == "PENDENTE")
                                                                    <a class="dropdown-item" href="{{ route('reservas.edit', $item->id) }}"><i class="fas fa-edit text-success"></i> Editar</a>
                                                                    @endif
                                                                    
                                                                    @if ($item->check == "PENDENTE")
                                                                    <a class="dropdown-item" href="{{ route('reservas.check_in', $item->id) }}"><i class="fas fa-check text-success"></i> Check In</a>
                                                                    @endif
                                                                    @if ($item->check == "IN")
                                                                    <a class="dropdown-item" href="{{ route('reservas.check_out', $item->id) }}"><i class="fas fa-times text-danger"></i> Check Out</a>
                                                                    @endif
                                                                @endif
                                                                
                                                                @if (Auth::user()->can('listar reserva'))
                                                                    @if ($item->check == "IN")
                                                                        <a class="dropdown-item"
                                                                          href="{{ route('pronto-venda-mesas-quartos', Crypt::encrypt($item->quarto_id)) }}"><i
                                                                          class="fas fa-eye text-info"></i> Fazer Pedidos
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                                
                                                                <div class="dropdown-divider"></div>
                                                                @if (Auth::user()->can('eliminar reserva'))
                                                                    <form
                                                                        action="{{ route('reservas.destroy', $item->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-danger dropdown-item"
                                                                            onclick="return confirm('Tens Certeza que Desejas excluir esta Reserva?')">
                                                                            <i class="fas fa-trash text-danger"></i>
                                                                            Eliminar
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
