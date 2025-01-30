@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Caixa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('caixas.index') }}">Voltar</a></li>
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
            <div class="card">
                <form action="{{ route('tipo-pagamentos.update', $tipoPagamento->id) }}" method="post" class="">
                    @csrf
                    @method('put')
                    <div class="card-body row">
                        <div class="col-12 col-md-6">
                            <label for="" class="form-label">Nome</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control" name="titulo" value="{{ $tipoPagamento->titulo }}" placeholder="Informe o Titulo">
                            </div>
                            <p class="text-danger">
                                @error('titulo')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="" class="form-label">Estado</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select type="text" class="form-control" name="status">
                                    <option value="1" {{ $tipoPagamento->status == true ? 'selected' : '' }}> Activo</option>
                                    <option value="0" {{ $tipoPagamento->status == false ? 'selected' : '' }}>Desactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="" class="form-label">Tipo</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select name="tipo" id="tipo" class="form-control">
                                    <option value="">Informe o tipo do caixa</option>
                                    <option value="NU" {{ $tipoPagamento->tipo == "NU" ? 'selected' : '' }}>Numerário</option>
                                    <option value="CC" {{ $tipoPagamento->tipo == "CC" ? 'selected' : '' }}>Cartão de Crédito</option>
                                    <option value="CD" {{ $tipoPagamento->tipo == "CD" ? 'selected' : '' }}>Cartão de Débito</option>
                                    <option value="CO" {{ $tipoPagamento->tipo == "CO" ? 'selected' : '' }}>Cartão Oferta</option>
                                    <option value="CS" {{ $tipoPagamento->tipo == "CS" ? 'selected' : '' }}>Compensação de Saldos C/C</option>
                                    <option value="DE" {{ $tipoPagamento->tipo == "DE" ? 'selected' : '' }}>Cartão de Pontos</option>
                                    <option value="TR" {{ $tipoPagamento->tipo == "TR" ? 'selected' : '' }}>Ticket Restaurante</option>
                                    <option value="MB" {{ $tipoPagamento->tipo == "MB" ? 'selected' : '' }}>Multicaixa</option>
                                    <option value="OU" {{ $tipoPagamento->tipo == "OU" ? 'selected' : '' }}>Duplo Pagamento</option>
                                    <option value="CH" {{ $tipoPagamento->tipo == "CH" ? 'selected' : '' }}>Cheque Bancário</option>
                                    <option value="LC" {{ $tipoPagamento->tipo == "LC" ? 'selected' : '' }}>Letra Comercial</option>
                                    <option value="TB" {{ $tipoPagamento->tipo == "TB" ? 'selected' : '' }}>Transferência Bancária</option>
                                    <option value="PR" {{ $tipoPagamento->tipo == "PR" ? 'selected' : '' }}>Permuta de Bens</option>
                                    <option value="DNP" {{ $tipoPagamento->tipo == "DNP" ? 'selected' : '' }}>Pagamento em conta corrente - entre 15 e 90 dias ou numa data específica</option>
                                </select>
                            </div>
                            <p class="text-danger">
                                @error('tipo')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="" class="form-label">Pode originar troco?</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select name="troco" class="form-control" id="troco" aria-placeholder="Pode originar troco">
                                    <option value="">Selecionar</option>
                                    <option value="1" {{ $tipoPagamento->status == true ? 'selected' : '' }}>Sim</option>
                                    <option value="0" {{ $tipoPagamento->status == false ? 'selected' : '' }}>Não</option>
                                </select>
                            </div>
                            <p class="text-danger">
                                @error('troco')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-danger">Cancelar</button>
                    </div>
                </form>
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
    });

</script>
@endsection
