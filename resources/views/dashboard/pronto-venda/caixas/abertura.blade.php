@extends('layouts.vendas')

@section('section')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header"> </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-1"></div>
                <!-- /.col-md-6 -->
                <div class="col-lg-3">
                    <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Abrir</a>   
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('caixa.abertura_caixa_create') }}" method="post" class="row">
                                @csrf
                                <div class="col-12 col-md-12 text-center">
                                    <label for="">Montante Disponível ao Abrir Caixa</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">Kz</span>
                                      </div>
                                      <input type="text" value="0" class="form-control form-control-lg" name="valor">
                                    </div>
                                @error('valor')
                                    <span>{{ $message }}</span>
                                @enderror
                                </div>

                                <div class="input-group my-3 px-5">
                                    <span class="input-group-append text-center">
                                        <button type="submit" class="btn btn-info btn-flat mx-2"><i class="fas fa-check"></i> Confirmar</button>
                                        <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-info btn-flat mx-2"><i class="fas fa-close"></i> Cancelar</a>
                                    </span>
                                </div>
                                <!-- /input-group -->

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7"> </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-1"></div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

