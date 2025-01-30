@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Movimentos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pronto-venda') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Movimentos</li>
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
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('caixa.movimentos_caixa') }}" method="get" class="mt-3">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Data inicio</span>
                                        </div>
                                        <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio">
                                    </div>
                                    <p class="text-danger">
                                        @error('data_inicio')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Data final</span>
                                        </div>
                                        <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final">
                                    </div>
                                    <p class="text-danger">
                                        @error('data_final')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Operador</span>
                                        </div>
                                        <select type="text" class="form-control select2" name="caixa_id">
                                            <option value="">Todas</option>
                                            @foreach ($caixas as $caixa)
                                            <option value="{{ $caixa->id }}" {{ $requests['caixa_id'] == $caixa->id ? 'selected' : '' }}>{{ $caixa->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('caixa_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <select type="text" class="form-control select2" name="operador_id">
                                                <option value="">Todas</option>
                                                @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ $requests['operador_id'] == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('caixa.movimentos_caixa', ['data_inicio' => $requests['data_inicio'] ?? "",'data_final' => $requests['data_final'] ?? "", 'caixa_id' => $requests['caixa_id'] ?? "", 'operador_id' => $requests['operador_id'] ?? "", 'documento_pdf' => 'exportar_pdf']) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Operador</th>
                                        <th>Caixa</th>
                                        <th>Data Abertura</th>
                                        <th>Data Fecho</th>
                                        <th style="text-align: right">V. Abertura</th>
                                        {{-- <th style="text-align: right">Valor Fecho</th> --}}
                                        <th style="text-align: right">TPA</th>
                                        <th style="text-align: right">CASH</th>
                                        <th style="text-align: right">Total</th>
                                        <th class="text-right">Estado</th>
                                        <th class="text-right">Acções</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimentos as $item)
                                    
                                    <tr>
                                        <td>{{ $item->user->name ?? "" }}</td>
                                        <td>{{ $item->caixa->nome }}</td>
                                        <td>{{ $item->data_abertura }}</td>
                                        <td>{{ $item->data_fecho }}</td>
                                        <td style="text-align: right">{{ number_format($item->valor_abertura, 2, ',', '.') }}</td>
                                        {{-- <td style="text-align: right">{{ number_format($item->valor_valor_fecho, 2, ',', '.') }}</td> --}}
                                        <td style="text-align: right">{{ number_format($item->valor_multicaixa, 2, ',', '.') }}</td>
                                        <td style="text-align: right">{{ number_format($item->valor_cash, 2, ',', '.') }}</td>
                                        
                                        @if (($item->valor_valor_fecho) < 0)
                                        <td class="text-danger" style="text-align: right">{{ number_format(($item->valor_valor_fecho), 2, ',', '.') }}</td>    
                                        @endif
                                        
                                        @if (($item->valor_valor_fecho) == 0)
                                        <td class="text-warning" style="text-align: right">{{ number_format(($item->valor_valor_fecho), 2, ',', '.') }}</td>    
                                        @endif
                                        
                                        @if (($item->valor_valor_fecho) > 0)
                                        <td class="text-success" style="text-align: right">{{ number_format(($item->valor_valor_fecho), 2, ',', '.') }}</td>    
                                        @endif
                                        
                                        @if ($item->status == false)
                                        <td class="text-danger text-right">FECHADO</td>    
                                        @else
                                        <td class="text-success text-right">ABERTO</td>    
                                        @endif
                                        
                                        <td class="text-success text-right">
                                            <a href="{{ route('caixa.movimentos_caixa_imprimir', ['id_imprimir' => $item->id]) }}" target="_blink" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Imprimir</a>
                                        </td>
                                    </tr>
                                  
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <h6>Saldo Final = ((Valor Abertura + Valor Fecho + Valor Entrada) - Valor Saída) </h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
