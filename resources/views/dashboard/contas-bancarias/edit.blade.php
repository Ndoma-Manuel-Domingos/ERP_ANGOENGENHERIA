@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Conta Bancária</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('contas-bancarias.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Conta Bancária</li>
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
                        <form action="{{ route('contas-bancarias.update', $banco->id) }}" method="post" class="">
                            @csrf
                            @method('put')
                            <div class="card-body row">

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="banco_id" class="form-label">Banco</label>
                                    <select type="text" class="select2 form-control" name="banco_id">
                                        <option value="">Escolher</option>
                                        @foreach ($bancos as $item)
                                        <option value="{{ $item->id }}" {{ $banco->banco_id == $item->id ? 'selected' : "" }}>{{ $item->sigla }} - {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="moeda" class="form-label">Moeda</label>
                                    <select type="text" class="form-control" name="moeda">
                                        <option value="KZ" {{ $banco->moeda == "KZ" ? 'selected' : '' }}>KZ</option>
                                        <option value="USD" {{ $banco->moeda == "USD" ? 'selected' : '' }}>USD</option>
                                    </select>
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="tipo_banco_id" class="form-label">Conta Cont.</label>
                                    <select type="text" class="form-control select2" id="tipo_banco_id" name="tipo_banco_id">
                                        <option value="DO" {{ $banco->tipo_banco_id == "DO" ? 'selected' : '' }}>Depósitos à Ordem</option>
                                        <option value="DP" {{ $banco->tipo_banco_id == "DP" ? 'selected' : '' }}>Depósitos a prazo</option>
                                        <option value="OD" {{ $banco->tipo_banco_id == "OD" ? 'selected' : '' }}>Outros Depósitos</option>
                                    </select>
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="numero_conta" class="form-label">Nº da Conta</label>
                                    <input type="text" id="numero_conta" class="form-control" name="numero_conta" value="{{ $banco->numero_conta ?? old('numero_conta') }}" placeholder="Informe o Nº da conta do banco">
                                    <p class="text-danger">
                                        @error('numero_conta')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="iban" class="form-label">Nº do IBAN</label>
                                    <input type="text" class="form-control" id="iban" name="iban" value="{{ $banco->iban ?? old('iban') }}" placeholder="Informe o Iban do banco">
                                    <p class="text-danger">
                                        @error('iban')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="nib" class="form-label">Nº do NIB</label>
                                    <input type="text" class="form-control" id="nib" name="nib" value="{{ $banco->nib ?? old('nib') }}" placeholder="Informe o NIB do Banco">
                                    <p class="text-danger">
                                        @error('nib')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="switf" class="form-label">SWITF</label>
                                    <input type="text" class="form-control" id="switf" name="switf" value="{{ $banco->switf ?? old('switf') }}" placeholder="Informe o switf do Banco">
                                    <p class="text-danger">
                                        @error('switf')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="nome_agencia" class="form-label">Nome (Agência)</label>
                                    <input type="text" class="form-control" id="nome_agencia" name="nome_agencia" value="{{ $banco->nome_agencia ?? old('nome_agencia') }}" placeholder="Informe o nome_agencia do Banco">
                                    <p class="text-danger">
                                        @error('nome_agencia')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="numero_gestor" class="form-label">Número Gestor (Agência)</label>
                                    <input type="text" class="form-control" id="numero_gestor" name="numero_gestor" value="{{ $banco->numero_gestor ?? old('numero_gestor') }}" placeholder="Informe o numero_gestor do Banco">
                                    <p class="text-danger">
                                        @error('numero_gestor')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>


                                <div class="col-12 col-md-3 mb-3">
                                    <label for="nome_titular" class="form-label">Nome (Titular)</label>
                                    <input type="text" id="nome_titular" class="form-control" name="nome_titular" value="{{ $banco->nome_titular ?? old('nome_titular') }}" placeholder="Informe o Nome do Titular">
                                    <p class="text-danger">
                                        @error('nome_titular')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="morada_titular" class="form-label">Morada (Titular)</label>
                                    <input type="text" id="morada_titular" class="form-control" name="morada_titular" value="{{ $banco->morada_titular ?? old('morada_titular') }}" placeholder="Informe a Morada do Titular">
                                    <p class="text-danger">
                                        @error('morada_titular')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="local_titular" class="form-label">Local (Titular)</label>
                                    <input type="text" id="local_titular" class="form-control" name="local_titular" value="{{ $banco->local_titular ?? old('local_titular') }}" placeholder="Informe a Morada do Titular">
                                    <p class="text-danger">
                                        @error('local_titular')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="codigo_postal_titular" class="form-label">Codigo Postal (Titular)</label>
                                    <input type="text" id="codigo_postal_titular" class="form-control" name="codigo_postal_titular" value="{{ $banco->codigo_postal_titular ?? old('codigo_postal_titular') }}" placeholder="Informe a Morada do Titular">
                                    <p class="text-danger">
                                        @error('codigo_postal_titular')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select type="text" class="form-control" name="status">
                                        <option value="aberto" {{ $banco->status == "aberto" ? 'selected' : '' }}>Activo</option>
                                        <option value="fechado" {{ $banco->status == "fechado" ? 'selected' : '' }}>Desactivo</option>
                                    </select>
                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('editar caixa'))
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                @endif
                                <button type="reset" class="btn btn-danger">Cancelar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.row -->
                </div>
            </div>

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
