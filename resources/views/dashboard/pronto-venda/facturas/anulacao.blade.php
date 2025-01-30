@extends('layouts.vendas')

@section('section')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Anulação de Facturas</h1>
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
        <div class="col-12 col-md-1"></div>
        <div class="col-12 col-md-10">
          <div class="card">
           
            <div class="card-body">
                <h4>O que pretende fazer?</h4>
                <p class="h5">Escolha o tipo de operação que pretende fazer relativamente a uma factura.</p>

                <div class="row mt-5">
                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ route('pronto-venda.facturas-trocarItens') }}">
                            <div class="info-box shadow-sm">
                              <span class="info-box-icon bg-secondary"><i class="fas fa-exchange-alt"></i></span>
                
                              <div class="info-box-content">
                                <span class="info-box-text h3">Trocar Itens</span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </a>
                    </div>
                    <!-- /.col -->

                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ route('pronto-venda.facturas-devolucao') }}">
                            <div class="info-box shadow-sm">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-undo"></i></span>
                
                            <div class="info-box-content">
                                <span class="info-box-text h3">Devoluções de Itens</span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </a>
                    </div>
                    <!-- /.col -->

                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ route('pronto-venda.facturas-anulacao') }}">
                            <div class="info-box shadow-sm">
                              <span class="info-box-icon bg-secondary"><i class="fas fa-times"></i></span>
                
                              <div class="info-box-content">
                                <span class="info-box-text h3">Anulação de Documento</span>
                              </div>
                              <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </a>
                    </div>
                    <!-- /.col -->


                  
                  </div>
                  <!-- /.row -->





            </div>
          </div>
          <!-- /.card -->          
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection


