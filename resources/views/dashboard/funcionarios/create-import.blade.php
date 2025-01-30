@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cadastrar Funcionários</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('funcionarios.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Funcionário</li>
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
                
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('store_import.funcionarios') }}" method="post" class="" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body row">
                             
                                <div class="col-12 col-md-12">
                                    <label for="file" class="form-label">Carregar Excel</label>
                                    <input type="file" id="file" class="form-control" name="file" value="{{ old('file') }}" placeholder="Informe  file">
                                    <p class="text-danger">
                                        @error('file')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>
        
                            </div>
        
                            <div class="card-footer">
                                @if (Auth::user()->can('criar funcionario'))
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                @endif
                                <button type="reset" class="btn btn-danger">Cancelar</button>
                            </div>
                        </form>
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
