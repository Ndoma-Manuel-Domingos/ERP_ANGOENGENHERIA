@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Analise do Stock</h1>
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

        <div class="col-12">
          <form action="{{ route('resumo-relatorio') }}" method="get">
            @csrf
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <a href="{{ route('estoques.create') }}" class="btn btn-sm btn-primary">Actualizar Stock</a>
                </h3>
              </div>

              <div class="card-body">
              
                <div class="col-12">
                  <div class="input-group mb-3 row">
                    <div class="col-12 col-md-3 text-right">
                      <span class="">Período de Comparação:</span>
                    </div>
                    <div class="col-12 col-md-7 ">
                      <select type="text" class="form-control" name="periodo" disabled>
                        <option value="1_mes">1 Mês</option>
                        <option value="7_dias">7 Dias</option>
                        <option value="21_dias">21 Dias</option>
                        <option value="2_meses">2 Meses</option>
                        <option value="3_meses">3 Meses</option>
                        <option value="6_meses">6 Meses</option>
                        <option value="1_ano">1 Ano</option>
                      </select>  
                      <p><small>O período de comparação permite calcular a Previsão de stock com base nas vendas, por exemplo, dos últimos 30 dias.</small></p>
                    </div>
                  </div>
                  <p class="text-danger">
                    @error('periodo')
                    {{ $message }}
                    @enderror
                  </p>
                </div>

                <div class="col-12">
                  <div class="input-group mb-3 row">
                    <div class="col-12 col-md-3 text-right">
                      <span class="">Loja ou Armazém</span>
                    </div>
                    <div class="col-12 col-md-7 ">
                      <select type="text" class="form-control" name="loja_id" disabled>
                        <option value="">Todas lojas</option>
                        @if ($lojas)
                            @foreach ($lojas as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        @endif
                      </select>  
                      <p><small>Indique as Lojas/Armazéns em que pretende fazer análise. Se não indicar nenhuma, serão todos seleccionados.</small></p>
                    </div>
                  </div>
                  <p class="text-danger">
                    @error('loja_id')
                    {{ $message }}
                    @enderror
                  </p>
                </div>

                <div class="col-12">
                  <div class="input-group mb-3 row">
                    <div class="col-12 col-md-3 text-right">
                      <span class="">Estado do Stock:</span>
                    </div>
                    <div class="col-12 col-md-7 ">
                      <select type="text" class="form-control" name="stock" disabled>
                        <option value="">Todos tipo de stock</option>
                        <option value="positivo">Positivo</option>
                        <option value="negativo">Negativo ou Nulo</option>
                        <option value="menor">Menor que Alerta</option>
                      </select>  
                      <p><small>Filtre o estado do stock do produto. Caso não escolha, serão todos incluídos.</small></p>
                    </div>
                  </div>
                  <p class="text-danger">
                    @error('stock')
                    {{ $message }}
                    @enderror
                  </p>
                </div>

              </div>

              <div class="card-footer text-center">
                {{-- <button type="submit" class="btn btn-primary">Gerar Relatório</button> --}}
              </div>

            </div>
          </form>
          <!-- /.card -->
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection