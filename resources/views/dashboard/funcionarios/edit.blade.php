@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Funcionário</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('funcionarios.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Funcionário</li>
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
                    <form action="{{ route('funcionarios.update', $funcionario->id) }}" method="post" class="">

                        <div class="card">
                            @csrf
                            @method('put')

                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="numero_mecanografico" class="form-label">Número Mecanográfico:</label>
                                    <input type="text" class="form-control" id="numero_mecanografico" name="numero_mecanografico" value="{{ $funcionario->numero_mecanografico ?? old('numero_mecanografico') }}" placeholder="Informe número mecanográfico">
                                    <p class="text-danger">
                                        @error('numero_mecanografico')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Nome:</label>
                                    <input type="text" class="form-control" name="nome" value="{{ $funcionario->nome }}" placeholder="Informe Nome">
                                    <p class="text-danger">
                                        @error('nome')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Nº Contribuente:</label>
                                    <input type="text" class="form-control" name="nif" value="{{ $funcionario->nif ?? old('nif') }}" placeholder="Informe NIF">
                                    <p class="text-danger">
                                        @error('nif')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <select type="text" class="form-control select2" name="categoria" id="categoria">
                                        <option value="">Selecionar</option>
                                        <option value="Pessoal" {{ $funcionario->categoria == "Pessoal" ? 'selected' : '' }}>Pessoal</option>
                                        <option value="Orgão Sociais" {{ $funcionario->categoria == "Orgão Sociais" ? 'selected' : '' }}>Orgão Social</option>
                                        <option value="Empregados" {{ $funcionario->categoria == "Empregados" ? 'selected' : '' }}>Empregados</option>
                                    </select>
                                    <p class="text-danger">
                                        @error('categoria')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Data Nascimento <span class="text-secondary">(Opcional)</span>:</label>
                                    <input type="date" class="form-control" name="data_nascimento" value="{{ $funcionario->data_nascimento ?? old('data_nascimento') }}" placeholder="Data Nascimento">
                                    <p class="text-danger">
                                        @error('data_nascimento')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Gênero <span class="text-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="genero">
                                        <option value="">Selecionar</option>
                                        <option value="Masculino" {{ $funcionario->genero == "Masculino" ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ $funcionario->genero == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                        <option value="Personalizado" {{ $funcionario->genero == "Personalizado" ? 'selected' : '' }}>Personalizado</option>
                                    </select>
                                    <p class="text-danger">
                                        @error('genero')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Estado Cívil <span class="text-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="estado_civil_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($estados_civils as $item)
                                        <option value="{{ $item->id }}" {{ $funcionario->estado_civil_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">
                                        @error('estado_civil_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>



                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Província <span class="text-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="provincia_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ $funcionario->provincia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">
                                        @error('provincia_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Município <span class="text-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="municipio_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id }}" {{ $funcionario->municipio_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">
                                        @error('municipio_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Distritos <span class="text-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="distrito_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}" {{ $funcionario->distrito_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-danger">
                                        @error('distrito_id')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">País <span class="text-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control" name="pais">
                                        @include('includes.paises')
                                    </select>
                                    <p class="text-danger">
                                        @error('pais')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Código Postal <span class="text-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="codigo_postal" value="{{ $funcionario->codigo_postal ?? old('codigo_postal') }}" placeholder="Informe codigo Postal">
                                    <p class="text-danger">
                                        @error('codigo_postal')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Localidade <span class="text-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="localidade" value="{{ $funcionario->localidade ?? old('localidade') }}" placeholder="Informe  Localidade">
                                    <p class="text-danger">
                                        @error('localidade')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Telefone <span class="text-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="telefone" value="{{ $funcionario->telefone ?? old('telefone') }}" placeholder="Informe Telefone">
                                    <p class="text-danger">
                                        @error('telefone')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Telemóvel <span class="text-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="telemovel" value="{{ $funcionario->telemovel ?? old('telemovel') }}" placeholder="Informe  Telemóvel">
                                    <p class="text-danger">
                                        @error('telemovel')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">E-mail <span class="text-secondary">(Opcional)</span>:</label>
                                    <input type="email" class="form-control" name="email" value="{{ $funcionario->email ?? old('email') }}" placeholder="Informe  E-email">
                                    <p class="text-danger">
                                        @error('email')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header">
                                <h6>Documentos</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <label for="numero_bilhete" class="form-label">Número do Bilhete de Identidade:</label>
                                        <input type="text" class="form-control" name="numero_bilhete" id="numero_bilhete" value="{{ $funcionario->numero_bilhete ?? old('numero_bilhete') }}" placeholder="Informe Número do Bilhete de Identidade">
                                        <p class="text-danger">
                                            @error('nome')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="local_emissao_bilhete" class="form-label">Local Emissão Bilhete:</label>
                                        <input type="text" class="form-control" name="local_emissao_bilhete" id="local_emissao_bilhete" value="{{ $funcionario->local_emissao_bilhete ?? old('local_emissao_bilhete') }}" placeholder="Informe local emissão bilhete">
                                        <p class="text-danger">
                                            @error('local_emissao_bilhete')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_emissao_bilhete" class="form-label">Data Emissão B.I <span class="text-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" name="data_emissao_bilhete" id="data_emissao_bilhete" value="{{ $funcionario->data_emissao_bilhete ?? old('data_emissao_bilhete') }}" placeholder="Data">
                                        <p class="text-danger">
                                            @error('data_emissao_bilhete')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="validade_bilhete" class="form-label">Data Validade B.I <span class="text-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" id="validade_bilhete" name="validade_bilhete" value="{{ $funcionario->validade_bilhete ?? old('validade_bilhete') }}" placeholder="Validade">
                                        <p class="text-danger">
                                            @error('validade_bilhete')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <label for="numero_passaporte" class="form-label">Número do Passaporte:</label>
                                        <input type="text" class="form-control" name="numero_passaporte" id="numero_passaporte" value="{{ $funcionario->numero_passaporte ?? old('numero_passaporte') }}" placeholder="Informe Número do Passaporte">
                                        <p class="text-danger">
                                            @error('nome')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="local_emissao_passaporte" class="form-label">Local Emissão Passaporte:</label>
                                        <input type="text" class="form-control" name="local_emissao_passaporte" id="local_emissao_passaporte" value="{{ $funcionario->local_emissao_passaporte ?? old('local_emissao_passaporte') }}" placeholder="Informe local emissão passaporte">
                                        <p class="text-danger">
                                            @error('local_emissao_passaporte')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="data_emissao_passaporte" class="form-label">Data Emissão Passaporte <span class="text-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" name="data_emissao_passaporte" id="data_emissao_passaporte" value="{{ $funcionario->data_emissao_passaporte ?? old('data_emissao_passaporte') }}" placeholder="Data">
                                        <p class="text-danger">
                                            @error('data_emissao_passaporte')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="validade_passaporte" class="form-label">Data Validade Passaporte <span class="text-secondary">(Opcional)</span>:</label>
                                        <input type="date" class="form-control" id="validade_passaporte" name="validade_passaporte" value="{{ $funcionario->validade_passaporte ?? old('validade_passaporte') }}" placeholder="Validade">
                                        <p class="text-danger">
                                            @error('validade_passaporte')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-footer">
                                @if (Auth::user()->can('editar funcionario'))
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
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
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
                            messages += `${value}\n *`; // Exibe os erros
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
