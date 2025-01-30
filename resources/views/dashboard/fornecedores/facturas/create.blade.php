@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Criar Factura de Compra - Encomenda - {{ $encomenda->factura }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('fornecedores-facturas-encomendas.index') }}">Voltar</a></li>
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
          </div>

        
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('fornecedores-facturas-encomendas.store') }}" method="post" class="">
                            @csrf

                            <div class="card-body row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="factura" class="col-form-label text-right">Nº Factura:</label>
                                        <input type="text" class="form-control" id="factura" name="factura" value="" placeholder="Número da Factura:">
                                        <p class="text-danger col-sm-3">
                                            @error('factura')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="valor_total_factura_original" class="col-form-label text-right">Valor Total da Factura:</label>
                                        <input type="text" disabled class="form-control" id="valor_total_factura" name="valor_total_factura" value="{{ number_format($encomenda->total ?? 0, 2, ',', '.') }}" placeholder="Valor Total da factura">
                                        <input type="hidden" class="form-control" id="valor_total_factura_original" name="valor_total_factura_original" value="{{ $encomenda->total }}">
                                        <p class="text-danger col-sm-3">
                                            @error('valor_pagar')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="valor_a_pagar" class="col-form-label text-right">Valor a Pagar:</label>
                                        <input type="text" class="form-control" id="valor_a_pagar" name="valor_a_pagar" value="{{ old('total_a_pagar') ?? $encomenda->total_a_pagar }}" placeholder="Valor da Factura:">
                                        <p class="text-danger col-sm-3">
                                            @error('valor_a_pagar')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="desconto" class="col-form-label text-right">Desconto %:</label>
                                        <input type="text" class="form-control" id="desconto" name="desconto" value="{{ old('total_a_pagar') ?? 0 }}" placeholder="Desconto:">
                                        <p class="text-danger col-sm-3">
                                            @error('desconto')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="data_factura" class="col-form-label text-right">Data Factura</label>
                                        <input type="date" class="form-control" id="data_factura" name="data_factura" value="{{ old('data_factura') ?? "" }}" placeholder="Data factura:">
                                        <p class="text-danger col-sm-3">
                                            @error('data_factura')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="data_vencimento" class="col-form-label text-right">Data Vencimento:</label>
                                        <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento') ?? "" }}" placeholder="Data Vencimento:">
                                        <p class="text-danger col-sm-3">
                                            @error('data_vencimento')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="observacao" class="col-form-label text-right">Observações:</label>
                                        <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') ?? ""}}" placeholder="Observações ">
                                        <p class="text-danger col-sm-3">
                                            @error('observacao')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="marcar_como" class="col-form-label text-right">Marcar como paga :</label>
                                        <select class="form-control" id="marcar_como" name="marcar_como">
                                            <option value="nao">Não</option>
                                            <option value="sim">Sim</option>
                                        </select>
                                        <p class="text-danger col-sm-3">
                                            @error('marcar_como')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4" id="form_forma_pagamento" style="display: none">
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
                                        <label for="banco_id" class="col-form-label text-right">Conta Bancária</label>
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


                                <input type="hidden" name="encomenda_id" value="{{ $encomenda->id }}">

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                <button type="reset" class="btn btn-danger">Cancelar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    
    const select = document.getElementById('marcar_como');
    const forma_pagamento_id = document.getElementById('forma_pagamento_id');
    
    const form_forma_pagamento = document.getElementById('form_forma_pagamento');
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');
    

    select.addEventListener('change', function() {
      if (this.value === 'sim') {
        form_forma_pagamento.style.display = 'block';
      } else {
        form_forma_pagamento.style.display = 'none';
      }
    });
    
    forma_pagamento_id.addEventListener('change', function() {
      if (this.value === 'NU') {
        form_caixas.style.display = 'block';
        form_bancos.style.display = 'none';
      } else if(this.value === 'MB'){
        form_bancos.style.display = 'block';
        form_caixas.style.display = 'none';
      }else {
        form_caixas.style.display = 'none';
        form_bancos.style.display = 'none';
      }
    });

</script>
@endsection
