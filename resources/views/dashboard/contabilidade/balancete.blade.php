@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Balancete</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Voltar</a></li>
                        <li class="breadcrumb-item active">Balancete</li>
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
                <div class="col-12 col-md-12">
                    <form action="{{ route('contabilidade-balancete') }}" method="get">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="subconta_id" class="form-label">Contas</label>
                                        <select name="subconta_id" id="subconta_id" class="select2 form-control">
                                            <option value="">TODAS</option>
                                            @foreach ($subcontas as $item)
                                                <option value="{{ $item->id }}" {{ $requests['subconta_id'] == $item->id  ? 'selected' : "" }}>{{ $item->numero }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                        <label for="exercicio_id" class="form-label">Exercícios</label> 
                                        <select name="exercicio_id" id="exercicio_id" class="select2 form-control">
                                            <option value="">TODOS</option>
                                            @foreach ($exercicios as $item)
                                                <option value="{{ $item->id }}" {{ $requests['exercicio_id'] == $item->id  ? 'selected' : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-12 col-md-2">
                                        <label for="periodo_id" class="form-label">Períodos</label>
                                        <select name="periodo_id" id="periodo_id" class="select2 form-control">
                                            <option value="">TODOS</option>
                                            @foreach ($periodos as $item)
                                                <option value="{{ $item->id }}" {{ $requests['periodo_id'] == $item->id  ? 'selected' : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-12 col-md-2">
                                        <label for="data_inicio" class="form-label">Data Início</label>
                                        <input type="date" name="data_inicio" value="{{ $requests['data_inicio'] ?? old('data_inicio') }}" id="data_inicio" class="form-control" >
                                    </div>
                                    
                                    <div class="col-12 col-md-2">
                                        <label for="data_final" class="form-label">Data Final</label>
                                        <input type="date" name="data_final" value="{{ $requests['data_final'] ?? old('data_final') }}" id="data_final" class="form-control" >
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn-primary btn-sm">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        @if ($movimentos)
                            <!-- /.card-header -->
                            <div class="card-header">
                                <div class="card-tools">
                                    <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                                    <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i>EXCEL</a>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Conta</th>
                                            <th>Observação</th>
                                            <th>Data</th>
                                            <th class=" text-right">Crédito</th>
                                            <th class=" text-right">Debito</th>
                                            <th class=" text-right">Saldo</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $credito = 0;
                                        $debito = 0;
                                        $saldo = 0;
                                    @endphp
                
                                    <tbody>
                                        @foreach ($movimentos as $item)
                                            <tr>
                                                <td>{{ $item->subconta->numero ?? '' }} - {{ $item->subconta->nome ?? '' }}</td>
                                                <td>{{ $item->observacao ?? '' }}</td>
                                                <td>{{ $item->data_at ?? '' }}</td>
                                                <td class="text-danger text-right">{{ $item->credito == 0 ? '-' : number_format($item->credito ?? 0, 2, ',', '.')  }}</td>
                                                <td class="text-info text-right">{{ $item->debito == 0? '-' : number_format($item->debito ?? 0, 2, ',', '.') }}</td>
                                                @if ($item->credito > $item->debito)
                                                    @if (($item->credito - $item->debito) == 0)
                                                    <td class="text-danger text-right">-</td>
                                                    @else    
                                                    <td class="text-info text-right">{{ number_format($item->credito - $item->debito, 2, ',', '.') }}</td>
                                                    @endif
                                                @else
                                                    @if ($item->debito > $item->credito)
                                                        @if (($item->debito - $item->credito) == 0)
                                                        <td class="text-danger text-right">-</td>
                                                        @else
                                                        <td class="text-info text-right">{{ number_format($item->debito - $item->credito, 2, ',', '.') }}</td>
                                                        @endif
                                                    @else
                                                    <td class="text-danger text-right">{{ number_format(0, 2, ',', '.') }}</td>
                                                    @endif
                                                @endif  
                                            </tr>
                                            
                                            @php
                                                $credito += $item->credito;
                                                $debito += $item->debito;
                                                $saldo = 0;
                                            @endphp
                                            
                                        @endforeach
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>-</th>
                                            <th>-</th>
                                            <th>-</th>
                                            <th class="text-right text-danger">{{ number_format($credito ?? 0, 2, ',', '.') }}</th>
                                            <th class="text-right text-info">{{ number_format($debito ?? 0, 2, ',', '.') }}</th>
                                            
                                            
                                            @if ($credito > $debito)
                                                @if (($credito - $debito) == 0)
                                                <td class="text-danger text-right">-</td>
                                                @else    
                                                <td class="text-info text-right">{{ number_format($credito - $debito, 2, ',', '.') }}</td>
                                                @endif
                                            @else
                                                @if ($debito > $credito)
                                                    @if (($debito - $credito) == 0)
                                                    <td class="text-danger text-right">-</td>
                                                    @else
                                                    <td class="text-info text-right">{{ number_format($debito - $credito, 2, ',', '.') }}</td>
                                                    @endif
                                                @else
                                                <td class="text-danger text-right">{{ number_format(0, 2, ',', '.') }}</td>
                                                @endif
                                            @endif 
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
