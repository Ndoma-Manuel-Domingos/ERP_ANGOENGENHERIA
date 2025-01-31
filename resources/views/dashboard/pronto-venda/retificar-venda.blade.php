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
                <div class="col-lg-1"></div>
                <!-- /.col-md-6 -->
                <div class="col-lg-3">
                    <a type="button" href="{{ route('facturas.edit', $id_back) }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Voltar</a>   
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('retificar-venda-update', $movimento->id) }}" class="row" method="post">
                                @csrf
                                @method('put')
                                <div class="col-12 col-md-12">
                                  <label for="">Quantidade</label>
                                  <div class="input-group mb-3">
                                    <div class="input-group-prepend" onclick="decrementaValor(1); return false;">
                                      <span class="input-group-text px-5">-</span>
                                    </div>
                                    <input type="text" class="form-control" id="resultado" name="quantidade" value="{{ $movimento->quantidade }}">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text px-5" onclick="incrementaValor(10);return false;">+</span>
                                    </div>
                                  </div>
                              </div>

                                <div class="col-12 col-md-6">
                                    <label for="">Preço Unitário</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">kz</span>
                                      </div>
                                      <input type="text" class="form-control" name="preco_unitario" value="{{ $movimento->preco_unitario }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="">IVA</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">kz</span>
                                      </div>
                                      <select type="text" class="form-control" name="iva">
                                        <option value=''>Automático</option>
                                        <option value="ISE" {{ $movimento->iva == "ISE" ? 'selected' : '' }} >0%</option>
                                        <option value="RED" {{ $movimento->iva == "RED" ? 'selected' : '' }} >2%</option>
                                        <option value="INT" {{ $movimento->iva == "INT" ? 'selected' : '' }} >5%</option>
                                        <option value="OUT" {{ $movimento->iva == "OUT" ? 'selected' : '' }} >7%</option>
                                        <option value="NOR" {{ $movimento->iva == "NOR" ? 'selected' : '' }} >14%</option>
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
                                        <span class="input-group-text">Kz</span>
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
                <div class="col-lg-7"> </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-1"></div>
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
    function incrementaValor(valorMaximo){
        var value = parseInt(document.getElementById('resultado').value,10);
        value = isNaN(value) ? 0 : value;
        if(value >= valorMaximo) {
            value = valorMaximo;
        }else{
            value++;
        }
        document.getElementById('resultado').value = value;

        // var result = parseInt(document.getElementById('preco_unitario').value) * value;
        var result = parseInt(document.getElementById('totalPagar').value) * value;

        // document.getElementById('preco_unitario').value = result;
        document.getElementById('totalPagar').value = result;

        // console.log(document.getElementById('totalPagar').value);   
    }

    function decrementaValor(valorMinimo){
        var value = parseInt(document.getElementById('resultado').value,10);
        value = isNaN(value) ? 1 : value;
        if(value <= valorMinimo) {
            value = 1;
        }else{
            value--;
        }

        document.getElementById('resultado').value = value;
        if(value != 0){

            // var result = parseInt(document.getElementById('preco_unitario').value) / (value + 1);
            var result = parseInt(document.getElementById('totalPagar').value) / (value + 1);

            // document.getElementById('preco_unitario').value = result;
            document.getElementById('totalPagar').value = result;
        }
        
        // console.log(document.getElementById('totalPagar').value);
        
    }

    $(function(){
        $("#iva").on('change', function(){
            var valor = $(this).val();
            var valorNumero = 0;
            if(valor == "ISE"){
                valorNumero = 0;
            }
            if(valor == "RED"){
                valorNumero = 2;
            }
            if(valor == "INT"){
                valorNumero = 5;
            }
            if(valor == "OUT"){
                valorNumero = 7;
            }
            if(valor == "NOR"){
                valorNumero = 14;
            }

            var valorAcalcular = $("#preco_unitario").val();

            var novoValor = parseInt(valorAcalcular) + (valorAcalcular * (valorNumero / 100));

            console.log(novoValor);
        });
    });

</script>
@endsection