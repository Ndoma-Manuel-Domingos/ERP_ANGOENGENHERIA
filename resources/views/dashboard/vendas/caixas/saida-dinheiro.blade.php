@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Saída de Dinheiro</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pronto-venda') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Saída de Dinheiro</li>
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
                <!-- /.col-md-6 -->
                <div class="col-lg-5 col-md-3 col-12">
                    {{-- <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Saída de Dinheiro</a>    --}}
                    <div class="card">
                        <div class="card-header bg-light p-0">
                            <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-flat d-block p-3"><i class="fas fa-arrow-left"></i> Saída de Dinheiro</a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('caixa.saida_dinheiro_caixa_create') }}" class="row" method="post">
                                @csrf
                                <div class="col-12 col-md-12">
                                    <label for="">Defina o Montante</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">Kz</span>
                                      </div>
                                      <input type="text" class="form-control  @error('montante') is-invalid @enderror form-control-lg" name="montante" placeholder="Informe um montante">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="caixa_id">Escolhe a conta do Movimento</label>
                                    <div class="input-group mb-3 mt-2 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Kz</span>
                                        </div>
                                        <select name="caixa_id" id="caixa_id" class="select2 form-control @error('caixa_id') is-invalid @enderror">
                                            @foreach ($caixas as $item)
                                            <option value="{{ $item->id }}" {{ old('caixa_id') == $item->id ? 'selected' : '' }}>{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="">Observações (opcional)</label>
                                    <div class="input-group mb-3">
                                      <input type="text" class="form-control form-control-lg @error('observacao') is-invalid @enderror" placeholder="Opcional" name="observacao">
                                    </div>
                                </div>

                                <div class="input-group my-3">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-info btn-flat mx-2"><i class="fas fa-check"></i> Confirmar</button>
                                        <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-info btn-flat mx-2"><i class="fas fa-close"></i> Cancelar</a>
                                    </span>
                                </div>
                                <!-- /input-group -->
                            </form>
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
