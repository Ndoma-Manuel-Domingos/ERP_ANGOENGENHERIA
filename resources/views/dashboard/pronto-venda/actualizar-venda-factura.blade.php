@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header"> </div>
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
                <!-- /.col-md-6 -->
                <div class="col-lg-4 col-md-4 col-12">
                    <a type="button" href="{{ route('facturas.create') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Voltar</a>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('actualizar-venda-factura-update', $movimento->id) }}" class="row" method="post">
                                @csrf
                                @method('put')
                                <div class="col-12 col-md-12">
                                    <label for="">Quantidade</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend" onclick="decrementaValor(1); return false;">
                                            <span class="input-group-text px-5">-</span>
                                        </div>
                                        <input type="text" class="form-control" oninput="validateInput(this)" id="resultado" name="quantidade" value="{{ $movimento->quantidade }}">
                                        <input type="hidden" class="form-control" id="resultado_anterior" name="quantidade_anterior" value="{{ $movimento->quantidade }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text px-5" onclick="incrementaValor(10);return false;">+</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col12 col-md-6">
                                    <!-- Inputs para os números -->
                                    <label for="input1">Comprimento</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $dados->moeda }}</span>
                                        </div>
                                        <input type="text" class="form-control input1" name="input1" id="input1" value="1" id="input1" oninput="validateInput(this)">
                                    </div>
                                </div>
                                
                                <div class="col12 col-md-6">
                                    <label for="input2">Altura</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $dados->moeda }}</span>
                                        </div>
                                        <input type="text" class="form-control input2" name="input2" value="1" id="input2" oninput="validateInput(this)">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="">Preço Unitário</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $dados->moeda }}</span>
                                        </div>
                                        <input type="text" class="form-control" id="preco_unitario" name="preco_unitario" value="{{ $movimento->preco_unitario }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="">IVA</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $dados->moeda }}</span>
                                        </div>
                                        <select type="text" class="form-control" name="iva" id="iva">
                                            <option value=''>Automático</option>
                                            <option value="ISE" {{ $movimento->iva == "ISE" ? 'selected' : '' }}>0%</option>
                                            <option value="RED" {{ $movimento->iva == "RED" ? 'selected' : '' }}>2%</option>
                                            <option value="INT" {{ $movimento->iva == "INT" ? 'selected' : '' }}>5%</option>
                                            <option value="OUT" {{ $movimento->iva == "OUT" ? 'selected' : '' }}>7%</option>
                                            <option value="NOR" {{ $movimento->iva == "NOR" ? 'selected' : '' }}>14%</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="">Desconto Aplicado</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <input type="text" class="form-control" name="desconto_aplicado" value="{{ $movimento->desconto_aplicado }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="">.</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $dados->moeda }}</span>
                                        </div>
                                        <input type="text" class="form-control" name="desconto_aplicado_valor" value="{{ $movimento->desconto_aplicado_valor }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="">Texto Opcional</label>
                                    <div class="input-group mb-3">
                                        <textarea name="texto_opcional" placeholder="Se for necessário detalhar, utilize este campo." class="form-control" id="" rows="2">{{ $movimento->texto_opcional }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="">Número(s) de Série</label>
                                    <div class="input-group mb-3">
                                        <textarea name="numero_serie" placeholder="Se for mais do que um, utilize a virgula como separador." class="form-control" id="" rows="2">{{ $movimento->numero_serie }}</textarea>
                                    </div>
                                </div>

                                <div class="input-group my-3 px-5">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-info btn-flat">Confirmar</button>
                                    </span>
                                    <input type="text" id="totalPagar" class="form-control rounded-0" placeholder="" disabled value="{{ $movimento->valor_pagar }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ $dados->moeda ?? 'KZ' }}</span>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-8 col-12">
                    <a type="button" href="{{ route('facturas.create') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Actualizar Grupo de Preços</a>
                    <div class="card">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-right">Preço</th>
                                    <th class="text-right">Preço S/IVA</th>
                                    <th class="text-right">Preço Fornecedor</th>
                                    <th class="text-right">IVA</th>
                                    <th class="text-right">Margem de Lucro</th>
                                    <th class="text-right">Estado</th>
                                    <th class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($grupo_precos)
                                @foreach ($grupo_precos as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td class="text-right">{{ number_format($item->preco_venda, 2, ',', '.') }} <span class="text-secondary">{{ $tipo_entidade_logado->empresa->moeda }}</span></td>
                                    <td class="text-right">{{ number_format($item->preco, 2, ',', '.') }} <span class="text-secondary">{{ $tipo_entidade_logado->empresa->moeda }}</span></td>
                                    <td class="text-right">{{ number_format($item->preco_custo, 2, ',', '.')  }} <span class="text-secondary">{{ $tipo_entidade_logado->empresa->moeda }}</span></td>
                                    <td class="text-right">{{ $item->produto->taxa_imposto->valor }} %</td>
                                    <td class="text-right">{{ number_format($item->margem, 2, ',', '.')  }} <span class="text-secondary">%</span></td>
                                    <td class="text-right">{{ $item->status }}</td>

                                    <td style="width: 50px;">
                                        @if ($item->status == "desactivo")
                                        <a href="{{ route('definir_preco_factura.produtos', [$item->id, $movimento->id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-database"></i> Activar</a>
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                                @endif
                            </tbody>

                        </table>
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

@section('scripts')
<script>

    // Função para validar o input
    function validateInput(input) {
        // Expressão regular para aceitar apenas números e pontos
        input.value = input.value.replace(/[^0-9.]/g, '');
        
        // Evita múltiplos pontos
        if ((input.value.match(/\./g) || []).length > 1) {
            input.value = input.value.slice(0, -1);
        }
    }
    

    function incrementaValor(valorMaximo) {
        var value = parseInt(document.getElementById('resultado').value, 10);
        value = isNaN(value) ? 0 : value;
        if (value >= valorMaximo) {
            value = valorMaximo;
        } else {
            value++;
        }
        document.getElementById('resultado').value = value;

        // var result = parseInt(document.getElementById('preco_unitario').value) * value;
        var result = parseInt(document.getElementById('totalPagar').value) * value;

        // document.getElementById('preco_unitario').value = result;
        document.getElementById('totalPagar').value = result;

        // console.log(document.getElementById('totalPagar').value);   
    }

    function decrementaValor(valorMinimo) {
        var value = parseInt(document.getElementById('resultado').value, 10);
        value = isNaN(value) ? 1 : value;
        if (value <= valorMinimo) {
            value = 1;
        } else {
            value--;
        }

        document.getElementById('resultado').value = value;
        if (value != 0) {

            // var result = parseInt(document.getElementById('preco_unitario').value) / (value + 1);
            var result = parseInt(document.getElementById('totalPagar').value) / (value + 1);

            // document.getElementById('preco_unitario').value = result;
            document.getElementById('totalPagar').value = result;
        }

        // console.log(document.getElementById('totalPagar').value);

    }

    $(function() {
        $("#iva").on('change', function() {
            var valor = $(this).val();
            var valorNumero = 0;
            if (valor == "ISE") {
                valorNumero = 0;
            }
            if (valor == "RED") {
                valorNumero = 2;
            }
            if (valor == "INT") {
                valorNumero = 5;
            }
            if (valor == "OUT") {
                valorNumero = 7;
            }
            if (valor == "NOR") {
                valorNumero = 14;
            }

            var valorAcalcular = $("#preco_unitario").val();

            var novoValor = parseInt(valorAcalcular) + (valorAcalcular * (valorNumero / 100));

            console.log(novoValor);
        });
    });

</script>
@endsection
