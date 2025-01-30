@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">ESCOLA DO TIPO DE VENDAS</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Painel</li>
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
        <div class="col-12 col-md-3">
          <div class="card">
            <div class="card-header text-center py-4">
              <h3><i class="fas fa-shopping"></i></h3>
              <h3>VENDAS NORMAIS</h3>
              <p>
                Download do ficheiro SAF-T para comunicar à AGT mensalmente os documentos emitidos.
              </p>
            </div>

            <div class="card-body text-center">
              <a href="{{ route('pronto-venda') }}" class="btn-lg btn-primary d-block my-4">INICIAR</a>

              <p>Envie até ao dia 15 de cada mês</p>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-md-3">
          <div class="card">
            <div class="card-header text-center py-4">
              <h3><i class="fas fa-cart"></i></h3>
              <h3>VENDAS POR PEDIDOS</h3>
              <p>
                Refazer o ficheiro SAF-T caso hauver um erro ao exportar e tastar o saft no portal da AGT
              </p>
            </div>

            <div class="card-body text-center">
              <a href="{{ route('pronto-venda-mesas') }}" class="btn-lg btn-primary d-block my-4">INICIAR</a>

              <p>Faça sempre que o saft apresentar um erro</p>
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