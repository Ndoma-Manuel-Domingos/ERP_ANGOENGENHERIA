@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Entrada de Dinheiro</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pronto-venda') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Entrada de Dinheiro</li>
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
                <!-- /.col-md-6 -->
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="card">
                        <div class="card-header bg-light p-0">
                            <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-flat d-block p-3"><i class="fas fa-arrow-left"></i> Entrada de Dinheiro</a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('caixa.entrada_dinheiro_caixa_create') }}" class="row" method="post">
                                @csrf
                                <div class="col-12 col-md-3">
                                    <label for="">Defina o Montante</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Kz</span>
                                        </div>
                                        <input type="number" class="form-control  @error('montante') is-invalid @enderror" name="montante" placeholder="Informe um montante">
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <label for="tipo_movimento_id">Tipos de Movimentos</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('tipos-creditos.create') }}"><i class="fas fa-check"></i></a></span>
                                        </div>
                                        <select name="tipo_movimento_id" id="tipo_movimento_id" class="select2 form-control @error('tipo_movimento_id') is-invalid @enderror">
                                            <option value="">Opções</option>
                                            <option value="C">Crédito</option>
                                            <option value="D">Debíto</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3" id="form_tipos_proveitos">
                                    <label for="tipo_proveito_id">Tipos de Proveitos</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('tipos-creditos.create') }}"><i class="fas fa-check"></i></a></span>
                                        </div>
                                        <select name="tipo_proveito_id[]" id="tipo_proveito_id" multiple class="select2 form-control @error('tipo_proveito_id') is-invalid @enderror">
                                            @foreach ($proveitos as $item)
                                            <option value="{{ $item->id }}">{{ $item->numero }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                              
                                <div class="col-12 col-md-3" id="form_tipos_creditos">
                                    <label for="tipo_credito_id">Tipos de Créditos</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('tipos-creditos.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select name="tipo_credito_id" id="tipo_credito_id" class="select2 form-control @error('tipo_credito_id') is-invalid @enderror">
                                            @foreach ($tipos_creditos as $item)
                                            <option value="{{ $item->id }}" {{ old('tipo_credito_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3" id="form_contrapartidas">
                                    <label for="contrapartida_id">Contrapartidas</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('contrapartidas.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select name="contrapartida_id" id="contrapartida_id" class="select2 form-control @error('contrapartida_id') is-invalid @enderror">
                                            @foreach ($contrapartias as $item)
                                            <option value="{{ $item->subconta->id ?? "" }}" {{ old('contrapartida_id') == ($item->subconta->id ?? "") ? 'selected' : '' }}>{{ $item->subconta->numero?? "" }} - {{ $item->subconta->nome??"" }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="marcar_como">Marcar como paga ou Recebido :</label>
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
                                
                                <div class="col-12 col-md-3" id="form_forma_pagamento" style="display: none">
                                    <div class="form-group mb-3">
                                        <label for="forma_pagamento_id">Forma de Pagamento</label>
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

                                <div class="col-12 col-md-3" id="form_caixas" style="display: none">
                                    <div class="form-group mb-3">
                                        <label for="caixa_id">Caixas</label>
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
                                
                                <div class="col-12 col-md-3" id="form_bancos" style="display: none">
                                    <div class="form-group mb-3">
                                        <label for="banco_id">Conta Bancária</label>
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
                                
                                {{-- <div class="col-12 col-md-3">
                                    <label for="subconta_id">Escolhe a conta do Movimento</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('subcontas.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select name="subconta_id" id="subconta_id" class="select2 form-control @error('subconta_id') is-invalid @enderror">
                                            @foreach ($subcontas as $item)
                                            <option value="{{ $item->id }}" {{ old('subconta_id') == $item->id ? 'selected' : '' }}>{{ $item->numero }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                
                                <div class="col-12 col-md-3">
                                    <label for="date_at">Data Movimento</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Kz</span>
                                        </div>
                                        <input type="date" id="date_at" class="form-control @error('montante') is-invalid @enderror" value="{{ old('date_at') ?? date("Y-m-d") }}" name="date_at" placeholder="Informe um montante">
                                    </div>
                                </div>
                                
                                
                                <div class="col-12 col-md-3" id="form_clientes">
                                    <label for="cliente_id">Clientes</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('tipos-creditos.create') }}"><i class="fas fa-check"></i></a></span>
                                        </div>
                                        <select name="cliente_id" id="cliente_id" class="select2 form-control @error('cliente_id') is-invalid @enderror">
                                            @foreach ($clientes as $item)
                                            <option value="{{ $item->id }}" {{ $item->nome == "CONSUMIDOR FINAL" ? 'selected' : "" }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3" id="form_fornecedores">
                                    <label for="fornecedor_id">Fornecedores</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                                        </div>
                                        <select name="fornecedor_id" id="fornecedor_id" class="select2 form-control @error('fornecedor_id') is-invalid @enderror">
                                            @foreach ($fornecedores as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                           
                                
                                <div class="col-12 col-md-3">
                                    <label for="operacao_id">Operação</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                                        </div>
                                        <select name="operacao_id" id="operacao_id" class="select2 form-control @error('operacao_id') is-invalid @enderror">
                                            <option value="A">Acréscimo</option>
                                            <option value="D">Diferimento</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <label for="exercicio_id">Exercícios</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('exercicios.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select name="exercicio_id" id="exercicio_id" class="select2 form-control @error('exercicio_id') is-invalid @enderror">
                                            @foreach ($exercicios as $item)
                                            <option value="{{ $item->id }}" {{ old('exercicio_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <label for="periodo_id">Períodos</label>
                                    <div class="input-group mb-3 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href="{{ route('periodos.create') }}"><i class="fas fa-plus"></i></a></span>
                                        </div>
                                        <select name="periodo_id[]" id="periodo_id" multiple class="select2 form-control @error('periodo_id') is-invalid @enderror">
                                            @foreach ($periodos as $item)
                                            <option value="{{ $item->id }}" {{ old('periodo_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <label for="">Observações (opcional)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control form-control-lg  @error('observacao') is-invalid @enderror" placeholder="Opcional" name="observacao">
                                    </div>
                                </div>

                                <div class="input-group my-3">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-info btn-flat mx-2"><i class="fas fa-check"></i> Confirmar</button>
                                        <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-info btn-flat mx-2"><i class="fas fa-close"></i> Cancelar</a>
                                    </span>
                                </div>
                                <!-- /input-group -->

                            </form>
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
    
    $("#tipo_movimento_id").change(() => {
        let id = $("#tipo_movimento_id").val();
        
        if (id === "C") {
            $("#form_tipos_creditos").css('display', 'block'); // ou simplesmente use o método 'show()'
            $("#form_contrapartidas").css('display', 'block'); // ou simplesmente use o método 'show()'
            $("#form_fornecedores").css('display', 'block'); // ou simplesmente use o método 'show()'
            $("#form_tipos_proveitos").css('display', 'none'); // ou use 'hide()'
            $("#form_clientes").css('display', 'none'); // ou use 'hide()'
        } else if (id === "D") {
            $("#form_tipos_proveitos").css('display', 'block'); // ou use 'hide()'
            $("#form_clientes").css('display', 'block'); // ou use 'hide()'
            $("#form_tipos_creditos").css('display', 'none'); // ou simplesmente use o método 'show()'
            $("#form_contrapartidas").css('display', 'none'); // ou simplesmente use o método 'show()'
            $("#form_fornecedores").css('display', 'none'); // ou simplesmente use o método 'show()'
        }
    });
    
    $("#tipo_credito_id").change(() => {
        let id = $("#tipo_credito_id").val();
        $.get('../../carregar-contrapartidas/' + id, function(data) {
            $("#contrapartida_id").html("")
            $("#contrapartida_id").html(data)
        })
    });

        
    $(document).ready(function () {
        $('form').on('submit', function (e) {
            e.preventDefault(); // Impede o envio tradicional do formulário
  
            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário
  
            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                beforeSend: function () {
                  // Você pode adicionar um loader aqui, se necessário
                  progressBeforeSend();
                },
                success: function (response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    
                    // alert(response.mensagem || 'Arquivo exportado com sucesso!');
                    showMessage('Sucesso!', 'Operação realizado com sucesso!', 'success');
                    
                    window.location.reload();
                },
                error: function (xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function (key, value) {
                            messages += `${value} *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', 'Erro ao processar o pedido. Tente novamente.', 'error');
                    }
                },
            });
        });
    });

</script>
@endsection
