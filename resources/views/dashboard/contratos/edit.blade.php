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
                        <form action="{{ route('contratos.update', $contrato->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card">
                                <div class="card-body card-default">
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <label for="funcionario_id" class="form-label">Funcionários</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control @error('funcionario_id') is-invalid @enderror"
                                                    name="funcionario_id" id="funcionario_id">
                                                    @foreach ($funcionarios as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $contrato->funcionario_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-4">
                                            <label for="cargo_id" class="form-label">Cargos</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control select2 @error('cargo_id') is-invalid @enderror"
                                                    name="cargo_id" id="cargo_id">
                                                    <option value="">Selecionar</option>
                                                    @foreach ($cargos as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $contrato->cargo_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-4">
                                            <label for="categoria_id" class="form-label">Categorias</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control select2 @error('categoria_id') is-invalid @enderror"
                                                    name="categoria_id" id="categoria_id">
                                                    @foreach ($categorias as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $contrato->categoria_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="dias_processamento" class="form-label">Dias Processamento</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control select2 @error('dias_processamento') is-invalid @enderror"
                                                    name="dias_processamento" id="dias_processamento">
                                                    <option value="">Selecione</option>
                                                    <option value="dias_uteis_variaveis"
                                                        {{ $contrato->dias_processamento == 'dias_uteis_variaveis' ? 'selected' : '' }}>
                                                        Dias Úteis Variáveis</option>
                                                    <option value="dias_fixo"
                                                        {{ $contrato->dias_processamento == 'dias_fixo' ? 'selected' : '' }}>
                                                        Dias Fixos (30)</option>
                                                    <option value="dias_uteis_fixo"
                                                        {{ $contrato->dias_processamento == 'dias_uteis_fixo' ? 'selected' : '' }}>
                                                        Dias Úteis Fixos</option>
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="forma_pagamento_id" class="form-label">Formas de Pagamento</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control select2 @error('forma_pagamento_id') is-invalid @enderror"
                                                    name="forma_pagamento_id" id="forma_pagamento_id">
                                                    @foreach ($forma_pagamentos as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $contrato->forma_pagamento_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->titulo }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="subsidio_natal" class="form-label">Subsídio de Natal (%)</label>
                                            <div class="input-group mb-3">
                                                <input type="number"
                                                    class="form-control @error('subsidio_natal') is-invalid @enderror"
                                                    name="subsidio_natal" id="subsidio_natal"
                                                    value="{{ $contrato->subsidio_natal ?? (old('subsidio_natal') ?? 50) }}"
                                                    placeholder="Informe o Valor em percentagem">
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="forma_pagamento_natal" class="form-label">Forma de Pagamento
                                                (Natal)</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control @error('forma_pagamento_natal') is-invalid @enderror"
                                                    id="forma_pagamento_natal" name="forma_pagamento_natal">
                                                    <option value="">Selecione</option>
                                                    <option value="completa"
                                                        {{ $contrato->forma_pagamento_natal == 'completa' ? 'selected' : '' }}>
                                                        Completa Mês Subsídio</option>
                                                    <option value="partes"
                                                        {{ $contrato->forma_pagamento_natal == 'partes' ? 'selected' : '' }}>
                                                        Duodécimo</option>
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="mes_pagamento_natal" class="form-label">Mês Pagamento (Natal)</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control @error('mes_pagamento_natal') is-invalid @enderror"
                                                    id="mes_pagamento_natal" name="mes_pagamento_natal">
                                                    <option value="1"
                                                        {{ $contrato->forma_pagamento_natal == '1' ? 'selected' : '' }}>Janeiro
                                                    </option>
                                                    <option value="2"
                                                        {{ $contrato->forma_pagamento_natal == '2' ? 'selected' : '' }}>
                                                        Fevereiro</option>
                                                    <option value="3"
                                                        {{ $contrato->forma_pagamento_natal == '3' ? 'selected' : '' }}>Março
                                                    </option>
                                                    <option value="4"
                                                        {{ $contrato->forma_pagamento_natal == '4' ? 'selected' : '' }}>Abril
                                                    </option>
                                                    <option value="5"
                                                        {{ $contrato->forma_pagamento_natal == '5' ? 'selected' : '' }}>Maio
                                                    </option>
                                                    <option value="6"
                                                        {{ $contrato->forma_pagamento_natal == '6' ? 'selected' : '' }}>Junho
                                                    </option>
                                                    <option value="7"
                                                        {{ $contrato->forma_pagamento_natal == '7' ? 'selected' : '' }}>Julho
                                                    </option>
                                                    <option value="8"
                                                        {{ $contrato->forma_pagamento_natal == '8' ? 'selected' : '' }}>Agosto
                                                    </option>
                                                    <option value="9"
                                                        {{ $contrato->forma_pagamento_natal == '9' ? 'selected' : '' }}>
                                                        Setembro</option>
                                                    <option value="10"
                                                        {{ $contrato->forma_pagamento_natal == '10' ? 'selected' : '' }}>
                                                        Outubro</option>
                                                    <option value="11"
                                                        {{ $contrato->forma_pagamento_natal == '11' ? 'selected' : '' }}>
                                                        Novembro</option>
                                                    <option value="12"
                                                        {{ $contrato->forma_pagamento_natal == '12' ? 'selected' : '' }}>
                                                        Dezembro</option>
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="subsidio_ferias" class="form-label">Subsídio de Ferias(%)</label>
                                            <div class="input-group mb-3">
                                                <input type="number"
                                                    class="form-control @error('subsidio_ferias') is-invalid @enderror"
                                                    name="subsidio_ferias" id="subsidio_ferias"
                                                    value="{{ old('subsidio_ferias') ?? 50 }}"
                                                    placeholder="Informe o Valor em percentagem">
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="forma_pagamento_ferias" class="form-label">Forma de Pagamento
                                                (Ferias)</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control @error('forma_pagamento_ferias') is-invalid @enderror"
                                                    id="forma_pagamento_ferias" name="forma_pagamento_ferias">
                                                    <option value="">Selecione</option>
                                                    <option value="completa"
                                                        {{ $contrato->forma_pagamento_ferias == 'completa' ? 'selected' : '' }}>
                                                        Completa Mês Subsídio</option>
                                                    <option value="partes"
                                                        {{ $contrato->forma_pagamento_ferias == 'partes' ? 'selected' : '' }}>
                                                        Duodécimo</option>
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="mes_pagamento_ferias" class="form-label">Mês Pagamento
                                                (Ferias)</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control @error('mes_pagamento_ferias') is-invalid @enderror"
                                                    id="mes_pagamento_ferias" name="mes_pagamento_ferias">
                                                    <option value="1"
                                                        {{ $contrato->mes_pagamento_ferias == '1' ? 'selected' : '' }}>Janeiro
                                                    </option>
                                                    <option value="2"
                                                        {{ $contrato->mes_pagamento_ferias == '2' ? 'selected' : '' }}>
                                                        Fevereiro</option>
                                                    <option value="3"
                                                        {{ $contrato->mes_pagamento_ferias == '3' ? 'selected' : '' }}>Março
                                                    </option>
                                                    <option value="4"
                                                        {{ $contrato->mes_pagamento_ferias == '4' ? 'selected' : '' }}>Abril
                                                    </option>
                                                    <option value="5"
                                                        {{ $contrato->mes_pagamento_ferias == '5' ? 'selected' : '' }}>Maio
                                                    </option>
                                                    <option value="6"
                                                        {{ $contrato->mes_pagamento_ferias == '6' ? 'selected' : '' }}>Junho
                                                    </option>
                                                    <option value="7"
                                                        {{ $contrato->mes_pagamento_ferias == '7' ? 'selected' : '' }}>Julho
                                                    </option>
                                                    <option value="8"
                                                        {{ $contrato->mes_pagamento_ferias == '8' ? 'selected' : '' }}>Agosto
                                                    </option>
                                                    <option value="9"
                                                        {{ $contrato->mes_pagamento_ferias == '9' ? 'selected' : '' }}>Setembro
                                                    </option>
                                                    <option value="10"
                                                        {{ $contrato->mes_pagamento_ferias == '10' ? 'selected' : '' }}>Outubro
                                                    </option>
                                                    <option value="11"
                                                        {{ $contrato->mes_pagamento_ferias == '11' ? 'selected' : '' }}>
                                                        Novembro</option>
                                                    <option value="12"
                                                        {{ $contrato->mes_pagamento_ferias == '12' ? 'selected' : '' }}>
                                                        Dezembro</option>
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="tipo_contrato_id" class="form-label">Tipos de Contratos</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control select2 @error('tipo_contrato_id') is-invalid @enderror"
                                                    name="tipo_contrato_id" id="tipo_contrato_id">
                                                    @foreach ($tipos_contratos as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $contrato->tipo_contrato_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="data_inicio" class="form-label">Data Início</label>
                                            <div class="input-group mb-3">
                                                <input type="date"
                                                    class="form-control @error('data_inicio') is-invalid @enderror"
                                                    name="data_inicio" id="data_inicio"
                                                    value="{{ $contrato->data_inicio ?? old('data_inicio') }}"
                                                    placeholder="Informe o contrato">
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="data_final" class="form-label">Data Final</label>
                                            <div class="input-group mb-3">
                                                <input type="date"
                                                    class="form-control @error('data_final') is-invalid @enderror"
                                                    name="data_final" id="data_final"
                                                    value="{{ $contrato->data_final ?? old('data_final') }}"
                                                    placeholder="Informe o contrato">
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="data_envio_previo" class="form-label">Data Envio Previo</label>
                                            <div class="input-group mb-3">
                                                <input type="date"
                                                    class="form-control @error('data_envio_previo') is-invalid @enderror"
                                                    name="data_envio_previo" id="data_envio_previo"
                                                    value="{{ $contrato->data_envio_previo ?? (old('data_envio_previo') ?? 0) }}"
                                                    placeholder="Informe o contrato">
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="data_demissao" class="form-label">Data de Demissão</label>
                                            <div class="input-group mb-3">
                                                <input type="date"
                                                    class="form-control @error('data_demissao') is-invalid @enderror"
                                                    name="data_demissao" id="data_demissao"
                                                    value="{{ $contrato->data_demissao ?? (old('data_demissao') ?? 0) }}"
                                                    placeholder="Informe o contrato">
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="hora_entrada" class="form-label">Hora Entrada</label>
                                            <div class="input-group mb-3">
                                                <input type="time"
                                                    class="form-control @error('hora_entrada') is-invalid @enderror"
                                                    name="hora_entrada" id="hora_entrada"
                                                    value="{{ $contrato->hora_entrada ?? old('hora_entrada') }}"
                                                    placeholder="Informe o contrato">
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="hora_saida" class="form-label">Hora Saída</label>
                                            <div class="input-group mb-3">
                                                <input type="time"
                                                    class="form-control @error('hora_saida') is-invalid @enderror"
                                                    name="hora_saida" id="hora_saida"
                                                    value="{{ $contrato->hora_saida ?? old('hora_saida') }}"
                                                    placeholder="Informe o contrato">
                                            </div>
                                        </div>
    
                                        <div class="col-12 col-md-2">
                                            <label for="status" class="form-label">Estado</label>
                                            <div class="input-group mb-3">
                                                <select type="text"
                                                    class="form-control @error('status') is-invalid @enderror" id="status"
                                                    name="status">
                                                    <option value="activo"
                                                        {{ $contrato->status == 'activo' ? 'selected' : '' }}>Activo</option>
                                                    <option value="desactivo"
                                                        {{ $contrato->status == 'desactivo' ? 'selected' : '' }}>Desactivo
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                                
                                        <div class="col-12 col-md-4">
                                            <label for="salario_base" class="form-label">Salário Base</label>
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                    class="form-control @error('salario_base') is-invalid @enderror"
                                                    name="salario_base" id="salario_base"
                                                    value="{{ $contrato->salario_base ?? old('salario_base') }}"
                                                    placeholder="Informe o valor base da remuneração">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="bs-stepper">
    
                                        <div class="bs-stepper-header" role="tablist">
    
                                            <div class="line"></div>
    
                                            <div class="step" data-target="#paconte-subsidios">
                                                <button type="button" class="step-trigger" role="tab"
                                                    aria-controls="paconte-subsidios" id="paconte-subsidios-trigger">
                                                    <span class="bs-stepper-circle">1</span>
                                                    <span class="bs-stepper-label">Subsídios</span>
                                                </button>
                                            </div>
    
                                            <div class="line"></div>
    
                                            <div class="step" data-target="#paconte-descontos">
                                                <button type="button" class="step-trigger" role="tab"
                                                    aria-controls="paconte-descontos" id="paconte-descontos-trigger">
                                                    <span class="bs-stepper-circle">2</span>
                                                    <span class="bs-stepper-label">Descontos</span>
                                                </button>
                                            </div>
    
                                            <div class="line"></div>
    
                                        </div>
    
                                        <div class="bs-stepper-content">
                                            <div id="paconte-subsidios" class="content" role="tabpanel"
                                                aria-labelledby="paconte-subsidios-trigger">
    
                                                <div class="row">
                                                    <div class="col-12 col-md-12" id="dynamic-fields">
                                                        @foreach($contrato->subsidios_contrato as $index => $subsidio)
                                                            <div class="field-group">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="subsidio_id_1"
                                                                            class="form-label">Subsídio</label>
                                                                        <div class="input-group mb-3">
                                                                            <select
                                                                                class="form-control  @error('subsidio_id') is-invalid @enderror"
                                                                                id="subsidio_id_1" name="subsidio_id[]">
                                                                                <option value="">Selecione</option>
                                                                                @foreach ($subsidios as $item)
                                                                                    <option value="{{ $item->id }}" {{ $item->id == $subsidio->subsidio_id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="salario_subsidio_1"
                                                                            class="form-label">Salário Subsídio</label>
                                                                        <div class="input-group mb-3">
                                                                            <input type="text"
                                                                                class="form-control @error('salario_subsidio') is-invalid @enderror"
                                                                                name="salario_subsidio[]"
                                                                                id="salario_subsidio_1"
                                                                                value="{{ $subsidio->salario }}"
                                                                                placeholder="Informe o Valor da remuneração">
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="limite_isencao_1"
                                                                            class="form-label">Limite Isenção</label>
                                                                        <div class="input-group mb-3">
                                                                            <input type="text"
                                                                                class="form-control @error('limite_isencao') is-invalid @enderror"
                                                                                name="limite_isencao[]" id="limite_isencao_1"
                                                                                value="{{ $subsidio->limite_isencao }}"
                                                                                placeholder="Informe o Valor de Limite Isenção">
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="irt_1" class="form-label">IRT</label>
                                                                        <div class="input-group mb-3">
                                                                            <select
                                                                                class="form-control  @error('irt_1') is-invalid @enderror"
                                                                                id="irt_1" name="irt[]">
                                                                                <option value="N" {{ $subsidio->irt == "N" ? 'selected' : '' }}>Não Sujeito IRT</option>
                                                                                <option value="Y" {{ $subsidio->irt == "Y" ? 'selected' : '' }}>Sujeito IRT</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="inss_1" class="form-label">INSS</label>
                                                                        <div class="input-group mb-3">
                                                                            <select
                                                                                class="form-control  @error('inss_1') is-invalid @enderror"
                                                                                id="inss_1" name="inss[]">
                                                                                <option value="N" {{ $subsidio->inss == "N" ? 'selected' : '' }}>Não Sujeito INSS</option>
                                                                                <option value="Y" {{ $subsidio->inss == "Y" ? 'selected' : '' }}>Sujeito INSS</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-1">
                                                                        <label for="processamento_id_1"
                                                                            class="form-label">Tipo Proc.</label>
                                                                        <div class="input-group mb-3">
                                                                            <select
                                                                                class="form-control @error('processamento_id') is-invalid @enderror"
                                                                                id="processamento_id_1"
                                                                                name="processamento_id[]">
                                                                                <option value="">Selecione</option>
                                                                                @foreach ($processamentos as $item)
                                                                                    <option value="{{ $item->id }}" {{ $subsidio->processamento_id == $item->id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-1">
                                                                        <label class="form-label">.</label>
                                                                        <div class="input-group mb-3">
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-field"><i
                                                                                    class="fas fa-trash"></i> Remover</button>
                                                                        </div>
                                                                    </div>
        
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
    
                                                <button type="button" class="btn btn-primary my-4"
                                                    onclick="stepper.previous()">Anterior</button>
                                                <button type="button" class="btn btn-primary my-4"
                                                    onclick="stepper.next()">Proxímo</button>
    
                                                <button type="button" id="add-field-subsidio"
                                                    class="btn btn-success my-4 mx-2 float-right"><i
                                                        class="fas fa-plus"></i> Adicionar Subsídios</button>
                                            </div>
    
                                            <div id="paconte-descontos" class="content" role="tabpanel"
                                                aria-labelledby="paconte-descontos-trigger">
                                                <div class="row">
                                                    <div class="col-12 col-md-12" id="dynamic-fields-descontos">
                                                        @foreach($contrato->descontos_contrato as $index => $desconto)
                                                            <div class="field-group-desconto">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="desconto_id_1"
                                                                            class="form-label">Descontos</label>
                                                                        <div class="input-group mb-3">
                                                                            <select
                                                                                class="form-control  @error('desconto_id') is-invalid @enderror"
                                                                                id="desconto_id_1" name="desconto_id[]">
                                                                                <option value="">Selecione</option>
                                                                                @foreach ($descontos as $item)
                                                                                    <option value="{{ $item->id }}" {{ $item->id == $desconto->desconto_id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="salario_desconto_1"
                                                                            class="form-label">Salário Desconto</label>
                                                                        <div class="input-group mb-3">
                                                                            <input type="text"
                                                                                class="form-control @error('salario_desconto') is-invalid @enderror"
                                                                                name="salario_desconto[]"
                                                                                id="salario_desconto_1"
                                                                                value="{{ $desconto->salario }}"
                                                                                placeholder="Informe o Valor da remuneração">
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="tipo_valor_1" class="form-label">Tipo
                                                                            Valor</label>
                                                                        <div class="input-group mb-3">
                                                                            <select class="form-control  @error('tipo_valor_1') is-invalid @enderror" id="tipo_valor_1" name="tipo_valor[]">
                                                                                <option value="P" {{ $desconto->tipo_valor == "P" ? 'selected' : '' }}>Percetual</option>
                                                                                <option value="E" {{ $desconto->tipo_valor == "E" ? 'selected' : '' }}>Extenso</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="irt_desconto_1"
                                                                            class="form-label">IRT</label>
                                                                        <div class="input-group mb-3">
                                                                            <select
                                                                                class="form-control  @error('irt_desconto_1') is-invalid @enderror"
                                                                                id="irt_desconto_1" name="irt_desconto[]">
                                                                                <option value="N" {{ $desconto->irt == "N" ? 'selected' : '' }}>Não Sujeito IRT</option>
                                                                                <option value="Y" {{ $desconto->irt == "Y" ? 'selected' : '' }}>Sujeito IRT</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-2">
                                                                        <label for="inss_desconto_1"
                                                                            class="form-label">INSS</label>
                                                                        <div class="input-group mb-3">
                                                                            <select
                                                                                class="form-control  @error('inss_desconto_1') is-invalid @enderror"
                                                                                id="inss_desconto_1" name="inss_desconto[]">
                                                                                <option value="N" {{ $desconto->inss == "N" ? 'selected' : '' }}>Não Sujeito INSS</option>
                                                                                <option value="Y" {{ $desconto->inss == "Y" ? 'selected' : '' }}>Sujeito INSS</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-1">
                                                                        <label for="processamento_desconto_id_1"
                                                                            class="form-label">Tipo Proc.</label>
                                                                        <div class="input-group mb-3">
                                                                            <select
                                                                                class="form-control @error('processamento_desconto_id') is-invalid @enderror"
                                                                                id="processamento_desconto_id_1"
                                                                                name="processamento_desconto_id[]">
                                                                                <option value="">Selecione</option>
                                                                                @foreach ($processamentos as $item)
                                                                                    <option value="{{ $item->id }}" {{ $desconto->processamento_id == $item->id ? 'selected' : '' }}> {{ $item->nome }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
        
                                                                    <div class="col-12 col-md-1">
                                                                        <label class="form-label">.</label>
                                                                        <div class="input-group mb-3">
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-field-desconto"><i
                                                                                    class="fas fa-trash"></i> Remover</button>
                                                                        </div>
                                                                    </div>
        
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
    
                                                <button type="button" class="btn btn-primary my-4"
                                                    onclick="stepper.previous()">Anterior</button>
                                                {{-- <button type="submit" class="btn btn-primary my-4">Salvar</button> --}}
    
                                                <button type="button" id="add-field-desconto"
                                                    class="btn btn-success my-4 mx-2 float-right"><i
                                                        class="fas fa-plus"></i> Adicionar Descontos</button>
    
                                            </div>
                                        </div>
    
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card card-default">
                                <div class="card-footer">
                                    @if (Auth::user()->can('editar cargo'))
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    @endif
                                    <button type="reset" class="btn btn-danger">Cancelar</button>
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

    // $("#cargo_id").change(() => {
    //     let id = $("#cargo_id").val();
    //     $.get('../../carregar-categorias-cargo/' + id, function(data) {
    //         $("#categoria_id").html("")
    //         $("#categoria_id").html(data)
    //     })
    // })

    
  // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    $(document).ready(function() {
        let maxFields = 10; // Limite de campos dinâmicos
        let fieldCount = 1; // Contador de campos dinâmicos
        
        let maxFieldsDesconto = 10; // Limite de campos dinâmicos
        let fieldCountDesconto = 1; // Contador de campos dinâmicos

        // Função para adicionar novo campo
        $('#add-field-subsidio').click(function() {
          if (fieldCount < maxFields) {
            fieldCount++;
            let newFieldGroup = $('.field-group:first').clone();
            newFieldGroup.find('input, select').each(function() {
              let currentId = $(this).attr('id');
              let currentName = $(this).attr('name');
              let newId = currentId.replace(/_\d+$/, '_' + fieldCount);
              let newName = currentName.replace(/\[\]$/, '[]');
              $(this).attr('id', newId);
              $(this).attr('name', newName);
              $(this).val(''); // Limpar valores
            });
            newFieldGroup.appendTo('#dynamic-fields');
          } else {
            alert('Você só pode adicionar até 10 campos.');
          }
        });
        

        // Função para remover campo
        $('#dynamic-fields').on('click', '.remove-field', function() {
          if (fieldCount > 1) {
            $(this).closest('.field-group').remove();
            fieldCount--;
          } else {
            alert('Você deve ter pelo menos um campo.');
          }
        });
        
        
        // Função para adicionar novo campo
        $('#add-field-desconto').click(function() {
          if (fieldCountDesconto < maxFieldsDesconto) {
            fieldCountDesconto++;
            let newFieldGroup = $('.field-group-desconto:first').clone();
            newFieldGroup.find('input, select').each(function() {
              let currentId = $(this).attr('id');
              let currentName = $(this).attr('name');
              let newId = currentId.replace(/_\d+$/, '_' + fieldCountDesconto);
              let newName = currentName.replace(/\[\]$/, '[]');
              $(this).attr('id', newId);
              $(this).attr('name', newName);
              $(this).val(''); // Limpar valores
            });
            newFieldGroup.appendTo('#dynamic-fields-descontos');
          } else {
            alert('Você só pode adicionar até 10 campos.');
          }
        });
        
        
        // Função para remover campo
        $('#dynamic-fields-descontos').on('click', '.remove-field-desconto', function() {
          if (fieldCountDesconto > 1) {
            $(this).closest('.field-group-desconto').remove();
            fieldCountDesconto--;
          } else {
            alert('Você deve ter pelo menos um campo.');
          }
        });
    });

</script>
@endsection
