@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Stock Por Produto</h1>
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
                        <form action="{{ route('vendas_por_artigo') }}" method="get">
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
    
                                    <div class="col-md-3 col-12">
                                        <label for="loja_id" class="form-label">Lojas/Armazém</label>
                                        <select type="text" class="form-control select2" id="loja_id" name="loja_id">
                                            <option value="">Selecione</option>
                                            @foreach ($empresa->lojas as $item)
                                            <option value="{{ $item->id }}" {{ $requests['loja_id'] == $item->id ? 'selected' : ''}}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
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
                                <a href="{{ route('pdf-stock-artigos', ['data_inicio' => $requests['data_inicio'] ?? '', 'data_final' => $requests['data_final'] ?? '', 'loja_id' => $requests['loja_id'], 'user_id' => $requests['user_id']]) }}" target="_blink" class="btn btn-sm btn-info float-right"><i class="fas fa-file-pdf"></i> Exportar PDF</a>
                            </div>
                        </div>
                    
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Produto</th>
                                        <th class="text-right">Preço</th>
                                        <th class="text-right">Custo</th>
                                        <th class="text-right">Imposto</th>
                                        <th class="text-right">Desconto</th>
                                        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Stock"))
                                        <th class="text-right">Qtd Consumidas</th>
                                        @else
                                        <th class="text-right">Qtd Vendidas</th>
                                        @endif
                                        <th class="text-right">Qtd no Stock</th>
                                        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Stock"))
                                        <th class="text-right">Total Liq. Consumidas</th>
                                        @else
                                        <th class="text-right">Total Liq. Vendidas</th>
                                        @endif
                                        <th class="text-right">Total Liq. Stock</th>
                                        <th class="text-right">Total Geral</th>
                                        
                                        {{-- <th class="text-right">Custo Geral</th> --}}
                                        <th class="text-right">Lucro Geral</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @php
                                        $total_liquido_vendido_valor = 0;
                                        $total_liquido_restante_valor = 0;
                                        $total_liquido_geral_valor = 0;
                                        $custo = 0;
                                        $lucro = 0;
                                    @endphp                         
                                
                                    @foreach ($vendas as $item)
                                  
                                    <tr>
                                        <td><a href="{{ route('produtos.show', $item->id) }}">{{ $item->id }}</a></td>
                                        <td><a href="{{ route('produtos.show', $item->id) }}">{{ $item->produto }}</a></td>
                                        <td class="text-right">{{ number_format($item->preco, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->custo, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->imposto, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->desconto, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->quantidade_vendida, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->quantidade_estoque, 2, ',', '.') }}</td>
                                        
                                        <td class="text-right">{{ number_format($item->total_liquido_vendido, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->preco * $item->quantidade_estoque, 2, ',', '.') }}</td>
                                        <td class="text-right">{{ number_format($item->total_liquido_geral, 2, ',', '.') }}</td>
                                        
                                        <td class="text-right">{{ number_format($item->total_liquido_custo, 2, ',', '.') }}</td>
                                        
                                        @php
                                            $total_liquido_vendido_valor += $item->total_liquido_vendido;
                                            $total_liquido_restante_valor += $item->preco * $item->quantidade_estoque;
                                            $total_liquido_geral_valor += $item->total_liquido_geral;
                                            
                                            $custo += ($item->custo * $item->quantidade_vendida);
                                            $lucro += $item->total_liquido_custo;
                                        @endphp 
                                    </tr>
                                    @endforeach
                                    
                                    <tr>
                                        <th>TOTAL</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        <th class="text-right">---</th>
                                        
                                        <th class="text-right">{{ number_format($total_liquido_vendido_valor, 2, ',', '.') }}</th>
                                        <th class="text-right">{{ number_format($total_liquido_restante_valor, 2, ',', '.') }}</th>
                                        <th class="text-right">{{ number_format($total_liquido_geral_valor, 2, ',', '.') }}</th>
                                        
                                        <th class="text-right">{{ number_format($lucro, 2, ',', '.') }}</th>
                                    </tr>
                                    
                                </tbody>
                            </table>
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
