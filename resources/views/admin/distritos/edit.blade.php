@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Distrito</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('distritos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Distrito</li>
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
                <form action="{{ route('distritos.update', $distrito->id) }}" method="post" class="">
                    @csrf
                    @method('put')
                    <div class="card-body row">
                        <div class="col-12">

                            <label for="" class="form-label">Província</label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nome" value="{{ $distrito->nome }}" placeholder="Informe a Província">
                            </div>
                            <p class="text-danger">
                                @error('nome')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12">
                            <label for="" class="form-label">Municípios</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select type="text" class="form-control" name="municipio_id">
                                    <option value="">Selecionar</option>
                                    @foreach ($municipios as $item)
                                    <option value="{{ $item->id }}" {{ $distrito->municipio_id ==  $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="" class="form-label">Estado</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select type="text" class="form-control" name="status">
                                    <option value="activo" {{ $distrito->status == "activo" ? 'selected' : '' }}>Activo</option>
                                    <option value="desactivo" {{ $distrito->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                                </select>
                            </div>
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
