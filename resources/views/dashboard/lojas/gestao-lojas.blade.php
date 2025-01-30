@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Gestão de Lojas/Armazém</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Gestão</li>
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
      </div>
      
      <div class="row">
        <div class="col-12">
          @if ($lojas)
            @foreach ($lojas as $loja)
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <a href="{{ route('gestao-lojas-armazem-detalhe', $loja->id) }}" class="text-info text-uppercase">{{ $loja->nome }}</a>
                  </h3>
                  
                  <a href="{{ route('gestao-lojas-armazem-detalhe', $loja->id) }}" class="btn-sm btn-primary float-right">Mais Detalhe <i class="fas fa-info"></i></a>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th></th>
                        <th class="text-right">Stock Minimo</th>
                        <th class="text-right">Stock Alerta</th>
                        <th class="text-right">Stock</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_stock_minimo = 0;
                            $total_stock_alerta = 0;
                            $total_stock = 0;
                        @endphp
                        
                      @foreach ($loja->produtos_estoques as $item)
                      
                        @php
                            $total_stock_minimo += $item->stock_minimo;
                            $total_stock_alerta += $item->stock_alerta;
                            $total_stock += $item->stock;
                        @endphp
                          
                      {{-- <tr>
                        <td></td>
                        <td class="text-right">{{ $item->stock_minimo }}</td>
                        
                        @if ($item->stock > 50)
                        <td class="text-right text-warning">Excesso</td>
                        @endif

                        @if ($item->stock <= 10) 
                        <td class="text-right text-danger">Alerta</td>
                        @endif

                        @if ($item->stock > 10 AND $item->stock <= 50) 
                        <td class="text-right text-success">Normal</td>
                        @endif
                        
                        <td class="text-right">{{ $item->stock }}</td>
                      </tr> --}}
                      @endforeach
                    </tbody>
                    
                    <tfoot>
                        <tr>
                            <th class="text-left">TOTAL</th>
                            <th class="text-right">{{ $total_stock_minimo }}</th>
                            <th class="text-right">{{ $total_stock_alerta }}</th>
                            <th class="text-right">{{ $total_stock }}</th>
                        </tr>
                    </tfoot>
                  </table>
                </div>
                
                
                <div class="card-footer">
                
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection