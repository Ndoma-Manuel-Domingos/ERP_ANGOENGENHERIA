@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Novo Agendamento</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('agendamentos.create') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Agendamento</li>
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
                        <form action="{{ route('agendamentos.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="cliente_id" class="form-label">Clientes</label>
                                        <select type="text" class="form-control select2" id="cliente_id" name="cliente_id">
                                            <option value="">Selecionar</option>
                                            @foreach ($clientes as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>

                                        <p class="text-danger">
                                            @error('cliente_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                
                                    {{-- <div class="col-12 col-md-6">
                                        <label for="nome" class="form-label">Nome do Cliente</label>
                                        <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="Informe o Nome">
                                        <p class="text-danger">
                                            @error('nome')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div> --}}

                                    {{-- <div class="col-12 col-md-6">
                                        <label for="telefone" class="form-label">Telefone do Cliente</label>
                                        <input type="text" class="form-control" name="telefone" value="{{ old('telefone') }}" placeholder="Informe o Número de telefone">
                                        <p class="text-danger">
                                            @error('telefone')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div> --}}

                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="servico_id" class="form-label">Serviços</label>
                                        <select type="text" class="form-control select2" id="servico_id" name="servico_id">
                                            <option value="">Selecionar</option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>

                                        <p class="text-danger">
                                            @error('servico_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="status" class="form-label">Estados</label>
                                        <select type="text" class="form-control select2" name="status">
                                            <option value="">Selecionar</option>
                                            <option value="pendente">Pendente</option>
                                            <option value="atendido">Atendido</option>
                                            <option value="cancelado">Cancelado</option>
                                            <option value="experido">Expirado</option>
                                        </select>
                                        <p class="text-danger">
                                            @error('status')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <label for="hora" class="form-label">Hora</label>
                                        <input type="time" class="form-control" name="hora" value="{{ old('hora') }}" placeholder="Informe a hora">
                                        <p class="text-danger">
                                            @error('hora')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="data_at" class="form-label">Data</label>
                                        <input type="date" class="form-control" name="data_at" value="{{ old('data_at') }}" placeholder="Informe a data">
                                        <p class="text-danger">
                                            @error('data_at')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label for="observacao" class="form-label">Observação</label>
                                        <input type="text" class="form-control" name="observacao" value="{{ old('observacao') }}" placeholder="Digita uma observação">
                                        <p class="text-danger">
                                            @error('observacao')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar agendamento'))
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
