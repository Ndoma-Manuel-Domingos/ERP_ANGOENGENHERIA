@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header"></div>
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
                <div class="col-lg-9 col-md-8 col-ls-12 col-12">
                    <div class="mb-4">
                        <div class="row">
                            {{-- <form action="{{ route('buscar-produto-venda-codigo-barra') }}" method="post" class="col-12 col-md-5" id="formulario_adicionar_produto_codigo_barra"> --}}
                            <form action="" method="post" class="col-12 col-md-5" id="formulario_adicionar_produto_codigo_barra">
                                @csrf
                                <div class="col-12 col-md-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <div class="input-group">
                                                <input type="text" name="produto_codigo_barra" autofocus id="produto_codigo_barra_original" class="form-control form-control-lg produto_codigo_barra" placeholder="Leitura de Codigo de Barra">
                                                <div class="input-group-append">
                                                    {{-- <button type="submit" form="formulario_adicionar_produto_codigo_barra" class="btn btn-lg btn-default">
                                                        <i class="fa fa-search"></i>
                                                    </button> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <form class="col-12 col-md-5">
                                <div class="col-12 col-md-12">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <div class="input-group">
                                                <input type="search" name="produto" id="produto" class="form-control form-control-lg produto" placeholder="Pesquisar qualquer produto aqui...">
                                                <div class="input-group-append">
                                                    <button type="submit" id="pesquisar_produto" class="btn btn-lg btn-default pesquisar_produto">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="col-12 col-md-2">
                                <button class="btn-lg btn-info" type="button" data-toggle="modal" data-target="#myModal">QUANTIDADES</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @if (empty($checkCaixa))
                        <div class="col-12 col-md-12">
                            <div class="card p-5 bg-dark">
                                <div class="card-body p-5 text-center">
                                    <h1 class="h3 p-5">Para efectuar operações do caixa, nomeadamente facturação,
                                        deverá
                                        abrir a caixa</h1>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-12 col-md-12">
                            <div class="card card-dark card-outline card-outline-tabs bg-dark">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active text-uppercase text-white" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Geral</a>
                                        </li>
                                        @if ($categorias)
                                        @foreach ($categorias as $categoria)
                                        <li class="nav-item">
                                            <a class="nav-link text-uppercase text-white" id="custom-tabs-four-profile-tab{{ $categoria->id }}" data-toggle="pill" href="#custom-tabs-four-profile{{ $categoria->id }}" role="tab" aria-controls="custom-tabs-four-profile{{ $categoria->id }}" aria-selected="false">{{ $categoria->categoria }}
                                            </a>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div style="height: 400px;overflow: hidden; overflow-y: scroll">
                                        <div class="tab-content" id="custom-tabs-four-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">

                                                <div class="row" id="carregar_produtos">
                                                    @foreach ($produtos as $item)
                                                    <div class="col-6 col-md-3 col-lg-2">
                                                        <a class="adicionar-carrinho" style="cursor: pointer" data-id="{{ $item->id }}" data-nome="{{ $item->nome }}" data-preco="{{ $item->preco_venda }}">
                                                            <div class="card shadow-sm bg-light">
                                                                <!-- /.card-header -->
                                                                @if ($item->estoque)
                                                                @if ($item->estoque->stock <= $item->estoque->stock_minimo)
                                                                    <div class="card-body bg-danger">
                                                                    @else
                                                                    <div class="card-body bg-info">
                                                                    @endif
                                                                    @else
                                                                    <div class="card-body bg-warning">
                                                                    @endif
                                                                    <div class="col-12 col-md-12 col-sm-12">
                                                                        <h6 class="text-uppercase text-white">
                                                                            {{ $item->nome }}</h6>
                                                                        <p class=" text-dark">
                                                                            <strong>{{ number_format($item->preco_venda, 2, ',', '.') }}
                                                                                <small>{{ $loja->moeda }}</small></strong>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            <!-- /.card-body -->
                                                            </div>
                                                        </a>
                                                        <!-- /.card -->
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <!-- /.row -->
                                            </div>

                                            @if ($categorias)
                                            @foreach ($categorias as $categoria)
                                            <div class="tab-pane fade" id="custom-tabs-four-profile{{ $categoria->id }}" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab{{ $categoria->id }}">
                                                <div class="row">
                                                    @foreach ($categoria->produtos as $produto)
                                                    @if ($produto->categoria_id == $categoria->id)
                                                    <div class="col-6 col-md-3 col-lg-2">
                                                        <a class="adicionar-carrinho" style="cursor: pointer" data-id="{{ $item->id }}" data-nome="{{ $item->nome }}" data-preco="{{ $item->preco_venda }}">
                                                            <div class="card shadow-sm bg-light">

                                                                <!-- /.card-header -->
                                                                @if ($produto->estoque)
                                                                @if ($produto->estoque->stock <= $produto->estoque->stock_minimo)
                                                                    <div class="card-body bg-danger">
                                                                        @else
                                                                        <div class="card-body bg-info">
                                                                            @endif
                                                                            @else
                                                                            <div class="card-body bg-warning">
                                                                                @endif
                                                                                <div class="col-12 col-md-12 col-sm-12">
                                                                                    <h6 class="pt-3 text-uppercase text-white">{{ $produto->nome }}
                                                                                    </h6>
                                                                                    <p class="text-dark">
                                                                                        <strong>{{ number_format($produto->preco_venda, 2, ',', '.') }}
                                                                                            <small>{{ $loja->moeda }}</small></strong>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <!-- /.card-body -->
                                                                        </div>
                                                        </a>
                                                        <!-- /.card -->
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>
                
                <!-- /.col-md-6 -->
                <div class="col-lg-3 col-md-4 col-ls-12 col-12">
                    @if (empty($checkCaixa))
                    <div class="card text-center">
                        <div class="card-header">
                            <img src="{{ asset('dist/img/user.png') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="width: 120px;height: 120px">
                            <h4 class="pt-2">Caixa Fechado</h4>
                            <p class="text-secondary">Para efectuar operações de caixa, nomeadamente, faturar, deverá
                                Abrir a Caixa</p>
                        </div>
                        <div class="card-body text-center">
                            <h6 class="text-center"><strong>Montante</strong></h6>
                            <p class="text-secondary">Introduza o montante disponível em caixa no momento da abertura
                                (pode ser zero).</p>

                            <form action="{{ route('caixa.abertura_caixa_create') }}" id="abertura_caixa_create" method="post" class="px-5">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-12 text-center">
                                        <label for="">Informe o Montante Valor</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="valor" value="0" placeholder="Informe o Montante Valor">
                                        </div>
                                        @error('valor')
                                        <span>{{ $message }}</span><br>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-12 text-center">
                                        <label for="">Escolha Aqui O Caixa</label>
                                        <div class="input-group text-left">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Kz</span>
                                            </div>
                                            <select name="caixa_id" id="" class="form-control @error('caixa_id') is-invalid @enderror">
                                                {{-- <option value="">TODOS</option> --}}
                                                @foreach ($caixas as $item)
                                                <option value="{{ $item->id }}" {{ old('caixa_id') == $item->id ? 'selected' : '' }}>{{ $item->conta }} - {{ $item->nome }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-md mt-4 d-iniline-block btn-primary"><i class="fas fa-box"></i> Abrir Caixa</button>
                            </form>

                        </div>
                    </div>
                    @else
                    <div class="card" id="carrinho-itens">
                        <div class="card-header bg-info">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <p>TOTAL</p>
                                </div>
                                <div class="col-12 col-md-8 text-right">
                                    <p class="h3" id="total-carrinho">0,00 AOA</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive mt-3" style="height: 400px;">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Qtd</th>
                                        <th class="text-right">Preço</th>
                                        <th style="width: 5px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Os itens do carrinho serão inseridos aqui via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button class="btn-lg btn-info" type="button" data-toggle="modal" id="botao_definir_preco_personalizado" data-target="#myModalPrecoPersonalizado"><i class="fas fa-plus"></i> PREÇO PERSONALIZADO</button>
                            <button class="btn-lg btn-danger" type="button" data-toggle="modal" id="botao_remover_preco_personalizado" style="display: none" onclick="removerPrecoPersonalizado()"><i class="fas fa-times"></i> PREÇO PERSONALIZADO</button>
                            <span id="precoPersonalizadoTexto" class="float-right h4"></span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- FORMULARIO FINALIZACAO DE VENDA --}}
                @if (!empty($checkCaixa))
                <div class="col-lg-12 col-md-12 col-12">
                    <form action="{{ route('finalizar-venda-create') }}" method="post" id="quickForm">
                        @csrf
                        <div class="card bg-info">
                            <div class="card-body row" style="height: auto;">

                                <div class="col-12 col-md-1">
                                    @if ($tipo_entidade_logado->empresa->inicializacao == 'Y')
                                    <a href="{{ route('logout') }}" class="btn btn-danger col-12 col-md-12 px-2 py-4 text-center" onclick="event.preventDefault();document.getElementById('formLoggout').submit();" role="button" data-slide="true" data-widget="control-sidebar" title="SAIR DAS VENDAS">
                                        <span class="h1 text-uppercase">
                                            <i class="fas fa-power-off"></i>
                                        </span><br>
                                        <span class="h6 text-white text-uppercase">
                                            Sair
                                        </span>
                                    </a>
                                    @endif

                                    @if ($tipo_entidade_logado->empresa->inicializacao == 'N')
                                    <a href="{{ route('caixa.fechamento_caixa') }}" title="SAIR DAS VENDAS" class="btn btn-danger col-12 col-md-12 px-2 py-4 text-center">
                                        <span class="h1 text-uppercase">
                                            <i class="fas fa-power-off"></i>
                                        </span><br>
                                        <span class="h6 text-white text-uppercase">
                                            Sair
                                        </span>
                                    </a>
                                    @endif
                                </div>

                                <div class="col-12 col-md-8">
                                    <div action="" class="row">
                                        <div class="col-md-12 col-12 mb-4" style="display: none;">
                                            <div class="input-group input-group-lg">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        {{ $loja->moeda }}
                                                    </span>
                                                </div>
                                                <input type="text" name="" class="form-control form-control-lg valor_total_pagar_fixo" disabled value="{{ number_format($total_pagar, 2, ',', '.') }}">
                                                <input type="hidden" name="total_pagar" id="total_pagar" class="form-control form-control-lg total_pagar" value="{{ $total_pagar }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fas fa-edit"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" id="venda_realizado" name="venda_realizado" value="CAIXA">

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="row">
                                                <div class="col-md-6 col-12 mb-4">
                                                    <input class="form-control form-control-lg" type="text" name="nome_cliente" id="nome_cliente" value="CONSUMIDOR FINAL">
                                                </div>

                                                <div class="col-md-6 col-12 mb-4">
                                                    <input class="form-control form-control-lg" type="text" name="documento_nif" id="documento_nif" value="999999999">
                                                </div>

                                                <div class="col-md-12 col-12 mb-4" style="display: none">
                                                    <div class="input-group input-group-lg">
                                                        <select name="cliente_id" id="cliente_id" class="form-control form-control-lg">
                                                            <option value="">Cliente</option>
                                                            @if ($clientes)
                                                            @foreach ($clientes as $item)
                                                            <option value="{{ $item->id }}" {{ $item->nome == 'CONSUMIDOR FINAL' ? 'selected' : '' }}>
                                                                {{ $item->nome }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-12 mb-4">
                                                    <div class="input-group input-group-lg">
                                                        <select name="pagamento" id="forma_de_pagamentos" class="form-control form-control-lg">
                                                            @foreach ($forma_pagmento as $forma)
                                                            <option value="{{ $forma->tipo }}" class="text-uppercase">
                                                                {{ $forma->titulo }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="row">
                                                <div class="col-12 col-md-12 mb-4">
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                {{ $loja->moeda }}
                                                            </span>
                                                        </div>
                                                        <input type="text" name="valor_entregue_multicaixa" id="valor_entregue_multicaixa" class="form-control py-3 valor_entregue_multicaixa" disabled height="40" value="0">
                                                        <input type="hidden" name="valor_entregue_multicaixa_input" class="valor_entregue_multicaixa_input" id="valor_entregue_multicaixa_input" value="">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-credit-card"></i></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-12 mb-4">
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                {{ $loja->moeda }}
                                                            </span>
                                                        </div>
                                                        <input type="text" name="valor_entregue" id="valor_entregue" class="form-control py-3 valor_entregue" height="40" value="{{ $total_pagar }}">
                                                        <input type="hidden" name="valor_entregue_input" class="valor_entregue_input" id="valor_entregue_input" value="">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-wallet"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="radio" style="display: none;" id="radioPrimary_super_factura_recibo" name="documento" value="FR" checked>

                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <p class="p-1 text-right">
                                                <span class="h5" id="valor_troco_apresenta">0</span>
                                                <small>{{ $loja->moeda ?? 'KZ' }}</small> <br>
                                                <span class="text-uppercase">Troco</span>
                                            </p>
                                        </div>

                                        <div class="col-12 col-md-12">
                                            <div class="card">
                                                <button type="button" class="btn btn-dark col-12 col-md-12 px-4 py-2 text-center float-right" id="finalizar-venda">
                                                    <span class="h3 text-white text-uppercase"><i class="fas fa-check"></i> Confirmar
                                                        venda </span>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Estrutura do Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Cabeçalho do modal -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Actualizar Quantidade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Corpo do modal -->
                            <div class="modal-body my-4">
                                <div class="row">
                                    <div class="col-12 col-md-12 mb-3">
                                        <label for="quantidade">Quantidade</label>
                                        <input type="text" class="form-control quantidade" value="1" oninput="validateInput(this)" name="quantidade" id="quantidade" placeholder="Digite a Quantidade">
                                    </div>

                                    <div class="col-12 col-md-6 mb-3">
                                        <!-- Inputs para os números -->
                                        <label for="input1">Comprimento</label>
                                        <input type="text" class="form-control input1" value="1" id="input1" oninput="validateInput(this)">
                                    </div>
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="input2">Altura</label>
                                        <input type="text" class="form-control input2" value="1" id="input2" oninput="validateInput(this)">
                                    </div>

                                    <input type="hidden" value="1" class="result" id="result">
                                </div>
                            </div>
                            <!-- Rodapé do modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary" onclick="calculateMultiplication()">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estrutura do Modal -->
                <div class="modal fade" id="myModalPrecoPersonalizado" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Cabeçalho do modal -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Definir Preço Personalizado</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Corpo do modal -->
                            <div class="modal-body my-4">
                                <div class="row">
                                    <div class="col-12 col-md-12 mb-3">
                                        <label for="precoPersonalizado">Definir Preço Personalizado</label>
                                        <input type="text" class="form-control precoPersonalizado" value="1" oninput="validateInput(this)" name="precoPersonalizado" id="precoPersonalizado" placeholder="Digite o Preço">
                                    </div>
                                </div>
                            </div>
                            <!-- Rodapé do modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary" onclick="salvarPrecoPersonalizado()">Confirmar</button>
                            </div>
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

@section('scripts')
<script>
    var quantidade_final = 1;
    let precoPersonalizado = null;
    
    // Função para validar o input
    function validateInput(input) {
        // Expressão regular para aceitar apenas números e pontos
        input.value = input.value.replace(/[^0-9.]/g, '');

        // Evita múltiplos pontos
        if ((input.value.match(/\./g) || []).length > 1) {
            input.value = input.value.slice(0, -1);
        }
    }

    // Função para multiplicar os valores dos inputs
    function calculateMultiplication() {
        const input1 = document.getElementById("input1").value;
        const input2 = document.getElementById("input2").value;
        const quantidade = document.getElementById("quantidade").value;

        // Converte os valores para float e realiza a multiplicação, se ambos forem válidos
        if (input1 && input2 && quantidade) {
            const num1 = parseFloat(input1);
            const num2 = parseFloat(input2);
            const quant = parseFloat(quantidade);
            const result = num1 * num2 * quant;

            // Exibe o resultado
            document.getElementById("result").value = result;

            $('#myModal').modal('hide');

        } else {
            document.getElementById("result").innerText = "Por favor, insira números válidos nos dois campos.";
        }
    }

    function salvarPrecoPersonalizado() {
        const preco = parseFloat(document.getElementById("precoPersonalizado").value);
        if (!isNaN(preco) && preco > 0) {
            precoPersonalizado = preco;  // Define o preço personalizado para o próximo produto
            document.getElementById("precoPersonalizadoTexto").textContent = `${preco.toFixed(2)}`;
            $('#myModalPrecoPersonalizado').modal('hide');
            document.getElementById("botao_definir_preco_personalizado").style.display = 'none';
            document.getElementById("botao_remover_preco_personalizado").style.display = 'inline-block';
        } else {
            alert("Por favor, insira um preço válido.");
        }
    }

    function removerPrecoPersonalizado() {
        const preco = parseFloat(document.getElementById("precoPersonalizado").value);
        if (!isNaN(preco) && preco > 0) {
            precoPersonalizado = null;  // Define o preço personalizado para o próximo produto
            document.getElementById("precoPersonalizadoTexto").textContent = "";
            document.getElementById("botao_definir_preco_personalizado").style.display = 'inline-block';
            document.getElementById("botao_remover_preco_personalizado").style.display = 'none';
        } else {
            alert("Por favor, insira um preço válido.");
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
                
        const input = document.getElementById('produto_codigo_barra_original');
        input.focus();

        input.addEventListener('keydown', function (e) {
            // Bloqueia "Enter" ou outros atalhos específicos
            if (e.key === 'Enter' || (e.ctrlKey && e.key === 'j')) {
                e.preventDefault();
                
                const produtoId = input.value.trim(); 
                let quantidade = document.getElementById("result").value;
                
                fetch('/carrinho/adicionar-codigo-barra', {
                    method: 'POST', 
                    headers: {
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }, 
                    body: JSON.stringify({
                        produto_id: input.value,
                        quantidade: quantidade,
                    })
                })
                .then(response => response.json())
                .then(data => {
                
                    if (data.success) {
                        atualizarCarrinho(data.carrinho, data.total);
                        
                        input.value = ''; // Limpa o campo de entrada
                        input.focus(); // Retorna o foco ao campo
                        
                    } else {
                        alert(data.message || 'Erro ao adicionar produto.');
                    }
                
                });
                
            }
        })
    
        // Função para atualizar o carrinho na tabela
        function atualizarCarrinho(carrinho, total) {
            let carrinhoItens = document.querySelector('#carrinho-itens tbody');
            carrinhoItens.innerHTML = '';

            // Percorre os itens do carrinho e os insere na tabela
            Object.values(carrinho).forEach(item => {
                let tr = document.createElement('tr');

                tr.innerHTML = `
                    <td><a href="#">${item.nome}</a></td>
                    <td><a href="#">${item.quantidade}</a></td>
                    <td class="text-right"><a href="#">${Number(item.valor_pagar).toFixed(2).replace('.', ',')} <small>AOA</small></a></td>
                    <td><a href="#" class="remover-item" data-id="${item.produto_id}"><i class="fas fa-close text-danger"></i> </a></td>
                `;

                carrinhoItens.appendChild(tr);
            });

            // Atualiza o valor total do carrinho
            document.querySelector('#total-carrinho').textContent = `${Number(total).toFixed(2).replace('.', ',')} AOA`;

            document.getElementById('total_pagar').value = `${Number(total).toFixed(2).replace('.', ',')}`;
            document.getElementById('valor_entregue').value = `${Number(total).toFixed(2).replace('.', ',')}`;

            // Adiciona o evento de remoção para os novos elementos
            document.querySelectorAll('.remover-item').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    let produtoId = this.getAttribute('data-id');
                    removerDoCarrinho(produtoId);
                });
            });
        }

        // Função para adicionar um produto ao carrinho
        function adicionarAoCarrinho(produtoId, nome, preco, quantidade = 1) {
            
            const precoUsado = precoPersonalizado !== null ? precoPersonalizado : preco;
        
            fetch('/carrinho/adicionar', {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
                , body: JSON.stringify({
                    produto_id: produtoId, 
                    nome: nome, 
                    preco: precoUsado, 
                    quantidade: quantidade
                })
            })
            .then(response => response.json())
            .then(data => {
                // alert(data.message);
                atualizarCarrinho(data.carrinho, data.total);
            });
        }

        // Função para remover um produto do carrinho
        function removerDoCarrinho(produtoId) {
            fetch('/carrinho/remover', {
                method: 'DELETE', 
                headers: {
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }, 
                body: JSON.stringify({
                    produto_id: produtoId
                })
            })
            .then(response => response.json())
            .then(data => {
                // alert(data.message);
                atualizarCarrinho(data.carrinho, data.total);
            });
        }

        // Evento para finalizar a venda
        document.querySelector('#finalizar-venda').addEventListener('click', function() {

            $('#quickForm').validate({
                rules: {
                    pagamento: {
                        required: true
                    , }
                    , cliente_id: {
                        required: true
                    , }
                , }
                , messages: {
                    pagamento: {
                        required: "Please enter a email address"
                    , }
                    , cliente_id: {
                        required: "Please enter a email address"
                    , }
                , }
                , errorElement: 'span'
                , errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                }
                , highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                }
                , unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            // Exemplo de dados que você quer enviar
            let pagamentoData = {
                valorEntregue: parseFloat(document.getElementById('valor_entregue').value), // Exemplo de valor total
                valorEntregueMulticaixa: parseFloat(document.getElementById('valor_entregue_multicaixa').value), // Exemplo de método de pagamento
                formaPagamento: document.getElementById('forma_de_pagamentos').value, // Exemplo de nome do cliente
                nomeCliente: document.getElementById('nome_cliente').value, // Exemplo de valor recebido
                nomeNIF: document.getElementById('documento_nif').value, // Exemplo de valor recebido
                clienteId: document.getElementById('cliente_id').value, // Exemplo de valor recebido
                venda_realizado: document.getElementById('venda_realizado').value, // Exemplo de valor recebido
                total_pagar: document.getElementById('total_pagar').value, // Exemplo de valor recebido
                valorEntregueMulticaixaInput: document.getElementById('valor_entregue_multicaixa_input').value, // Exemplo de valor recebido
                valorEntregueInput: document.getElementById('valor_entregue_input').value, // Exemplo de valor recebido
                documento: document.getElementById('radioPrimary_super_factura_recibo').value // Exemplo de troco
            };

            fetch('/carrinho/pagamento', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                    , body: JSON.stringify(pagamentoData) // Converte o objeto para JSON
                })
                .then(response => response.json())
                .then(data => {
                
                    const baseUrl = `{{ route('factura-recibo-pos-venda') }}`;
                    const facturaId = data.data.factura.id; // Este valor pode vir dinamicamente do seu sistema
                    
                    // Construir a URL completa
                    const url = `${baseUrl}?factura=${facturaId}`;
                    
                    // Redirecionar
                    window.location.href = url;

                    // Abrir uma nova janela com os dados como parâmetros na URL
                    //window.location.href = `/dashboard/factura-recibos-pos-venda?factura=${data.data.factura.id}`;
                    return

                }).catch(error => {
                    // Erro: captura e lida com o erro
                    // alert(`Erro no pagamento: ${error.message}`);
                    console.error('Detalhes do erro:', error);
                });
        });

        // Usar delegação de eventos para capturar cliques em elementos adicionados dinamicamente
        $(document).on("click", "#adicionar-carrinho-exemplo", function(e) {
            e.preventDefault();

            let produtoId = $(this).data('id');
            let nome = $(this).data('nome');
            let preco = $(this).data('preco');

            adicionarAoCarrinho(produtoId, nome, preco);

        });

        // $("#produto_codigo_barra").on("input", function(e) {
        //     e.preventDefault()
        //     var produto = $("#produto_codigo_barra").val()

        //     $.ajax({
        //         method: "GET"
        //         , url: "buscar-produto-codigo-barra"
        //         , data: {
        //             produto: produto
        //         }
        //         , beforeSend: function() {
        //             // $(".ajax_load").fadeIn(200).css("display", "flex");
        //         }
        //         , success: function(response) {
        //             $("#carregar_produtos").html("")

        //             var btn = "";

        //             if (response.produtos.estoque.stock <= response.produtos.estoque.stock_minimo) {
        //                 btn = "bg-danger";
        //             } else {
        //                 btn = "bg-info";
        //             }

        //             $('#carregar_produtos').append('<div class="col-6 col-md-3 col-lg-2">\
        //                 <a href="adicionar-produto/' + response.produtos.id + '">\
        //                     <div class="card shadow-sm bg-light">\
        //                         <div class="card-body ' + btn + '" ">\
        //                             <div class="col-12 col-md-12 col-sm-12">\
        //                                 <h6 class="pt-3 text-uppercase text-dark">' + response.produtos.nome + '</h6>\
        //                                 <p class="text-dark"><strong>' + response.produtos.preco_venda + '<small>' + response.loja.moeda + '</small></strong></p>\
        //                             </div>\
        //                         </div>\
        //                     </div>\
        //                 </a>\
        //             </div>');

        //         }
        //     })

        //     $("#produto_codigo_barra").val("");
        //     window.location.reload();
        // })    

        // Exemplo de uso: adicionar um produto ao carrinho
        document.querySelectorAll('.adicionar-carrinho').forEach(button => {
            button.addEventListener('click', function() {
                let produtoId = this.getAttribute('data-id');
                let nome = this.getAttribute('data-nome');
                let preco = this.getAttribute('data-preco');

                let quantidade = document.getElementById("result").value;

                adicionarAoCarrinho(produtoId, nome, preco, quantidade);

                document.getElementById("input1").value = 1;
                document.getElementById("input2").value = 1;
                document.getElementById("quantidade").value = 1;
                document.getElementById("result").value = 1;

            });
        });

        $('#forma_de_pagamentos').on('change', function(e) {
            e.preventDefault();

            var forma_pagamento = $('#forma_de_pagamentos').val();
            var valor_entregue_multicaixa = document.getElementById('valor_entregue_multicaixa');
            var valor_entregue = document.getElementById('valor_entregue');

            var valor_total = $('#total_pagar').val();

            if (forma_pagamento == "NU") {
                valor_entregue.disabled = false;
                valor_entregue_multicaixa.disabled = true;

                $('.valor_entregue_multicaixa').val(0);
                $('.valor_entregue').val(valor_total);
            } else if (forma_pagamento == "MB") {
                valor_entregue.disabled = true;
                valor_entregue_multicaixa.disabled = false;

                $('.valor_entregue_multicaixa').val(valor_total);
                $('.valor_entregue').val(0);

            } else if (forma_pagamento == "OU") {
                valor_entregue.disabled = false;
                valor_entregue_multicaixa.disabled = false;

                $('.valor_entregue').val(valor_total);
                $('.valor_entregue_multicaixa').val(0);
            }
        })

        $('.valor_entregue').on('input', function(e) {
            e.preventDefault();

            if ($(this).val() > 0) {
                // valor total a pagar
                var valor_total = $('#total_pagar').val();

                var total = parseInt(valor_total.replace(',', '.'));

                var valor_entregue = parseFloat($(this).val());

                var forma_pagamento = $('#forma_de_pagamentos').val();

                var troco = valor_entregue - total;

                if (forma_pagamento == "NU") {

                    var troco = valor_entregue - total;

                    var f2 = troco.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $("#valor_troco_apresenta").html("")
                    $("#valor_troco_apresenta").append(f2)

                } else if (forma_pagamento == "OU") {

                    var valor_restante = valor_entregue - total;

                    var restante = valor_restante * (-1);

                    var f2 = restante.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $('#valor_entregue_multicaixa').val(0);
                    $('#valor_entregue_multicaixa').val(f2);

                    $('#valor_entregue_multicaixa_input').val(restante);
                    $('#valor_entregue_input').val(valor_entregue);


                    if ((restante + valor_entregue) > total) {
                        var novo_troco = (restante + valor_entregue) - total;

                        var f3 = troco.toLocaleString('pt-br', {
                            minimumFractionDigits: 2
                        });

                        $("#valor_troco_apresenta").html("")
                        $("#valor_troco_apresenta").append(f3)
                    }

                }

            } else {
                console.log("false")
            }
        })

        $('.valor_entregue_multicaixa').on('input', function(e) {
            e.preventDefault();
            if ($(this).val() > 0) {
                // valor total a pagar
                var valor_total = $('#total_pagar').val();

                var total = parseInt(valor_total.replace(',', '.'));

                // var valor_entregue_outra_forma = $('#valor_entregue').val();

                var valor_entregue = parseFloat($(this).val());

                var forma_pagamento = $('#forma_de_pagamentos').val();

                if (forma_pagamento == "MB") {

                    var troco = valor_entregue - total;

                    // var f = troco.toLocaleString('pt-br',{style: 'currency', currency: 'AOA'});
                    var f2 = troco.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $("#valor_troco_apresenta").html("")
                    $("#valor_troco_apresenta").append(f2)


                } else if (forma_pagamento == "OU") {

                    var valor_restante = valor_entregue - total;

                    var restante = valor_restante * (-1);

                    var f2 = restante.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    $('#valor_entregue').val(0);
                    $('#valor_entregue').val(f2);

                    $('#valor_entregue_input').val(restante)
                    $('#valor_entregue_multicaixa_input').val(valor_entregue)

                    if ((restante + valor_entregue) > total) {

                        var novo_troco = (restante + valor_entregue) - total;

                        var f3 = troco.toLocaleString('pt-br', {
                            minimumFractionDigits: 2
                        });

                        $("#valor_troco_apresenta").html("")
                        $("#valor_troco_apresenta").append(f3)
                    }

                }
            } else {
                console.log("false")
            }
        })

        $("#produto").on("input", function(e) {
            e.preventDefault()
            var produto = $("#produto").val()

            $.ajax({
                method: "GET", 
                url: "buscar-produto", 
                data: {
                    produto: produto
                }
                , beforeSend: function() {
                    // $(".ajax_load").fadeIn(200).css("display", "flex");
                }
                , success: function(response) {
                    console.log(response)
                    $("#carregar_produtos").html("")
                    for (let index = 0; index < response.produtos.length; index++) {

                        var btn = "";

                        if (response.produtos[index].estoque.stock <= response.produtos[
                                index].estoque.stock_minimo) {
                            btn = "bg-danger";
                        } else {
                            btn = "bg-info";
                        }

                        $('#carregar_produtos').append('<div class="col-6 col-md-3 col-lg-2">\
                            <a id="adicionar-carrinho-exemplo" style="cursor: pointer;" data-id="' + response.produtos[index].id + '" data-nome="' + response.produtos[index].nome + '" data-preco="' + response.produtos[index].preco_venda + '">\
                                <div class="card shadow-sm bg-light">\
    								<div class="card-body ' + btn + '" ">\
                                        <div class="col-12 col-md-12 col-sm-12">\
                                            <h6 class="pt-3 text-uppercase text-dark">' + response.produtos[index].nome + '</h6>\
                                            <p class="text-dark"><strong>' + response.produtos[index].preco_venda + '<small>' + response.loja.moeda + '</small></strong></p>\
                                        </div>\
                                    </div>\
                                </div>\
                            </a>\
                        </div>');
                    }

                }
            })
        })
    });

            
    $(document).ready(function() {
        $('#abertura_caixa_create').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário
            
            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }, 
                success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    showMessage('Sucesso!', 'Seja bem vindo ao sistema!', 'success');

                    window.location.href = response.redirect;

                    // window.location.reload();
                }, 
                error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }, 
            });
        });
    });
    

</script>

@endsection
