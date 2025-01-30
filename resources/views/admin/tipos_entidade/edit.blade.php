@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Tipo Entidade</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tipos-entidade.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">perfil</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="{{ route('tipos-entidade.update', $tipo_entidade->id) }}" method="post" class="">
                    @csrf
                    @method('put')
                    <div class="card-body row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="" class="form-label">Designação</label>
                            <input type="text" class="form-control" name="tipo" value="{{ $tipo_entidade->tipo }}" placeholder="Informe a Tipo Entidade">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="" class="form-label">Sigla</label>
                            <input type="text" class="form-control" name="sigla" value="{{ $tipo_entidade->sigla ?? old('sigla') }}" placeholder="Informe Sigla">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="" class="form-label">Estado</label>
                            <select type="text" class="form-control select2" name="status">
                                <option value="">Escolher</option>
                                <option value="activo" {{ $tipo_entidade->status == 'activo' ? 'selected': '' }}>Activo</option>
                                <option value="desactivo" {{ $tipo_entidade->status == 'desactivo' ? 'selected': '' }}>Desactivo</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="" class="form-label">Descrição</label>
                            <input type="text" class="form-control" name="descricao" value="{{ $tipo_entidade->descricao }}" placeholder="Informe a Descrição Tipo Entidade">
                        </div>

                        <div class="col-12 col-md-12">
                            <h6 class="bg-light mt-4 p-2"><strong>Conceder Modulos</strong></h6>
                        </div>

                        @foreach ($modulos_entidade as $modulo)
                        <div class="col-12 col-md-12">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="modulo{{ $modulo->id }}" value="{{ $modulo->id }}" name="modulo_id[]" @if(in_array($modulo->id, $entidade_permissions)) checked @endif>
                                    <label for="modulo{{ $modulo->id }}">
                                        {{ $modulo->modulo }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach

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
