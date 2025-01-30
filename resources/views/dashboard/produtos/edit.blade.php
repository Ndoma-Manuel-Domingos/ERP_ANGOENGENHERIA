@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Produto</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('produtos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Produto</li>
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
                        <form action="{{ route('produtos.update', $produto->id) }}" method="post" class="" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="card-body row">
                                <div class="col-12 col-md-4">
                                    <label for="" class="form-label">Nome <span class="text-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="nome" value="{{ $produto->nome }}" placeholder="Informe Produto">
                                    </div>
                                    <p class="text-danger">
                                        @error('nome')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="" class="form-label">Codigo de Barra</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="codigo_barra" value="{{ $produto->codigo_barra }}" placeholder="Informe Codigo Barra">
                                    </div>
                                    <p class="text-danger">
                                        @error('codigo_barra')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="" class="form-label">Referência</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="referencia" value="{{ $produto->referencia }}" placeholder="Informe Referência">
                                    </div>
                                    <p class="text-danger">
                                        @error('referencia')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">Descrição</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="descricao" value="{{ $produto->descricao }}" placeholder="Descrição">
                                    </div>
                                    <p class="text-danger">
                                        @error('descricao')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">Incluir Factura</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="incluir_factura">
                                            <option value="">Incluir Factura</option>
                                            <option value="Não" {{ $produto->incluir_factura == "Não" ? 'selected' : ''}}>Não</option>
                                            <option value="Sim" {{ $produto->incluir_factura == "Sim" ? 'selected' : ''}}>Sim</option>
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('incluir_factura')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">Imagem</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="file" class="form-control" name="imagem" placeholder="Imagem">

                                        <input type="hidden" class="form-control" name="imagem_guardada" value="{{ $produto->imagem }}" placeholder="Imagem">
                                    </div>
                                    <p class="text-danger">
                                        @error('imagem')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">Variações</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('variacoes.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select type="text" class="form-control" name="variacao_id">
                                            <option value="">-- Variações --</option>
                                            @foreach ($empresa->variacoes as $variacao)
                                            <option value="{{ $variacao->id }}" {{ $produto->variacao_id == $variacao->id ? 'selected' : '' }}>{{ $variacao->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('variacao_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">Variações</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('categorias.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select type="text" class="form-control" name="categoria_id">
                                            <option value="">-- Categoria --</option>
                                            @foreach ($empresa->categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ $produto->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->categoria }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('categoria_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="" class="form-label">Marca</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('marcas.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select type="text" class="form-control" name="marca_id">
                                            <option value="">-- Marca --</option>
                                            @foreach ($empresa->marcas as $marca)
                                            <option value="{{ $marca->id }}" {{ $produto->marca_id == $marca->id ? 'selected' : '' }}>{{ $marca->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('marca_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Tipo Produto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="tipo">
                                            <option value="">Tipo Produto</option>
                                            <option value="P" {{ $produto->tipo == 'P' ? 'selected' : '' }}>Produto</option>
                                            <option value="S" {{ $produto->tipo == 'S' ? 'selected' : '' }}>Serviço</option>
                                            <option value="O" {{ $produto->tipo == 'O' ? 'selected' : '' }}>Outro (portes, adiantamentos, etc.)</option>
                                            <option value="I" {{ $produto->tipo == 'I' ? 'selected' : '' }}>Imposto (excepto IVA e IS) ou Encargo Parafiscal</option>
                                            <option value="E" {{ $produto->tipo == 'E' ? 'selected' : '' }}>Imposto Especial de Consumo (IABA, ISP e IT)</option>
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('tipo')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Unidade</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="unidade">
                                            <option value="">Unidade</option>
                                            <option value="uni" {{ $produto->unidade == 'uni' ? 'selected' : '' }}>Uni</option>
                                            <option value="kg" {{ $produto->unidade == 'kg' ? 'selected' : '' }}>Kg</option>
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('unidade')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Imposto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="imposto" id="imposto">
                                            <option value=''>Automático</option>
                                            @foreach ($impostos as $item)
                                            <option value="{{ $item->id }}" {{ $produto->imposto_id == $item->id ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('imposto')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Motivo Isenção</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="motivo_isencao">
                                            @foreach ($motivos as $item)
                                            <option value="{{ $item->id }}" {{ $produto->motivo_id == $item->id ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('motivo_isencao')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                @endif

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Preço de Custo</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="preco_custo" name="preco_custo" value="{{ $produto->preco_custo }}" placeholder="Preço de Custo">
                                    </div>
                                    <p class="text-danger">
                                        @error('preco_custo')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
                                @section('styles')
                                <style>
                                    .btn-aplicao {
                                        border: 1px solid #1a1a1a;
                                        display: inline-block;
                                        padding: 5px 10px;
                                        position: relative;
                                        text-align: center;
                                        transition: background 600ms ease, color 600ms ease;
                                    }

                                </style>
                                @endsection

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">.</label>
                                    <div class="input-group mb-3">
                                        <input id="toggle-on" class="toggle toggle-left" name="iva_recomendar" value="false" type="radio" checked>
                                        <label for="toggle-on" class="btn-aplicao">c/IVA</label>
                                        <input id="toggle-off" class="toggle toggle-right" name="iva_recomendar" value="true" type="radio">
                                        <label for="toggle-off" class="btn-aplicao">s/IVA</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Margem</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="margem" id="margem" value="{{ $produto->margem }}" placeholder="Margem">
                                    </div>
                                    <p class="text-danger">
                                        @error('margem')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Preço de Venda</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="preco_venda" name="preco_venda" value="{{ $produto->preco_venda }}" placeholder="Preço de Venda do Produto">
                                    </div>
                                    <p class="text-danger">
                                        @error('preco_venda')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Preço</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="preco" name="preco" value="{{ $produto->preco }}" placeholder="Preço" disabled>
                                    </div>
                                    <p class="text-danger">
                                        @error('preco')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                                @endif


                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Controlar Stock</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="controlo_stock">
                                            <option value="">Controlar Stock</option>
                                            <option value="Sim" {{ $produto->controlo_stock == 'Sim' ? 'selected' : '' }}>Sim</option>
                                            <option value="Não" {{ $produto->controlo_stock == 'Não' ? 'selected' : '' }}>Não</option>
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('controlo_stock')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Tipo de Stock</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="tipo_stock">
                                            <option value="">Tipo de Stock</option>

                                            <optgroup label="Mercadorias">
                                                <option value="M" {{ $produto->tipo_stock == 'M' ? 'selected' : '' }}>Mercadorias</option>
                                            </optgroup>
                                            <optgroup label="Matérias-primas, subsidiárias e de consumo">
                                                <option value="P" {{ $produto->tipo_stock == 'P' ? 'selected' : '' }}>Matérias primas</option>
                                                <option value="P1" {{ $produto->tipo_stock == 'P1' ? 'selected' : '' }}>Matérias Subsidiárias</option>
                                                <option value="P2" {{ $produto->tipo_stock == 'P2' ? 'selected' : '' }}>Matérias primas de consumo</option>
                                            </optgroup>
                                            <optgroup label="Produtos acabados e intermédios">
                                                <option value="A" {{ $produto->tipo_stock == 'A' ? 'selected' : '' }}>Produtos acabados</option>
                                                <option value="A1" {{ $produto->tipo_stock == 'A1' ? 'selected' : '' }}>Produtos intermédios</option>
                                            </optgroup>
                                            <optgroup label="Sub-produtos, desperdícios, resíduos e refugos">
                                                <option value="S" {{ $produto->tipo_stock == 'S' ? 'selected' : '' }}>Subprodutos, desperdícios refugos</option>
                                                <option value="S1" {{ $produto->tipo_stock == 'S1' ? 'selected' : '' }}>Desperdícios refugos</option>
                                            </optgroup>
                                            <optgroup label="Produtos e trabalhos em curso">
                                                <option value="T" {{ $produto->tipo_stock == 'T' ? 'selected' : '' }}>Produtos e trabalhos em curso</option>
                                            </optgroup>

                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('tipo_stock')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Lojas</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="disponibilidade">
                                            <option value="">Escolher Lojas</option>
                                            @if ($lojas)
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id }}" {{ $produto->disponibilidade == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('disponibilidade')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <input type="hidden" name="preco_venda" id="preco_venda_guardado" value="" disabled>
                                <input type="hidden" name="preco" id="preco_guardado" value="">

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">Estado</label>
                                    <select type="text" class="form-control" name="status">
                                        <option value="activo" {{ $produto->disponibilidade == 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="desactivo" {{ $produto->disponibilidade == 'desactivo' ? 'selected' : '' }}>Desactivo</option>
                                    </select>
                                    <p class="text-danger">
                                        @error('status')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('editar produtos'))
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
    
    document.addEventListener('DOMContentLoaded', function () {
        // Seleciona todos os campos de entrada na página
        const inputs = document.querySelectorAll('input');
    
        // Itera sobre cada campo de entrada
        inputs.forEach(input => {
            // Garante que o campo esteja focado quando necessário (opcional)
            input.addEventListener('focus', function () {
                console.log(`Campo ${input.name || input.id} está focado.`);
            });
    
            // Adiciona evento de keydown para bloquear atalhos específicos
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || (e.ctrlKey && e.key === 'j')) {
                    e.preventDefault(); // Impede o comportamento padrão
                    console.log(`Ação bloqueada no campo ${input.name || input.id}.`);
                }
            });
        });
    });
    
    $(document).ready(function() {

        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');

                    window.location.reload();

                }
                , error: function(xhr) {
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

                }
            , });
        });

        $("#toggle-on").on('click', function() {
            if ($("#toggle-on").is(':checked')) {
                $("#preco").prop("disabled", true);
                $("#preco_guardado").prop("disabled", false);
                $("#preco_venda").prop("disabled", false);
                $("#preco_venda_guardado").prop("disabled", true);
            } else {
                $("#preco").prop("disabled", false);
                $("#preco_guardado").prop("disabled", true);
                $("#preco_venda").prop("disabled", true);
                $("#preco_venda_guardado").prop("disabled", false);
            }
        });

        $("#toggle-off").on('click', function() {
            if ($("#toggle-off").is(':checked')) {
                $("#preco").prop("disabled", false);
                $("#preco_guardado").prop("disabled", true);
                $("#preco_venda").prop("disabled", true);
                $("#preco_venda_guardado").prop("disabled", false);
            } else {
                $("#preco").prop("disabled", true);
                $("#preco_guardado").prop("disabled", false);
                $("#preco_venda").prop("disabled", false);
                $("#preco_venda_guardado").prop("disabled", true);
            }
        });

        function valorImposto() {
            var elem = $("#imposto").val();
            if (elem == '1') {
                return 0;
            } else if (elem == '2') {
                return 2;
            } else if (elem == '3') {
                return 5;
            } else if (elem == '4') {
                return 7;
            } else if (elem == '5') {
                return 14;
            } else {
                return 14;
            }
        }

        // mudar valor imposto
        $("#imposto").change(function(eventObject) {
            var elem = $(this).val();
            if (elem == '1') {
                calcularPreco(0);
                calcularMargem(0);
            } else if (elem == '2') {
                calcularPreco(2);
                calcularMargem(2);
            } else if (elem == '3') {
                calcularPreco(5);
                calcularMargem(5);
            } else if (elem == '4') {
                calcularPreco(7);
                calcularMargem(7);
            } else if (elem == '5') {
                calcularPreco(14);
                calcularMargem(14);
            } else {
                calcularPreco(14);
                calcularMargem(14);
            }
        });

        function calcularPreco(imposto) {
            // var tipoImposto = $("#imposto").val();
            if (imposto == 0) {
                $("#preco_venda").val($("#preco_custo").val());
                $("#preco_venda_guardado").val($("#preco_custo").val());

                $("#preco_guardado").val($("#preco_custo").val());
                $("#preco").val($("#preco_custo").val());
            } else {

                var valorDigitado = parseInt($("#preco_custo").val());
                var valor = valorDigitado + (valorDigitado * (imposto / 100));

                $("#preco_venda").val(valor);
                $("#preco_venda_guardado").val(valor);

                if ($("#margem").val() == "" || parseInt($("#margem").val()) < 0) {
                    console.log("sem valor");
                } else {
                    var precoVenda = valorDigitado + (valorDigitado * (imposto / 100));
                    var actualizarPrecoVenda = precoVenda + (precoVenda * (parseInt($("#margem").val()) / 100));
                    $("#preco_venda").val(actualizarPrecoVenda);
                    $("#preco_venda_guardado").val(actualizarPrecoVenda);
                }
            }
        }

        function calcularMargem(imposto) {
            if (imposto == 0) {
                $("#preco_venda").val($("#preco_custo").val());
                $("#preco_venda_guardado").val($("#preco_custo").val());

                $("#preco").val($("#preco_custo").val());
                $("#preco_guardado").val($("#preco_custo").val());
            } else {
                /******************/
                // recuperar preco custo
                var precoCusto = parseInt($("#preco_custo").val());
                var resultPrecoVenda = precoCusto + (precoCusto * (imposto / 100));
                /******************/
                // actualizar preco venda
                var actualizarPrecoVenda = resultPrecoVenda + (resultPrecoVenda * (parseInt($("#margem").val()) / 100));
                $("#preco_venda").val(actualizarPrecoVenda);
                $("#preco_venda_guardado").val(actualizarPrecoVenda);


                // actualizar preco do produto
                var percentagem = parseInt($("#margem").val()) / 100;
                $("#preco").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
                $("#preco_guardado").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
            }

        }

        $("#preco_custo").on('input', function() {
            calcularPreco(valorImposto());
        });

        $("#margem").on('input', function() {
            calcularMargem(valorImposto());
        })

    });
</script>
@endsection
