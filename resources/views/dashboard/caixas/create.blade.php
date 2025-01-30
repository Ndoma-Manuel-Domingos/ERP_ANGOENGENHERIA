@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cadastrar Caixa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('caixas.create') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Caixa</li>
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
                        <form action="{{ route('caixas.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12 mb-3">
                                        <label for="" class="form-label">Nome</label>
                                        <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="Informe o nome do caixa">
                                        <p class="text-danger">
                                            @error('nome')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Estado <span class="text-secondary">(Opcional)</span></label>
                                        <select type="text" class="form-control" name="status">
                                            <option value="fechado">Desactivo</option>
                                            <option value="aberto">Activo</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Tipo de Caixa <span class="text-secondary">(Opcional)</span></label>
                                        <select name="tipo_caixa" id="tipo_caixa" class="form-control">
                                            <option value="auto">Automático</option>
                                            <option value="pos">Normal (Venda Produtos/Serviços)</option>
                                            <option value="rest">Restaurante (Gestão de Salas/Mesas)</option>
                                            <option value="api">API (Integração Programática)</option>
                                            <option value="office">Office</option>
                                            <option value="rest_terminal">Terminal de Pedidos (Pedidos à Mesa/Cozinha)</option>
                                        </select>
                                        <p class="text-danger">
                                            @error('tipo_caixa')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Vencimento <span class="text-secondary">(Opcional)</span></label>
                                        <select name="vencimento" class="form-control" id="vencimento">
                                            <option value="">Vencimento</option>
                                            <option value="now" selected>A Pronto</option>
                                            <option value="15">A Prazo de 15 Dias</option>
                                            <option value="30">A Prazo de 30 Dias</option>
                                            <option value="60">A Prazo de 60 Dias</option>
                                            <option value="90">A Prazo de 90 Dias</option>
                                        </select>
                                        <p class="text-danger">
                                            @error('vencimento')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Documento Predefinido <span class="text-secondary">(Opcional)</span></label>
                                        <select name="documento_predefinido" class="form-control" id="documento_predefinido" aria-placeholder="Documento Predefinido">
                                            <option value="">Documento Predefinido</option>
                                            <option value="FT" selected>Factura</option>
                                            <option value="FG">Factura Global</option>
                                            <option value="FR">Factura Recibo</option>
                                            <option value="NC">Nota de Crédito</option>
                                            <option value="PP">Factura Pró-Forma</option>
                                            <option value="OT">Orçamento</option>
                                            <option value="EC">Encomenda</option>
                                            <option value="GT">Guia de Transporte</option>
                                            <option value="GR">Guia de Remessa</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Aspecto <span class="text-secondary">(Opcional)</span></label>
                                        <select name="aspecto" class="form-control" id="aspecto" aria-placeholder="Documento Predefinido">
                                            <option value="auto">Automático</option>
                                            <option value="-1">Automático - Listagem</option>
                                            <option value="107613683">Personalizado - Layout01</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Metódo de Impressão <span class="text-secondary">(Opcional)</span></label>
                                        <select name="metodo_impressao" class="form-control" id="metodo_impressao" aria-placeholder="Documento Predefinido">
                                            <option value="browser">Impressão pelo Navegador</option>
                                            <option value="desktop">Aplicação de Impressão</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Modelos <span class="text-secondary">(Opcional)</span></label>
                                        <select name="modelo" class="form-control" id="modelo" aria-placeholder="Documento Predefinido">
                                            <option value="auto">Automático</option>
                                            <option value="107611658">Documento A4</option>
                                            <option value="107611659">Recibo principal</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Impressão em Papel <span class="text-secondary">(Opcional)</span></label>
                                        <select name="impressao_papel" class="form-control" id="impressao_papel" aria-placeholder="Documento Predefinido">
                                            <option value="sim">Sim</option>
                                            <option value="nao">Não</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Modelo de Email <span class="text-secondary">(Opcional)</span></label>
                                        <select name="modelo_email" class="form-control" id="modelo_email" aria-placeholder="Documento Predefinido">
                                            <option value="auto">Automático</option>
                                            <option value="107611658">Documento A4</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Finalizar Avançado <span class="text-secondary">(Opcional)</span></label>
                                        <select name="finalizar_avancado" class="form-control" id="finalizar_avancado" aria-placeholder="Documento Predefinido">
                                            <option value="sim">Sim</option>
                                            <option value="nao">Não</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Referência Produtos <span class="text-secondary">(Opcional)</span></label>
                                        <select name="referencia_produtos" class="form-control" id="referencia_produtos" aria-placeholder="Documento Predefinido">
                                            <option value="nao">Omitir Referência</option>
                                            <option value="sim">Mostrar Referência</option>
                                        </select>

                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Preços Produtos <span class="text-secondary">(Opcional)</span></label>
                                        <select name="precos_produtos" class="form-control" id="precos_produtos" aria-placeholder="Documento Predefinido">
                                            <option value="sim">Preços com IVA</option>
                                            <option value="nao">Preços sem IVA</option>
                                        </select>

                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Listar Produtos <span class="text-secondary">(Opcional)</span></label>
                                        <select name="modo_funcionamento" class="form-control" id="modo_funcionamento" aria-placeholder="Documento Predefinido">
                                            <option value="normal">Normal</option>
                                            <option value="tests">Formação/Testes</option>
                                        </select>

                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Listar Produtos <span class="text-secondary">(Opcional)</span></label>
                                        <select name="listar_produtos" class="form-control" id="listar_produtos" aria-placeholder="Listar Produtos">
                                            <option value="all">Todos</option>
                                            <option value="without_barcode">Sem código de barras</option>
                                            <option value="none">Não (usar apenas pesquisa ou código de barras)</option>
                                        </select>

                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Grupo de Preços <span class="text-secondary">(Opcional)</span></label>
                                        <select name="grupo_precos" class="form-control" id="grupo_precos" aria-placeholder="Listar Produtos">
                                            <option value="0">-- Selecione --</option>
                                            <option value="normal" selected>Normal</option>
                                            <option value="outro">Outro</option>
                                        </select>

                                    </div>

                                    <div class="col-12 col-md-3 mb-3">
                                        <label for="" class="form-label">Numeração Pedidos de Mesa <span class="text-secondary">(Opcional)</span></label>
                                        <select name="numeracao_pedidos_mesa" class="form-control" id="numeracao_pedidos_mesa" aria-placeholder="Listar Produtos">
                                            <option value="sim">Sim</option>
                                            <option value="nao" selected>Não</option>
                                        </select>

                                    </div>

                                </div>
                                <input type="hidden" name="loja_id" value="{{ $loja_id }}">

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar caixa'))
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                @endif
                                <button type="reset" class="btn btn-danger">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.row -->
                </div>
            </div>


        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
