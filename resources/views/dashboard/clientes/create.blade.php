@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cadastrar
                        @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                        Hospode
                        @else
                        Cliente
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">
                            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                            Hospode
                            @else
                            Cliente
                            @endif
                        </li>
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
                        <form action="{{ route('clientes.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Nome:</label>
                                    <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="Informe Nome">
                                    <p class="text-danger">
                                        @error('nome')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">NIF/BI:</label>
                                    <input type="text" class="form-control" name="nif" value="{{ old('nif') }}" placeholder="Informe NIF">
                                    <p class="text-danger">
                                        @error('nif')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="tipo_cliente" class="form-label">Tipo Cliente <span class="text-secondary">(Opcional)</span>:</label>
                                    <select type="text" class="form-control select2" name="tipo_cliente">
                                        <option value="">Selecionar</option>
                                        <option value="C" {{ old('tipo_cliente') == "C" ? 'selected' : '' }} selected>Correntes</option>
                                        <option value="TR" {{ old('tipo_cliente') == "TR" ? 'selected' : '' }}>Títulos a Receber</option>
                                        <option value="TD" {{ old('tipo_cliente') == "TD" ? 'selected' : '' }}>Títulos Descontados</option>
                                        <option value="CD" {{ old('tipo_cliente') == "CD" ? 'selected' : '' }}>Cobrança Duvidosa</option>
                                        <option value="SC" {{ old('tipo_cliente') == "SC" ? 'selected' : '' }}>Saldos Credores</option>
                                    </select>
                                    <p class="text-danger">
                                        @error('tipo_cliente')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                {{-- <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Nome Pai <span class="text-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="nome_do_pai" value="{{ old('nome_do_pai') }}" placeholder="Informe Nome do Pai">
                                <p class="text-danger">
                                    @error('nome_do_pai')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Nome Mãe <span class="text-secondary">(Opcional)</span>:</label>
                                <input type="text" class="form-control" name="nome_da_mae" value="{{ old('nome_da_mae') }}" placeholder="Informe Nome mãe">
                                <p class="text-danger">
                                    @error('nome_da_mae')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div> --}}

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Data Nascimento <span class="text-secondary">(Opcional)</span>:</label>
                                <input type="date" class="form-control" name="data_nascimento" value="{{ old('data_nascimento') }}" placeholder="Data Nascimento">
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
                                    <option value="Masculino" {{ old('genero') == "Masculino" ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ old('genero') == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                    <option value="Personalizado" {{ old('genero') == "Personalizado" ? 'selected' : '' }}>Personalizado</option>
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
                                    <option value="{{ $item->id }}" {{ old('estado_civil_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                <p class="text-danger">
                                    @error('estado_civil_id')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>


                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Seguradora <span class="text-secondary">(Opcional)</span>:</label>
                                <select type="text" class="form-control select2" name="seguradora_id">
                                    <option value="">Selecionar</option>
                                    @foreach ($seguradores as $item)
                                    <option value="{{ $item->id }}" {{ old('seguradora_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                <p class="text-danger">
                                    @error('seguradora_id')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Província <span class="text-secondary">(Opcional)</span>:</label>
                                <select type="text" class="form-control select2" name="provincia_id">
                                    <option value="">Selecionar</option>
                                    @foreach ($provincias as $item)
                                    <option value="{{ $item->id }}" {{ old('provincia_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
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
                                    <option value="{{ $item->id }}" {{ old('municipio_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
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
                                    <option value="{{ $item->id }}" {{ old('distrito_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
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
                                <input type="text" class="form-control" name="codigo_postal" value="{{ old('codigo_postal') }}" placeholder="Informe codigo Postal">
                                <p class="text-danger">
                                    @error('codigo_postal')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Localidade <span class="text-secondary">(Opcional)</span>:</label>
                                <input type="text" class="form-control" name="localidade" value="{{ old('localidade') }}" placeholder="Informe  Localidade">
                                <p class="text-danger">
                                    @error('localidade')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Telefone <span class="text-secondary">(Opcional)</span>:</label>
                                <input type="text" class="form-control" name="telefone" value="{{ old('telefone') }}" placeholder="Informe Telefone">
                                <p class="text-danger">
                                    @error('telefone')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Telemóvel <span class="text-secondary">(Opcional)</span>:</label>
                                <input type="text" class="form-control" name="telemovel" value="{{ old('telemovel') }}" placeholder="Informe  Telemóvel">
                                <p class="text-danger">
                                    @error('telemovel')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">E-mail <span class="text-secondary">(Opcional)</span>:</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Informe  E-email">
                                <p class="text-danger">
                                    @error('email')
                                    {{ $message }}
                                    @enderror
                                </p>
                            </div>

                            {{-- <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Website <span class="text-secondary">(Opcional)</span>:</label>
                                    <input type="text" class="form-control" name="website" value="{{ old('website') }}" placeholder="Informe WebSite">
                            <p class="text-danger">
                                @error('website')
                                {{ $message }}
                                @enderror
                            </p>
                    </div> --}}


                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Observação <span class="text-secondary">(Opcional)</span>:</label>
                        <input type="text" class="form-control" name="observacao" value="{{ old('observacao') }}" placeholder="Informe Observação">
                        <p class="text-danger">
                            @error('observacao')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                </div>

                <div class="card-footer">
                    @if (Auth::user()->can('criar cliente'))
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
