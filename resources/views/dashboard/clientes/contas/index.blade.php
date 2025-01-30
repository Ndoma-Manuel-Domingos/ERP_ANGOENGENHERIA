@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Listagem Conta Corrente</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Conta</li>
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
 
              <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
        
                    <div class="info-box-content text-right">
                      <span class="info-box-text">Saldo Total</span>
                      <h5 class="info-box-number">{{ number_format($facturasVencidas + $facturasVencidasCorrente , 2, ',', '.')  }}  {{ $loja->empresa->moeda }}</h5>
                      @if (($facturasVencidas + $facturasVencidasCorrente) > 0)
                        <span class="info-box-text text-success">Existe dívida</span>
                      @else
                        <span class="info-box-text">Não existem dívidas</span>
                      @endif
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
        
                    <div class="info-box-content text-right">
                      <span class="info-box-text">Dívida Corrente</span>
                      <h5 class="info-box-number">{{ number_format($facturasVencidasCorrente, 2, ',', '.')  }}  {{ $loja->empresa->moeda }}</h5>
                      @if ($facturasVencidasCorrente > 0)
                        <span class="info-box-text text-success">Existem pagamentos pendentes</span>
                      @else
                        <span class="info-box-text">Não existem pagamentos pendentes</span>
                      @endif
                     
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
        
                    <div class="info-box-content text-right">
                      <span class="info-box-text">Dívida Vencida</span>
                      <h5 class="info-box-number">{{ number_format($facturasVencidas, 2, ',', '.') }}  {{ $loja->empresa->moeda }}</h5>
                      @if ($facturasVencidas > 0)
                        <span class="info-box-text text-success">Existem pagamentos fora do prazo</span>
                      @else
                        <span class="info-box-text">Não existem pagamentos fora do prazo</span>
                      @endif
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
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