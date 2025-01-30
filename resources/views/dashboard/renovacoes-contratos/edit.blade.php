@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">Editar Contrato</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('contratos.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Contrato</li>
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
                    <form action="{{ route('contratos.update', $contrato->id) }}" method="post" class="">
                        @csrf
                        @method('put')

                        <div class="card-body row">

                            <div class="col-12 col-md-4">
                                <label for="funcionario_id" class="form-label">Funcionários</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control @error('funcionario_id') is-invalid @enderror" name="funcionario_id" id="funcionario_id">
                                        @foreach ($funcionarios as $item)
                                        <option value="{{ $item->id }}" {{ $contrato->funcionario_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="cargo_id" class="form-label">Cargos</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control select2 @error('cargo_id') is-invalid @enderror" name="cargo_id" id="cargo_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($cargos as $item)
                                        <option value="{{ $item->id }}" {{ $contrato->cargo_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="categoria_id" class="form-label">Categorias</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control select2 @error('categoria_id') is-invalid @enderror" name="categoria_id" id="categoria_id">
                                        @foreach ($categorias as $item)
                                        <option value="{{ $item->id }}" {{ $contrato->categoria_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="dias_processamento" class="form-label">Dias Processamento</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control select2 @error('dias_processamento') is-invalid @enderror" name="dias_processamento" id="dias_processamento">
                                        <option value="">Selecione</option>
                                        <option value="dias_uteis_variaveis" {{ $contrato->dias_processamento == "dias_uteis_variaveis" ? 'selected' : '' }}>Dias Úteis Variáveis</option>
                                        <option value="dias_fixo" {{ $contrato->dias_processamento == "dias_fixo" ? 'selected' : '' }}>Dias Fixos (30)</option>
                                        <option value="dias_uteis_fixo" {{ $contrato->dias_processamento == "dias_uteis_fixo" ? 'selected' : '' }}>Dias Úteis Fixos</option>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-12 col-md-2">
                                <label for="forma_pagamento_id" class="form-label">Formas de Pagamento</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control select2 @error('forma_pagamento_id') is-invalid @enderror" name="forma_pagamento_id" id="forma_pagamento_id">
                                        @foreach ($forma_pagamentos as $item)
                                        <option value="{{ $item->id }}" {{ $contrato->forma_pagamento_id == $item->id ? 'selected' : '' }}>{{ $item->titulo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="subsidio_natal" class="form-label">Subsídio de Natal (%)</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control @error('subsidio_natal') is-invalid @enderror" name="subsidio_natal" id="subsidio_natal" value="{{ $contrato->subsidio_natal ?? old('subsidio_natal') ?? 50 }}" placeholder="Informe o Valor em percentagem">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="forma_pagamento_natal" class="form-label">Forma de Pagamento (Natal)</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control @error('forma_pagamento_natal') is-invalid @enderror" id="forma_pagamento_natal" name="forma_pagamento_natal">
                                        <option value="">Selecione</option>
                                        <option value="completa" {{ $contrato->forma_pagamento_natal == "completa" ? 'selected' : '' }}>Completa Mês Subsídio</option>
                                        <option value="partes"  {{ $contrato->forma_pagamento_natal == "partes" ? 'selected' : '' }}>Duodécimo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="mes_pagamento_natal" class="form-label">Mês Pagamento (Natal)</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control @error('mes_pagamento_natal') is-invalid @enderror" id="mes_pagamento_natal" name="mes_pagamento_natal">
                                        <option value="1" {{ $contrato->forma_pagamento_natal == "1" ? 'selected' : '' }}>Janeiro</option>
                                        <option value="2" {{ $contrato->forma_pagamento_natal == "2" ? 'selected' : '' }}>Fevereiro</option>
                                        <option value="3" {{ $contrato->forma_pagamento_natal == "3" ? 'selected' : '' }}>Março</option>
                                        <option value="4" {{ $contrato->forma_pagamento_natal == "4" ? 'selected' : '' }}>Abril</option>
                                        <option value="5" {{ $contrato->forma_pagamento_natal == "5" ? 'selected' : '' }}>Maio</option>
                                        <option value="6" {{ $contrato->forma_pagamento_natal == "6" ? 'selected' : '' }}>Junho</option>
                                        <option value="7" {{ $contrato->forma_pagamento_natal == "7" ? 'selected' : '' }}>Julho</option>
                                        <option value="8" {{ $contrato->forma_pagamento_natal == "8" ? 'selected' : '' }}>Agosto</option>
                                        <option value="9" {{ $contrato->forma_pagamento_natal == "9" ? 'selected' : '' }}>Setembro</option>
                                        <option value="10" {{ $contrato->forma_pagamento_natal == "10" ? 'selected' : '' }}>Outubro</option>
                                        <option value="11" {{ $contrato->forma_pagamento_natal == "11" ? 'selected' : '' }}>Novembro</option>
                                        <option value="12" {{ $contrato->forma_pagamento_natal == "12" ? 'selected' : '' }}>Dezembro</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-12 col-md-2">
                                <label for="subsidio_ferias" class="form-label">Subsídio de Ferias(%)</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control @error('subsidio_ferias') is-invalid @enderror" name="subsidio_ferias" id="subsidio_ferias" value="{{ old('subsidio_ferias') ?? 50 }}" placeholder="Informe o Valor em percentagem">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="forma_pagamento_ferias" class="form-label">Forma de Pagamento (Ferias)</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control @error('forma_pagamento_ferias') is-invalid @enderror" id="forma_pagamento_ferias" name="forma_pagamento_ferias">
                                        <option value="">Selecione</option>
                                        <option value="completa" {{ $contrato->forma_pagamento_ferias == "completa" ? 'selected' : '' }}>Completa Mês Subsídio</option>
                                        <option value="partes"  {{ $contrato->forma_pagamento_ferias == "partes" ? 'selected' : '' }}>Duodécimo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="mes_pagamento_ferias" class="form-label">Mês Pagamento (Ferias)</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control @error('mes_pagamento_ferias') is-invalid @enderror" id="mes_pagamento_ferias" name="mes_pagamento_ferias">
                                      <option value="1" {{ $contrato->mes_pagamento_ferias == "1" ? 'selected' : '' }}>Janeiro</option>
                                      <option value="2" {{ $contrato->mes_pagamento_ferias == "2" ? 'selected' : '' }}>Fevereiro</option>
                                      <option value="3" {{ $contrato->mes_pagamento_ferias == "3" ? 'selected' : '' }}>Março</option>
                                      <option value="4" {{ $contrato->mes_pagamento_ferias == "4" ? 'selected' : '' }}>Abril</option>
                                      <option value="5" {{ $contrato->mes_pagamento_ferias == "5" ? 'selected' : '' }}>Maio</option>
                                      <option value="6" {{ $contrato->mes_pagamento_ferias == "6" ? 'selected' : '' }}>Junho</option>
                                      <option value="7" {{ $contrato->mes_pagamento_ferias == "7" ? 'selected' : '' }}>Julho</option>
                                      <option value="8" {{ $contrato->mes_pagamento_ferias == "8" ? 'selected' : '' }}>Agosto</option>
                                      <option value="9" {{ $contrato->mes_pagamento_ferias == "9" ? 'selected' : '' }}>Setembro</option>
                                      <option value="10" {{ $contrato->mes_pagamento_ferias == "10" ? 'selected' : '' }}>Outubro</option>
                                      <option value="11" {{ $contrato->mes_pagamento_ferias == "11" ? 'selected' : '' }}>Novembro</option>
                                      <option value="12" {{ $contrato->mes_pagamento_ferias == "12" ? 'selected' : '' }}>Dezembro</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-12 col-md-2">
                                <label for="tipo_contrato_id" class="form-label">Tipos de Contratos</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control select2 @error('tipo_contrato_id') is-invalid @enderror" name="tipo_contrato_id" id="tipo_contrato_id">
                                        @foreach ($tipos_contratos as $item)
                                        <option value="{{ $item->id }}" {{ $contrato->tipo_contrato_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="data_inicio" class="form-label">Data Início</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" name="data_inicio" id="data_inicio" value="{{ $contrato->data_inicio ?? old('data_inicio') }}" placeholder="Informe o contrato">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="data_final" class="form-label">Data Final</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('data_final') is-invalid @enderror" name="data_final" id="data_final" value="{{ $contrato->data_final ?? old('data_final') }}" placeholder="Informe o contrato">
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-2">
                                <label for="data_envio_previo" class="form-label">Data Envio Previo</label>
                                <div class="input-group mb-3">
                                  <input type="date" class="form-control @error('data_envio_previo') is-invalid @enderror" name="data_envio_previo" id="data_envio_previo" value="{{ $contrato->data_envio_previo ?? old('data_envio_previo') ?? 0 }}"
                                    placeholder="Informe o contrato">
                                </div>             
                              </div>
                                              
                              <div class="col-12 col-md-2">
                                <label for="data_demissao" class="form-label">Data de Demissão</label>
                                <div class="input-group mb-3">
                                  <input type="date" class="form-control @error('data_demissao') is-invalid @enderror" name="data_demissao" id="data_demissao" value="{{ $contrato->data_demissao ?? old('data_demissao') ?? 0 }}"
                                    placeholder="Informe o contrato">
                                </div>           
                              </div>

                            <div class="col-12 col-md-2">
                                <label for="hora_entrada" class="form-label">Hora Entrada</label>
                                <div class="input-group mb-3">
                                    <input type="time" class="form-control @error('hora_entrada') is-invalid @enderror" name="hora_entrada" id="hora_entrada" value="{{ $contrato->hora_entrada ?? old('hora_entrada') }}" placeholder="Informe o contrato">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="hora_saida" class="form-label">Hora Saída</label>
                                <div class="input-group mb-3">
                                    <input type="time" class="form-control @error('hora_saida') is-invalid @enderror" name="hora_saida" id="hora_saida" value="{{ $contrato->hora_saida ?? old('hora_saida') }}" placeholder="Informe o contrato">
                                </div>
                            </div>


                            <div class="col-12 col-md-2">
                                <label for="status" class="form-label">Estado</label>
                                <div class="input-group mb-3">
                                    <select type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="activo" {{ $contrato->status == 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="desactivo" {{ $contrato->status == 'desactivo' ? 'selected' : '' }}>Desactivo</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            @if (Auth::user()->can('editar cargo'))
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


@section('scripts')
  <script>
  
    $("#cargo_id").change(()=>{
      let id = $("#cargo_id").val();
      $.get('../../carregar-categorias-cargo/'+id, function(data){
        $("#categoria_id").html("")
        $("#categoria_id").html(data)
      })
    })

</script>
@endsection