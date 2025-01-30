@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cadastrar Loja</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('lojas.create') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Loja</li>
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
                        <form action="{{ route('lojas.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-6">
                                  <label for="" class="form-label">Nome</label>
                                  <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="Informe o nome da Loja">
                                  <p class="text-danger">
                                      @error('nome')
                                      {{ $message }}
                                      @enderror
                                  </p>
                                </div>

                                <div class="col-12 col-md-3">
                                  <label for="" class="form-label">Estado</label>
                                  <select type="text" class="form-control" name="status">
                                      {{-- <option value="activo">Activo</option> --}}
                                      <option value="desactivo" selected>Desactivo</option>
                                  </select>
                                  <p class="text-danger">
                                      @error('status')
                                      {{ $message }}
                                      @enderror
                                  </p>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                  <label for="" class="form-label">Codigo Postal <span class="text-secondary">(Opcional)</span></label>
                                  <input type="text" class="form-control" name="codigo_postal" value="{{ old('codigo_postal') }}" placeholder="Informe o Codigo Postal">
                         
                                  <p class="text-danger">
                                      @error('codigo_postal')
                                      {{ $message }}
                                      @enderror
                                  </p>
                                </div>

                                <div class="col-12 col-md-3">
                                  <label for="" class="form-label">Morada da Loja <span class="text-secondary">(Opcional)</span></label>
                                  <input type="text" class="form-control" name="morada" value="{{ old('morada') }}" placeholder="Informe a morada da Loja">
                                    <p class="text-danger">
                                        @error('morada')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                  <label for="" class="form-label">Localidade da Loja <span class="text-secondary">(Opcional)</span></label>
                                  <input type="text" class="form-control" name="localidade" value="{{ old('localidade') }}" placeholder="Informe a Localidade da Loja">
                                    <p class="text-danger">
                                        @error('localidade')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                  <label for="" class="form-label">Telefone <span class="text-secondary">(Opcional)</span></label>
                                  <input type="text" class="form-control" name="telefone" value="{{ old('telefone') }}" placeholder="Informe o Telefone">
                                  <p class="text-danger">
                                      @error('telefone')
                                      {{ $message }}
                                      @enderror
                                  </p>
                                </div>

                                <div class="col-12 col-md-3">
                                  <label for="" class="form-label">E-mail <span class="text-secondary">(Opcional)</span></label>
                                  <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Informe o E-mail">
                                  <p class="text-danger">
                                      @error('email')
                                      {{ $message }}
                                      @enderror
                                  </p>
                                </div>
                                
                                <div class="col-12 col-md-12">
                                  <label for="" class="form-label">Descrição <span class="text-secondary">(Opcional)</span></label>
                                  <textarea class="form-control" rows="2" name="descricao" placeholder="Informe a descricao da Loja ...">{{ old('descricao') }}</textarea>
                                
                                  <p class="text-danger">
                                      @error('descricao')
                                      {{ $message }}
                                      @enderror
                                  </p>
                                </div>
                                
                                

                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                <button type="reset" class="btn btn-danger">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.row -->
                </div>
            </div>



        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
