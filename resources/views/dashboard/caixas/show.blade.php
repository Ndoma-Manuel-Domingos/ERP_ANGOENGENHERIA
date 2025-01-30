@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><span class="text-uppercase">{{ $caixa->conta }} - {{ $caixa->nome }}</span> - Movimentos de Caixa
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('caixas.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Caixa</li>
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

                <div class="col-12 col-md-12">
                    <form action="{{ route('caixas.show', $caixa->id) }}" method="GET">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                @method('get')
                                <div class="row">
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="operador_id">Operadores</label>
                                        <select name="operador_id" id="operador_id" class="select2 form-control @error('operador_id') is-invalid @enderror">
                                            <option value="">TODOS</option>
                                            @foreach ($utilizadores as $item)
                                            <option value="{{ $item->id }}" {{ old('operador_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="data_inicio">Data Inicio</label>
                                        <input type="date" class="form-control" name="data_inicio" value="{{ old('data_inicio') ?? $requests['data_inicio'] }}">
                                    </div>

                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="data_final">Data Inicio</label>
                                        <input type="date" class="form-control" name="data_final" value="{{ old('data_final') ?? $requests['data_final'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="float-right btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('caixa.movimentos_caixa', ['data_inicio' => $requests['data_inicio'] ?? "",'data_final' => $requests['data_final'] ?? "", 'caixa_id' => $caixa->id, 'operador_id' => $requests['operador_id'] ?? "", 'documento_pdf' => 'exportar_pdf']) }}" target="_blink" class="float-right btn btn-primary">Exportar</a>
                            {{-- <a href="{{ route('caixa.movimentos_caixa', ['data_inicio' => $requests['data_inicio'] ?? "",'data_final' => $requests['data_final'] ?? "", 'caixa_id' => $caixa->id, 'operador_id' => $requests['operador_id'] ?? "", 'documento_pdf' => 'exportar_pdf']) }}" target="_blink" class="float-right btn btn-primary">Exportar</a> --}}
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Número</th>
                                        <th>Operador</th>
                                        <th>Pagamento</th>
                                        <th>Movimento</th>
                                        <th class="text-right">Credito</th>
                                        <th class="text-right">Debito</th>
                                        <th class="text-right">Acçoes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimentos as $item)
                                    <tr>
                                        <td class="text-left">{{ $item->id??0 ?? "" }}</td>
                                        <td class="text-left">{{ $item->numero ?? "" }}</td>
                                        <td class="text-left">{{ $item->user->name ?? "" }}</td>
                                                                    
                                                                        
                                        @if ($item->forma_movimento == "NU")
                                            <td class="text-left">NUMÉRARIO</td>
                                        @else
                                            <td class="text-left">MULTICAIXA</td>
                                        @endif
                                        
                                        @if ($item->movimento == "E")
                                            <td class="text-left"><i class="fas fa-arrow-up text-success"></i></td>
                                        @else
                                            <td class="text-left"><i class="fas fa-arrow-down text-danger"></i></td>
                                        @endif
                                        
                                        <td class="text-right">{{ number_format($item->credito ??0, 2, ',', '.')  }}</td>
                                        <td class="text-right">{{ number_format($item->debito ??0, 2, ',', '.')  }}</td>
                                        <td>
                                            {{-- <a href="{{ route('caixa.caixas-detalhe', $item->id) }}" class="btn btn-sm btn-outline-primary float-right mr-2">Detalhe</a> --}}
                                            <a href="{{ route('nota-de-movimento', $item->code ) }}" target="_blink" class="btn btn-sm btn-outline-primary float-right mr-2">Exportar</a>
                                            {{-- <a href="{{ route('caixa.movimentos_caixa_imprimir', ['id_imprimir' => $item->id]) }}" target="_blink" class="btn btn-sm btn-outline-primary float-right mr-2">Exportar</a> --}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
