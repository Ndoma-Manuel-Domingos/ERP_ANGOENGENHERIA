@extends('layouts.app')

@section('content')

<style>
    .fc-toolbar h2 {
        text-transform: capitalize;
    }

    .fc-day-header {
        text-transform: capitalize;
    }

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Fazer nova Reserva: Nº {{ $reserva->id }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Reservas</li>
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
                    <form action="{{ route('reservas-fazer-pagamento-store') }}" method="post">
                        @csrf
                        @method('post')
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <label for="cliente_id" class="form-label">Clientes</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('clientes.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" disabled class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                                                @foreach ($clientes as $item)
                                                <option value="{{ $item->id }}" {{ $reserva->cliente_id == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="quarto_id" class="form-label">Quartos</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('quartos.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" disabled class="form-control @error('quarto_id') is-invalid @enderror" id="quarto_id" name="quarto_id">
                                                @foreach ($quartos as $item)
                                                <option value="{{ $item->id }}" {{ $reserva->quarto_id == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_entrada" class="form-label">Data de Entrada</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" disabled class="form-control  @error('data_entrada') is-invalid @enderror" name="data_entrada" id="data_entrada" value="{{ $reserva->data_inicio ?? old('data_entrada') }}" placeholder="Informe a quarto">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_saida" class="form-label">Data de Saída</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" disabled class="form-control  @error('data_saida') is-invalid @enderror" name="data_saida" id="data_saida" value="{{ $reserva->data_final ?? old('data_saida') }}" placeholder="Informe a quarto">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="total_dias" class="form-label">Total de Dias</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" disabled class="form-control  @error('total_dias') is-invalid @enderror" name="total_dias" id="total_dias" value="{{ $reserva->total_dias ?? old('total_dias') }}" placeholder="Informe o total de dias">
                                        </div>
                                    </div>

                                    <input type="hidden" id="reserva_id" name="reserva_id" value="{{ $reserva->id }}" class="form-control" readonly>

                                    <div class="col-12 col-md-3">
                                        <label for="total_pessoas" class="form-label">Total de Pessoas</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" disabled class="form-control  @error('total_pessoas') is-invalid @enderror" name="total_pessoas" id="total_pessoas" value="{{ $reserva->total_pessoas ?? old('total_pessoas') }}" placeholder="Informe o total de dias">
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-3">
                                        <label for="exercicio_id" class="form-label">Exercícios</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('exercicios.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select disabled class="form-control select2 @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                                                @foreach ($exercicios as $item)
                                                <option value="{{ $item->id }}" {{ $reserva->exercicio_id == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="tarefario_id" class="form-label">Terifários</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('tarefarios.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select disabled class="form-control @error('tarefario_id') is-invalid @enderror" id="tarefario_id" name="tarefario_id">
                                                @foreach ($tarefarios as $item)
                                                <option value="{{ $item->id }}" {{ $reserva->tarefario_id == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-3">
                                        <label for="actualizar_check_in" class="form-label">Fazer Check-In?</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select class="form-control @error('actualizar_check_in') is-invalid @enderror" id="actualizar_check_in" name="actualizar_check_in">
                                                <option value="sim" selected>Sim</option>
                                                <option value="nao">Não</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-3" id="form_forma_pagamento">
                                        <label for="forma_pagamento_id" class="form-label text-right">Forma de Pagamento</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select class="form-control" id="forma_pagamento_id" name="forma_pagamento_id">
                                                <option value="">Escolher</option>
                                                @foreach ($forma_pagamentos as $item)
                                                <option value="{{ $item->tipo }}">{{ $item->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3" id="form_caixas" style="display: none">
                                        <label for="caixa_id" class="form-label text-right">Caixas</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select class="form-control" id="caixa_id" name="caixa_id">
                                                <option value="">Escolher</option>
                                                @foreach ($caixas as $item)
                                                <option value="{{ $item->code }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3" id="form_bancos" style="display: none">
                                        <label for="banco_id" class="form-label text-right">Contas Bancárias</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select class="form-control" id="banco_id" name="banco_id">
                                                <option value="">Escolher</option>
                                                @foreach ($bancos as $item)
                                                <option value="{{ $item->code }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="preco_unitario" class="form-label">Preço Unitário</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" class="form-control  @error('preco_unitario') is-invalid @enderror" name="preco_unitario" id="preco_unitario" value="{{ $reserva->valor_unitario ?? old('preco_unitario') }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-3">
                                        <label for="preco_unitario" class="form-label">Valor Pago</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" class="form-control  @error('preco_unitario') is-invalid @enderror" name="preco_unitario" id="preco_unitario" value="{{ $reserva->valor_pago ?? old('preco_unitario') }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="preco_unitario" class="form-label">Valor A Pagar</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" class="form-control  @error('preco_unitario') is-invalid @enderror" name="preco_unitario" id="preco_unitario" value="{{ $reserva->valor_divida ?? old('preco_unitario') }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="total_factura" class="form-label">Total da Factura</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" class="form-control  @error('total_factura') is-invalid @enderror" name="total_factura" id="total_factura" value="{{ $reserva->valor_total ?? old('total_factura') }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="valor_entregue" class="form-label">Valor Entregue</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" class="form-control  @error('valor_entregue') is-invalid @enderror" name="valor_entregue" id="valor_entregue" value="{{ $reserva->valor_divida ?? old('valor_entregue') ?? 0 }}" placeholder="Informe da Factura">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <label for="observacao" class="form-label">Observação (Opcional)</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <textarea name="observacao" class="form-control" id="observacao" placeholder="Informe uma Observação" cols="30" rows="2">{{ $reserva->observacao ?? old('observacao') ?? "" }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar reserva'))
                                <button type="submit" class="btn btn-primary">Confirmar o Pagamento</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>

    const form_forma_pagamento = document.getElementById('form_forma_pagamento');
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');

    forma_pagamento_id.addEventListener('change', function() {
        if (this.value === 'NU') {
            form_caixas.style.display = 'block';
            form_bancos.style.display = 'none';
        } else if (this.value === 'MB') {
            form_bancos.style.display = 'block';
            form_caixas.style.display = 'none';
        } else {
            form_caixas.style.display = 'none';
            form_bancos.style.display = 'none';
        }
    });
</script>
@endsection
