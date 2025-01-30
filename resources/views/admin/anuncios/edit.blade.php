@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Anuncio</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('anuncios-admin.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Anuncio</li>
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
                <form action="{{ route('anuncios-admin.update', $anuncio->id) }}" method="post" class="" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card-body row">
                        <div class="col-12 col-md-6">
                            <label for="titulo" class="form-label">Titulo</label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control" name="titulo" value="{{ $anuncio->titulo ?? old('titulo') }}" placeholder="Informe o titulo">
                            </div>
                            <p class="text-danger">
                                @error('titulo')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label">Estado</label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select type="text" class="form-control" name="status">
                                    <option value="activo" {{ $anuncio->status == "activo" ? 'selected': ""  }}>Activo</option>
                                    <option value="desactivo" {{ $anuncio->status == "desactivo" ? 'selected': ""  }}>Desactivo</option>
                                </select>
                            </div>
                        </div>
                        
                         
                        <div class="col-12 col-md-6">
                        
                            @if($anuncio->image1)
                                <img src="{{ asset('images/anuncios/' . $anuncio->image1) }}" alt="Imagem1 Atual" style="max-width: 200px;height: 200px; display: block;">
                            @else
                                <p>Nenhuma imagem disponível.</p>
                            @endif
                            
                            <label for="status" class="form-label">Atualizar Imagem1</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="file" name="image1" id="image1" class="form-control" accept="image/*">
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                        
                            @if($anuncio->image2)
                                <img src="{{ asset('images/anuncios/' . $anuncio->image2) }}" alt="Imagem2 Atual" style="max-width: 200px;height: 200px; display: block;">
                            @else
                                <p>Nenhuma imagem disponível.</p>
                            @endif
                        
                            <label for="status" class="form-label">Atualizar Imagem 2</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="file" name="image2" id="image2" accept="image/*" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-12">
                            <label for="descricao" class="form-label">Descrição</label>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <textarea type="text" class="form-control" name="descricao" placeholder="Informe a Descrição">{{ $anuncio->descricao ?? old('descricao') }}</textarea>
                            </div>
                            <p class="text-danger">
                                @error('descricao')
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
            let formData = new FormData(); // Cria o objeto FormData
            
            // // Adiciona os dados serializados ao FormData
            // let serializedData = form.serializeArray();
            // $.each(serializedData, function(_, field) {
            //     formData.append(field.name, field.value);
            // });
            
            // // Adiciona o arquivo manualmente ao FormData
            // let fileInput1 = $('#image1')[0].files[0];
            // let fileInput2 = $('#image2')[0].files[0];
            // if (fileInput1) {
            //     formData.append('image1', fileInput1); // Adiciona o arquivo
            // }
            // if (fileInput2) {
            //     formData.append('image2', fileInput2); // Adiciona o arquivo
            // }

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
