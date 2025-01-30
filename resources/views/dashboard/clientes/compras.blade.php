@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Compras do cliente: {{ $cliente->nome }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
            </div>
        
            <div class="row">
                <div class="col-12 bg-light">
                    <div class="card">
                        <form action="{{ route('compras.clientes', $cliente->id) }}" method="get">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-12">
                                        <label for="" class="form-label">Data Inicio</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control" value="{{ $requests['data_inicio'] ?? '' }}" name="data_inicio" placeholder="Data Inicio">
                                        </div>
                                    </div>
    
                                    <div class="col-md-3 col-12">
                                        <label for="" class="form-label">Data Final</label>
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control" value="{{ $requests['data_final'] ?? '' }}" name="data_final" placeholder="Data final">
                                        </div>
                                    </div>
                                
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary btn-sm ml-2 text-right"> <i class="fas fa-search"></i> Pesquisar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

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
                            <div class="card-tools">
                                <a href="{{ route('compras_pdf.clientes', ['cliente_id' => $cliente->id ,'data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '']) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
                            </div>
                        </div>
                    
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Produto</th>
                                        <th class="text-right">Preço</th>
                                        <th class="text-right">Quantidade</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendas as $item)
                                    <tr>
                                        <td><a href="{{ route('produtos.show', $item->id) }}">{{ $item->id }}</a></td>
                                        <td><a href="{{ route('produtos.show', $item->id) }}">{{ $item->produto->nome }}</a></td>
                                        <td class="text-right">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->valor_pagar, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="card-footer">
                            <h4 class="text-right">Total: <span>{{ number_format($total_venda , 2, ',', '.') }}</span></h4>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
