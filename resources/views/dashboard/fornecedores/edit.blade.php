@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Fornecedor</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Fornecedor</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="{{ route('fornecedores.update', $fornecedor->id) }}" method="post" class="">
                    @csrf
                    @method('put')

                    <div class="card-body row">

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">Nome</label>
                            <input type="text" class="form-control" name="nome" value="{{ $fornecedor->nome }}" placeholder="Informe cliente">
                            <p class="text-danger">
                                @error('nome')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">NIF</label>
                            <input type="text" class="form-control" name="nif" value="{{ $fornecedor->nif }}" placeholder="Informe NIF">
                            <p class="text-danger">
                                @error('nif')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>


                        <div class="col-12 col-md-4">
                            <label for="tipo_pessoa" class="col-form-label text-right">Tipo Pessoas</label>
                            <select type="text" class="form-control" id="tipo_pessoa" name="tipo_pessoa">
                                <option value="JURIDICA" {{ $fornecedor->tipo_pessoa == "JURIDICA" ? "selected" : "" }}>JURÍDICA</option>
                                <option value="FISICA" {{ $fornecedor->tipo_pessoa == "JURIDICA" ? "selected" : "" }}>FISÍCA</option>
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
                                <option value="corrente" {{ $fornecedor->tipo_pessoa == "Corrente" ? "selected" : "" }}>Corrente</option>
                                <option value="titulos a pagar" {{ $fornecedor->tipo_pessoa == "corrente" ? "selected" : "" }}>Títulos a pagar</option>
                                <option value="imobilizado" {{ $fornecedor->tipo_pessoa == "imobilizados" ? "selected" : "" }}>Imobilizados</option>
                            </select>
                            <p class="text-danger">
                                @error('tipo_fornecedor')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">País</label>
                            <select type="text" class="form-control" name="pais">
                                @include('includes.paises')
                            </select>
                            <p class="text-danger">
                                @error('pais')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">Codigo Postal</label>
                            <input type="text" class="form-control" name="codigo_postal" value="{{ $fornecedor->codigo_postal }}" placeholder="Informe codigo Postal">
                            <p class="text-danger">
                                @error('codigo_postal')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">Localidade</label>
                            <input type="text" class="form-control" name="localidade" value="{{ $fornecedor->localidade }}" placeholder="Informe  Localidade">
                            <p class="text-danger">
                                @error('localidade')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">Telefone</label>
                            <input type="text" class="form-control" name="telefone" value="{{ $fornecedor->telefone }}" placeholder="Informe Telefone">
                            <p class="text-danger">
                                @error('telefone')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">Telemovel</label>
                            <input type="text" class="form-control" name="telemovel" value="{{ $fornecedor->telemovel }}" placeholder="Informe  Telemóvel">
                            <p class="text-danger">
                                @error('telemovel')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">E-mail</label>
                            <input type="email" class="form-control" name="email" value="{{ $fornecedor->email }}" placeholder="Informe  E-email">
                            <p class="text-danger">
                                @error('email')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">Website</label>
                            <input type="text" class="form-control" name="website" value="{{ $fornecedor->website }}" placeholder="Informe WebSite">
                            <p class="text-danger">
                                @error('website')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="nome" class="col-form-label text-right">Observação</label>
                            <input type="text" class="form-control" name="observacao" value="{{ $fornecedor->observacao }}" placeholder="Informe Observação">
                            <p class="text-danger">
                                @error('observacao')
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
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
