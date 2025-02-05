@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Abertura do caixa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pronto-venda') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Painel de venda</li>
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
                <!-- /.col-md-6 -->
                <div class="col-12 col-md-6 col-lg-6">
                    <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Abertura do caixa</a>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('caixa.abertura_caixa_create') }}" method="post" class="row">
                                @csrf
                                <div class="col-12 col-md-12 text-center">
                                    <label for="">Montante Disponível ao Abrir Caixa</label>
                                    <div class="input-group mb-3 mt-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Kz</span>
                                        </div>
                                        <input type="text" class="form-control @error('caixa_id') is-invalid @enderror" value="{{ old('valor') ?? '0' }}" name="valor" placeholder="Introduz o valor de  Abertura">
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-12 text-center">
                                    <label for="caixa_id">Escolha Aqui O Caixa</label>
                                    <div class="input-group mb-3 mt-2 text-left">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Kz</span>
                                        </div>
                                        <select name="caixa_id" id="caixa_id" class="select2 form-control @error('caixa_id') is-invalid @enderror">
                                            {{-- <option value="">TODOS</option> --}}
                                            @foreach ($caixas as $item)
                                            <option value="{{ $item->id }}" {{ old('caixa_id') == $item->id ? 'selected' : '' }}>{{ $item->conta }} - {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="input-group mt-4">
                                    <span class="input-group-append text-center">
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

                    window.location.href = response.redirect;

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
