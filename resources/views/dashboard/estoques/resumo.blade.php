@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Resumo</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Stock</li>
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
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Resumo do Relatório</h3>
            </div>

            <div class="card-body row">
              @php
                  $total = 0;
                  $stock = 0;
                  $contarProduto = 0;
                  $contarPositivo = 0;
                  $contarNegativo = 0;
              @endphp
              @foreach ($resultados as $u)
                @php
                  $total = $total + ($u->produto->preco_custo * $u->stock);
                  $stock = $stock + $u->stock;
                  $contarProduto++;

                  if($u->stock > 0){
                    $contarPositivo++;
                  }else{
                    $contarNegativo++;
                  }
                @endphp
              @endforeach
              <div class="col-4 text-right">
                <p>Valor em Stock</p>
                <h4>{{ number_format($total, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</h4>
                <p>{{ number_format($stock, 0, ',', '.') }} Unidades em Stock</p>
              </div>

              <div class="col-4 text-right">
                <p>Previsão Stock</p>
                <h4>0,00 Kz</h4>
                <p>Previsão Média do Stock</p>
              </div>

              <div class="col-4 text-right">
                <p>Total Produtos / Stock Positivo</p>
                <h4>{{ number_format($contarProduto, 0, ',', '.') }} / {{ $contarPositivo }}</h4>
                <p>1{{ $contarNegativo }} sem stock / 0 stock negativo</p>
              </div>

            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>


      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <p> Listagem de Produtos </p>
            </div>
            <div class="card-body">
              <table class="table text-sm">
                <thead>
                  <tr>
                    <th>Produto</th>
                    <th>P. Custo</th>
                    <th>Stock</th>
                    <th>Stock Valor</th>
                    <th>Stock Previsão</th>
                    <th>Venda/Dia</th>
                    <th>Uni. Vendidas</th>
                    <th>Uni. Entrada stock</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($resultados)
                    @foreach ($resultados as $item2)
                      @php
                        $total = $item2->produto->preco_custo * $item2->stock;
                      @endphp
                      <tr>
                        <td>
                          <a href="{{ route('produtos.show', $item2->produto->id) }}">{{ $item2->produto->nome }} </a><br> 
                          <small>{{ $item2->produto->referencia }}</small>
                        </td>
                        <td>
                          {{ number_format($item2->produto->preco_custo, 2, ',', '.') }} {{ $empresa->empresa->moeda }} <br>
                          <small>PVP: {{ number_format($item2->produto->preco_venda, 2, ',', '.') }} {{ $empresa->empresa->moeda }} </small>
                        </td>
                        <td>{{ $item2->stock }}</td>
                        <td>{{ number_format($item2->produto->preco_custo * $item2->stock, 2, ',', '.') }} {{ $empresa->empresa->moeda }}</td>
                        <td>*</td>
                        <td>0 Uni</td>
                        <td>0 Uni</td>
                        <td><a href="">Dias</a> <br>{{ number_format($item2->stock, 0, ',', '.') }} Uni</td>
                      </tr>     
                    @endforeach
                  @else
                    <tr>
                      <td colspan="8">Não foram encontrados resultados</td>
                    </tr>
                  @endif
  
                </tbody>
              </table>
            </div>
            <div class="card-footer">
              <a href="{{ route('imprimir-resumo-relatorio') }}" class="btn btn-primary">Imprimir</a>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
  
            <div class="card-header">
              <p>Legenda</p>
            </div>
  
            <div class="card-body text-sm">
              <p><i class="fas fa-info-circle"></i> A Análise de Stock permite-lhe obter informações como Valorização, Previsão (com base nas vendas registadas), Último Preço de Custo, Última Entrada Stock, entre outras informações.</p>
            </div>

            <table class="table text-sm">
              <thead>
                <tr>
                  <th class="text-right" width="300px">Coluna</th>
                  <th>Descrição</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td class="text-right">P. Custo</td>
                  <td>Preço de Custo do Produtos</td>
                </tr>

                <tr>
                  <td class="text-right">Stock Valor</td>
                  <td>Valorização do Stock com base nas unidades em stock e respetivo preço de custo</td>
                </tr>

                <tr>
                  <td class="text-right">Stock Previsão</td>
                  <td>Previsão do Stock com base nas vendas realizadas nos últimos dias. Se for inferior a 7 dias é indicado com um símbolo de alerta.</td>
                </tr>

                <tr>
                  <td class="text-right">Vendas/Dia</td>
                  <td>Média de vendas por dia com base nos últimos dias</td>
                </tr>
                <tr>
                  <td class="text-right">Uni. Vendidas</td>
                  <td>Número total de unidades vendidas nos últimos dias</td>
                </tr>

                <tr>
                  <td class="text-right">U. Preço Compra</td>
                  <td>Último Preço de Compra ao fornecedor (VS P. Custo é a diferença para o preço de custo atual)</td>
                </tr>

                <tr>
                  <td class="text-right">U. Encomenda</td>
                  <td>Última Encomenda realizada e respetivas unidades</td>
                </tr>

                <tr>
                  <td class="text-right">U. Entrada Stock</td>
                  <td>Última Entrada em Stock</td>
                </tr>
              </tbody>
            </table>
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