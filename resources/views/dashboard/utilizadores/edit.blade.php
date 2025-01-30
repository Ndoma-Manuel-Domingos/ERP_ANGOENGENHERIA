@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Utilizador</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('utilizadores.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Utilizador</li>
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
                <form action="{{ route('utilizadores.update', $utilizador->id) }}" method="post" class="">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" name="nome" value="{{ $utilizador->name }}" placeholder="Informe a utilizador">
                                <p class="text-danger">
                                    @error('nome')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ $utilizador->email }}" placeholder="Informe E-mail">
                                <p class="text-danger">
                                    @error('email')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="roles" class="form-label">Perfil</label>
                                <select type="text" id="roles" class="form-control select2" name="roles">
                                    @foreach ($roles as $item)
                                    <option value="{{ $item->id }}" @if(in_array($item->id, $users_roles)) selected @endif>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-3">
                                <label for="status" class="form-label">Estado</label>
                                <select type="text" id="status" class="form-control select2" name="status">
                                    <option value="1" {{ $utilizador->status == "1" ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ $utilizador->status == "0" ? 'selected' : '' }}>Desactivo</option>
                                </select>
                            </div>
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
