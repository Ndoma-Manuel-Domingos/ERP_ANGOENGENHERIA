@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cadastrar Fornecedor</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">fornecedor</li>
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
                    <div class="card">
                        <form action="{{ route('fornecedores.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-4">
                                    <label for="nome" class="col-form-label text-right">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Informe Nome Fornecedor">
                                    <p class="text-danger">
                                        @error('nome')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="nif" class="col-form-label text-right">NIF</label>
                                    <input type="text" class="form-control" id="nif" name="nif" value="{{ old('nif') }}" placeholder="Informe NIF">
                                    <p class="text-danger">
                                        @error('nif')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="tipo_pessoa" class="col-form-label text-right">Tipo Pessoas</label>
                                    <select type="text" class="form-control" id="tipo_pessoa" name="tipo_pessoa">
                                        <option value="JURIDICA">JURÍDICA</option>
                                        <option value="FISICA">FISÍCA</option>
                                    </select>
                                    <p class="text-danger">
                                        @error('tipo_pessoa')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="tipo_fornecedor" class="col-form-label text-right">Tipo Fornecedor</label>
                                    <select type="text" class="form-control" id="tipo_fornecedor" name="tipo_fornecedor">
                                        <option value="corrente">Corrente</option>
                                        <option value="titulos a pagar">Títulos a pagar</option>
                                        <option value="imobilizado">Imobilizados</option>
                                    </select>
                                    <p class="text-danger">
                                        @error('tipo_fornecedor')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="pais" class="col-form-label text-right">País</label>
                                    <select type="text" class="form-control" id="pais" name="pais">
                                        @include('includes.paises')
                                    </select>
                                    <p class="text-danger">
                                        @error('pais')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-4">
                                    <label for="codigo_postal" class="col-form-label text-right">Codígo Postal</label>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" placeholder="Informe codigo Postal">
                                    <p class="text-danger">
                                        @error('codigo_postal')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="localidade" class="col-form-label text-right">Localidade</label>
                                    <input type="text" class="form-control" id="localidade" name="localidade" value="{{ old('localidade') }}" placeholder="Informe  Localidade">
                                    <p class="text-danger">
                                        @error('localidade')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="telefone" class="col-form-label text-right">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') }}" placeholder="Informe Telefone">
                                    <p class="text-danger">
                                        @error('telefone')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="telemovel" class="col-form-label text-right">Telemóvel</label>
                                    <input type="text" class="form-control" id="telemovel" name="telemovel" value="{{ old('telemovel') }}" placeholder="Informe  Telemóvel">
                                    <p class="text-danger">
                                        @error('telemovel')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="email" class="col-form-label text-right">E-email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Informe  E-email">
                                    <p class="text-danger">
                                        @error('email')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="website" class="col-form-label text-right">WebSite</label>
                                    <input type="text" class="form-control" id="website" name="website" value="{{ old('website') }}" placeholder="Informe WebSite">
                                    <p class="text-danger">
                                        @error('website')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="observacao" class="col-form-label text-right">Observação</label>
                                    <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') }}" placeholder="Informe Observação">
                                    <p class="text-danger">
                                        @error('observacao')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar cliente'))
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
