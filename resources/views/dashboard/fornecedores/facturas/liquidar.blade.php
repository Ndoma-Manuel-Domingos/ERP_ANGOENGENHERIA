@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Liquidar Factura - {{ $factura->factura }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            @if ($factura->status2 == 'nao concluido' && $factura->status == false)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>
                                          <h5><strong>{{ number_format($factura->valor_pago ?? '0', 2, ',', '.') }} {{ $loja->empresa->moeda }}</strong></h5>
                                          <p>Valor A Pagar</p>
                                        </td>
                                        <td>Nº Factura <br> <a href="{{ route('fornecedores-facturas-encomendas.show', $factura->id) }}">{{ $factura->factura ?? '--' }}</a></td>
                                        <td>
                                          Valor Factura <br>
                                          {{ number_format($factura->valor_factura ?? '0', 2, ',', '.') }} {{ $loja->empresa->moeda }}
                                        </td>
                                        <td>
                                          Data Factura <br>
                                          {{ $factura->data_factura ?? '--' }}
                                        </td>
                                        <td>
                                          Vencimento<br>
                                          {{ $factura->data_vencimento ?? '--' }}
                                        </td>
                                    </tr>
                                </thead>
                            </table>

                            <div class="row">
                                <div class="col-12 bg-light">
                                    <form action="{{ route('encomenda-liquidar-factura-compra-store') }}" method="post" class="">
                                        @csrf
                                        <div class="card-body row mt-4">
                                            <div class="col-12 col-md-4 mb-3">
                                                <div class="form-group">
                                                  <label for="numero" class="col-form-label text-right">Valor a Liquidar:</label>
                                                  <input type="text" class="form-control" id="valor_liquidar" name="valor_liquidar" value="{{ $factura->valor_pago ?? old('valor_liquidar') }}" placeholder="Valor a Liquidar:">
                                                  <p class="text-danger col-sm-3">
                                                      @error('valor_liquidar')
                                                      {{ $message }}
                                                      @enderror
                                                  </p>
                                                </div>
                                            </div>

                                            <input type="hidden" name="factura_id" value="{{ $factura->id }}">
                                            <input type="hidden" name="valot_total_pagar" value="{{ $factura->valor_pago }}">

                                            <div class="col-12 col-md-4 mb-3">
                                              <div class="form-group">
                                                <label for="numero" class="col-form-label text-right">Data de Pagamento</label>
                                                <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" value="{{ old('data_pagamento') }}" placeholder="Data factura:">
                                                <p class="text-danger col-sm-3">
                                                    @error('data_pagamento')
                                                    {{ $message }}
                                                    @enderror
                                                </p>
                                              </div>
                                            </div>


                                            <div class="col-12 col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="forma_pagamento_id" class="col-form-label text-right">Forma de Pagamento</label>
                                                    <select class="form-control" id="forma_pagamento_id" name="forma_pagamento_id">
                                                        <option value="">Escolher</option>
                                                        <option value="NU">NUMERÁRIO</option>
                                                        <option value="MB">MULTICAIXA</option>
                                                    </select>
                                                    <p class="text-danger col-sm-3">
                                                        @error('forma_pagamento_id')
                                                        {{ $message }}
                                                        @enderror
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-4" id="form_caixas" style="display: none">
                                                <div class="form-group mb-3">
                                                    <label for="caixa_id" class="col-form-label text-right">Caixas</label>
                                                    <select class="form-control" id="caixa_id" name="caixa_id">
                                                        <option value="">Escolher</option>
                                                        @foreach ($caixas as $item)
                                                        <option value="{{ $item->code }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger col-sm-3">
                                                        @error('caixa_id')
                                                        {{ $message }}
                                                        @enderror
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-4" id="form_bancos" style="display: none">
                                                <div class="form-group mb-3">
                                                    <label for="banco_id" class="col-form-label text-right">Contas Bancárias</label>
                                                    <select class="form-control" id="banco_id" name="banco_id">
                                                        <option value="">Escolher</option>
                                                        @foreach ($bancos as $item)
                                                        <option value="{{ $item->code }}">{{ $item->conta }} - {{ $item->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger col-sm-3">
                                                        @error('banco_id')
                                                        {{ $message }}
                                                        @enderror
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="col-12 col-md-4">
                                              <div class="form-group mb-3">
                                                <label for="observacao" class="col-form-label text-right">Observações:</label>
                                                <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') }}" placeholder="Observações ">
                                                <p class="text-danger col-sm-3">
                                                  @error('observacao')
                                                  {{ $message }}
                                                  @enderror
                                                </p>
                                              </div>
                                            </div>

                                        </div>

                                        <div class="card-footer text-center">
                                            <button type="submit" class="btn btn-primary btn-lg">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <p>Não existem Facturas para liquidação. </p>
                            <p>Adicione Facturas relacionadas com esta encomenda para posteriomente liquida-las.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    const forma_pagamento_id = document.getElementById('forma_pagamento_id');

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
