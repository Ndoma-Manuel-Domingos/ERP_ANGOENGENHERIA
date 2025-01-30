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
                    <h1 class="m-0">Fazer nova Reserva</h1>
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
                    <form action="{{ route('reservas.store') }}" method="post">
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
                                            <select type="text" class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                                                @foreach ($clientes as $item)
                                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
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
                                        <select type="text" class="form-control select2 @error('quarto_id') is-invalid @enderror" id="quarto_id" name="quarto_id">
                                          <option value="">Escolhe</option>
                                          @foreach ($quartos as $item)
                                          <option value="{{ $item->id }}" {{ $requests['quarto_id'] == $item->id ? "selected" : "" }}>{{ $item->nome }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                      <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="time" class="form-control  @error('hora_entrada') is-invalid @enderror" name="hora_entrada" id="hora_entrada" value="{{ old('hora_entrada') }}" placeholder="Hora da Entrada">
                                      </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                      <label for="hora_saida" class="form-label">Hora de Saída</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="time" class="form-control  @error('hora_saida') is-invalid @enderror" name="hora_saida" id="hora_saida" value="{{ old('hora_saida') }}" placeholder="Hora da Entrada">
                                      </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_entrada" class="form-label">Data de Saída</label>
                                        <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                          </div>
                                          <input type="date" class="form-control  @error('data_entrada') is-invalid @enderror" name="data_entrada" id="data_entrada" value="{{ old('data_entrada') }}" placeholder="Informe a quarto">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_saida" class="form-label">Data de Saída</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control  @error('data_saida') is-invalid @enderror" name="data_saida" id="data_saida" value="{{ old('data_saida') }}" placeholder="Informe a quarto">
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-3">
                                      <label for="total_dias" class="form-label">Total de Dias</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('total_dias') is-invalid @enderror" name="total_dias" id="total_dias" value="{{ old('total_dias') ?? 0 }}" placeholder="Informe o total de dias">
                                      </div>
                                    </div>
                                    
                                    <input type="hidden" id="total_segundos" name="total_segundos" class="form-control" readonly>
                                    <input type="hidden" id="total_minutos" name="total_minutos" class="form-control" readonly>
                                    <input type="hidden" id="total_horas" name="total_horas" class="form-control" readonly>
                                    <input type="hidden" id="total_semanas" name="total_semanas" class="form-control" readonly>
                                    <input type="hidden" id="total_quinzenas" name="total_quinzenas" class="form-control" readonly>
                                    <input type="hidden" id="total_meses" name="total_meses" class="form-control" readonly>
                                    <input type="hidden" id="total_anos" name="total_anos" class="form-control" readonly>

                                    <div class="col-12 col-md-3">
                                      <label for="total_pessoas" class="form-label">Total de Pessoas</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('total_pessoas') is-invalid @enderror" name="total_pessoas" id="total_pessoas" value="{{ old('total_pessoas') ?? 1 }}" placeholder="Informe o total de dias">
                                      </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                      <label for="exercicio_id" class="form-label">Exercícios</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><a href="{{ route('exercicios.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select type="text" class="form-control select2 @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                                          @foreach ($exercicios as $item)
                                          <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="periodo_id" class="form-label">Períodos</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('periodos.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id">
                                                @foreach ($periodos as $item)
                                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                      <label for="marcar_como" class="form-label">Fazer O pagamento:</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select class="form-control @error('marcar_como') is-invalid @enderror" id="marcar_como" name="marcar_como">
                                          <option value="nao">Não</option>
                                          <option value="sim">Sim</option>
                                        </select>
                                      </div>
                                    </div>

                                    <div class="col-12 col-md-3" id="form_forma_pagamento" style="display: none">
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
                                        <label for="tarefario_id" class="form-label">Terifários</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><a href="{{ route('tarefarios.create') }}"><i class="fas fa-plus"></i></a></span>
                                            </div>
                                            <select type="text" class="form-control @error('tarefario_id') is-invalid @enderror" id="tarefario_id" name="tarefario_id">
                                                @foreach ($tarefarios as $item)
                                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
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
                                        <input type="number" class="form-control  @error('preco_unitario') is-invalid @enderror" name="preco_unitario" id="preco_unitario" value="{{ old('preco_unitario') ?? 0 }}" placeholder="Informe da Factura">
                                      </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                      <label for="total_factura" class="form-label">Total da Factura</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('total_factura') is-invalid @enderror" name="total_factura" id="total_factura" value="{{ old('total_factura') ?? 0 }}" placeholder="Informe da Factura">
                                      </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-3" id="form_valor_entregue" style="display: none">
                                      <label for="valor_entregue" class="form-label">Valor Entregue</label>
                                      <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('valor_entregue') is-invalid @enderror" name="valor_entregue" id="valor_entregue" value="{{ old('valor_entregue') ?? 0 }}" placeholder="Informe da Factura">
                                      </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar reserva'))
                                <button type="submit" class="btn btn-primary">Confirmar a Reserva</button>
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
    const total_factura = document.getElementById('total_factura');
    const select = document.getElementById('marcar_como');
    const forma_pagamento_id = document.getElementById('forma_pagamento_id');
    const totalDias = document.getElementById('total_dias');
    const total_pessoas = document.getElementById('total_pessoas');
    const valor_entregue = document.getElementById('valor_entregue');
    const preco_unitario = document.getElementById('preco_unitario');
        
    // Elementos para exibir os resultados
    const totalSegundos = document.getElementById('total_segundos');
    const totalMinutos = document.getElementById('total_minutos');
    const totalHoras = document.getElementById('total_horas');
    const totalSemanas = document.getElementById('total_semanas');
    const totalQuinzenas = document.getElementById('total_quinzenas');
    const totalMeses = document.getElementById('total_meses');
    const totalAnos = document.getElementById('total_anos');
    

    const form_forma_pagamento = document.getElementById('form_forma_pagamento');
    const form_caixas = document.getElementById('form_caixas');
    const form_bancos = document.getElementById('form_bancos');
    const form_valor_entregue = document.getElementById('form_valor_entregue');


    select.addEventListener('change', function() {
        if (this.value === 'sim') {
          form_forma_pagamento.style.display = 'block';
          form_valor_entregue.style.display = 'block';
        } else {
          form_forma_pagamento.style.display = 'none';
          form_valor_entregue.style.display = 'none';
        }
    });

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

    document.addEventListener('DOMContentLoaded', function() {
        const dataEntrada = document.getElementById('data_entrada');
        const dataSaida = document.getElementById('data_saida');
      
        function calcularIntervalos() {
          const entrada = new Date(dataEntrada.value);
          const saida = new Date(dataSaida.value);
  
          if (!isNaN(entrada) && !isNaN(saida)) {
            const diferencaMs = saida - entrada; // Diferença em milissegundos
  
            if (diferencaMs >= 0) {
              const segundos = diferencaMs / 1000; // Converter para segundos
              const minutos = segundos / 60; // Converter para minutos
              const horas = minutos / 60; // Converter para horas
              const dias = horas / 24; // Converter para dias
              const semanas = dias / 7; // Converter para semanas
              const quinzenas = dias / 15; // Converter para quinzenas
              const meses = dias / 30; // Aproximação para meses
              const anos = dias / 365; // Aproximação para anos

              // Atualizar os valores nos campos
              totalSegundos.value = segundos.toFixed(2);
              totalMinutos.value = minutos.toFixed(2);
              totalHoras.value = horas.toFixed(2);
              totalDias.value = dias.toFixed(2);
              totalSemanas.value = semanas.toFixed(2);
              totalQuinzenas.value = quinzenas.toFixed(2);
              totalMeses.value = meses.toFixed(2);
              totalAnos.value = anos.toFixed(2);
            } else {
              resetarValores();
            }
          } else {
            resetarValores();
          }
      }

      function resetarValores() {
        // Define todos os campos para 0
        totalSegundos.value = totalMinutos.value = totalHoras.value =
        totalDias.value = totalSemanas.value = totalQuinzenas.value =
        totalMeses.value = totalAnos.value = 0;
      }

      // Adicionar evento de alteração nos campos de data
      dataEntrada.addEventListener('change', calcularIntervalos);
      dataSaida.addEventListener('change', calcularIntervalos);
    });

    $("#quarto_id").change(() => {
      let id = $("#quarto_id").val();
      $.get('../carregar-tarefarios-quarto/' + id, function(data) {
        $("#tarefario_id").html("")
        $("#tarefario_id").html(data)
      })
    })

    $("#tarefario_id").change(() => {
      let id = $("#tarefario_id").val();
      $.get('../mais-detalhes-do-tarefarios/' + id, function(data) {
        
        if(data){
          
          preco_unitario.value = data.valor;
          
          if(data.modo_tarefario == "Por Minutos" && data.tipo_cobranca == "Por Comodo"){
            total_factura.value = data.valor * total_minutos.value;
          }
          if(data.modo_tarefario == "Por Minutos" && data.tipo_cobranca == "Por Pessoa"){
            total_factura.value = data.valor * total_minutos.value * total_pessoas.value;
          }
          if(data.modo_tarefario == "Por Dia" && data.tipo_cobranca == "Por Comodo"){
            total_factura.value = data.valor * totalDias.value;
          }
          if(data.modo_tarefario == "Por Dia" && data.tipo_cobranca == "Por Pessoa"){
            total_factura.value = data.valor * totalDias.value * total_pessoas.value;
          }
          if(data.modo_tarefario == "Por Hora" && data.tipo_cobranca == "Por Comodo"){
            total_factura.value = data.valor * total_minutos.total_horas;
          }
          if(data.modo_tarefario == "Por Hora" && data.tipo_cobranca == "Por Pessoa"){
            total_factura.value = data.valor * total_minutos.total_horas * total_pessoas.value;
          }
          if(data.modo_tarefario == "Por Semana" && data.tipo_cobranca == "Por Comodo"){
            total_factura.value = data.valor * total_minutos.total_semanas;
          }
          if(data.modo_tarefario == "Por Semana" && data.tipo_cobranca == "Por Pessoa"){
            total_factura.value = data.valor * total_minutos.total_semanas * total_pessoas.value;
          }
          if(data.modo_tarefario == "Por Quizena" && data.tipo_cobranca == "Por Comodo"){
            total_factura.value = data.valor * total_minutos.total_quinzenas;
          }
          if(data.modo_tarefario == "Por Quizena" && data.tipo_cobranca == "Por Pessoa"){
            total_factura.value = data.valor * total_minutos.total_quinzenas * total_pessoas.value;
          }
          if(data.modo_tarefario == "Por Mes" && data.tipo_cobranca == "Por Comodo"){
            total_factura.value = data.valor * total_minutos.total_meses;
          }
          if(data.modo_tarefario == "Por Mes" && data.tipo_cobranca == "Por Pessoa"){
            total_factura.value = data.valor * total_minutos.total_meses * total_pessoas.value;
          }
          if(data.modo_tarefario == "Por Ano" && data.tipo_cobranca == "Por Comodo"){
            total_factura.value = data.valor * total_minutos.total_anos;
          }
          if(data.modo_tarefario == "Por Ano" && data.tipo_cobranca == "Por Pessoa"){
            total_factura.value = data.valor * total_minutos.total_anos * total_pessoas.value;
          }
          valor_entregue.value = total_factura.value;
        }
      })
    })

</script>
@endsection
