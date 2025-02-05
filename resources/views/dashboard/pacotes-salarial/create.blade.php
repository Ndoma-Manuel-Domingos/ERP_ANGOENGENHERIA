@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cadastrar Pacote Salárial</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pacotes-salarial.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Pacote Salárial</li>
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
                <div class="col-md-12">
                    <form action="{{ route('pacotes-salarial.store') }}" method="post" class="">
                        @csrf
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">bs-stepper</h3>
                            </div>

                            <div class="card-body">
                                <div class="bs-stepper">

                                    <div class="bs-stepper-header" role="tablist">

                                        <div class="step" data-target="#paconte-salarial">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="paconte-salarial" id="paconte-salarial-trigger">
                                                <span class="bs-stepper-circle">1</span>
                                                <span class="bs-stepper-label">Paconte Salarial</span>
                                            </button>
                                        </div>

                                        <div class="line"></div>

                                        <div class="step" data-target="#paconte-subsidios">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="paconte-subsidios" id="paconte-subsidios-trigger">
                                                <span class="bs-stepper-circle">2</span>
                                                <span class="bs-stepper-label">Subsídios</span>
                                            </button>
                                        </div>

                                        <div class="line"></div>

                                        <div class="step" data-target="#paconte-descontos">
                                            <button type="button" class="step-trigger" role="tab" aria-controls="paconte-descontos" id="paconte-descontos-trigger">
                                                <span class="bs-stepper-circle">3</span>
                                                <span class="bs-stepper-label">Descontos</span>
                                            </button>
                                        </div>

                                        <div class="line"></div>

                                    </div>

                                    <div class="bs-stepper-content">

                                        <div id="paconte-salarial" class="content" role="tabpanel" aria-labelledby="paconte-salarial-trigger">

                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <label for="cargo_id" class="form-label">Cargos</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control select2 @error('cargo_id') is-invalid @enderror" id="cargo_id" name="cargo_id">
                                                            <option value="">Selecione</option>
                                                            @foreach ($cargos as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nome }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label for="categoria_id" class="form-label">Categorias</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control select2 @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id">
                                                            <option value="">Selecione</option>
                                                            @foreach ($categorias as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nome }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label for="salario_base" class="form-label">Salário Base</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control @error('salario_base') is-invalid @enderror" name="salario_base" id="salario_base" value="{{ old('salario_base') }}" placeholder="Informe o valor base da remuneração">
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label for="status" class="form-label">Estado</label>
                                                    <div class="input-group mb-3">
                                                        <select type="text" class="form-control select2 @error('status') is-invalid @enderror" id="status" name="status">
                                                            <option value="activo">Activo</option>
                                                            <option value="desactivo">Desactivo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-primary my-4" onclick="stepper.next()">Proxímo</button>
                                        </div>

                                        <div id="paconte-subsidios" class="content" role="tabpanel" aria-labelledby="paconte-subsidios-trigger">

                                            <div class="row">
                                                <div class="col-12 col-md-12" id="dynamic-fields">
                                                    <div class="field-group">
                                                        <div class="row">
                                                            <div class="col-12 col-md-2">
                                                                <label for="subsidio_id_1" class="form-label">Subsídio</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('subsidio_id') is-invalid @enderror" id="subsidio_id_1" name="subsidio_id[]">
                                                                        <option value="">Selecione</option>
                                                                        @foreach ($subsidios as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <label for="salario_subsidio_1" class="form-label">Salário Subsídio</label>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control @error('salario_subsidio') is-invalid @enderror" name="salario_subsidio[]" id="salario_subsidio_1" placeholder="Informe o Valor da remuneração">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <label for="limite_isencao_1" class="form-label">Limite Isenção</label>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control @error('limite_isencao') is-invalid @enderror" name="limite_isencao[]" id="limite_isencao_1" placeholder="Informe o Valor de Limite Isenção">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <label for="irt_1" class="form-label">IRT</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('irt_1') is-invalid @enderror" id="irt_1" name="irt[]">
                                                                        <option value="N">Não Sujeito IRT</option>
                                                                        <option value="Y">Sujeito IRT</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <label for="inss_1" class="form-label">INSS</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('inss_1') is-invalid @enderror" id="inss_1" name="inss[]">
                                                                        <option value="N">Não Sujeito INSS
                                                                        </option>
                                                                        <option value="Y">Sujeito INSS</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-1">
                                                                <label for="processamento_id_1" class="form-label">Tipo Proc.</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control @error('processamento_id') is-invalid @enderror" id="processamento_id_1" name="processamento_id[]">
                                                                        <option value="">Selecione</option>
                                                                        @foreach ($processamentos as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-1">
                                                                <label class="form-label">.</label>
                                                                <div class="input-group mb-3">
                                                                    <button type="button" class="btn btn-danger remove-field"><i class="fas fa-trash"></i> Remover</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-primary my-4" onclick="stepper.previous()">Anterior</button>
                                            <button type="button" class="btn btn-primary my-4" onclick="stepper.next()">Proxímo</button>

                                            <button type="button" id="add-field-subsidio" class="btn btn-success my-4 mx-2 float-right"><i class="fas fa-plus"></i> Adicionar Subsídios</button>
                                        </div>


                                        <div id="paconte-descontos" class="content" role="tabpanel" aria-labelledby="paconte-descontos-trigger">
                                            <div class="row">
                                                <div class="col-12 col-md-12" id="dynamic-fields-descontos">
                                                    <div class="field-group-desconto">
                                                        <div class="row">
                                                            <div class="col-12 col-md-2">
                                                                <label for="desconto_id_1" class="form-label">Descontos</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('desconto_id') is-invalid @enderror" id="desconto_id_1" name="desconto_id[]">
                                                                        <option value="">Selecione</option>
                                                                        @foreach ($descontos as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <label for="salario_desconto_1" class="form-label">Salário Desconto</label>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control @error('salario_desconto') is-invalid @enderror" name="salario_desconto[]" id="salario_desconto_1" placeholder="Informe o Valor da remuneração">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <label for="tipo_valor_1" class="form-label">Tipo
                                                                    Valor</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('tipo_valor_1') is-invalid @enderror" id="tipo_valor_1" name="tipo_valor[]">
                                                                        <option value="P">Percetual</option>
                                                                        <option value="E">Extenso</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <label for="irt_desconto_1" class="form-label">IRT</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('irt_desconto_1') is-invalid @enderror" id="irt_desconto_1" name="irt_desconto[]">
                                                                        <option value="N">Não Sujeito IRT</option>
                                                                        <option value="Y">Sujeito IRT</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-2">
                                                                <label for="inss_desconto_1" class="form-label">INSS</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control  @error('inss_desconto_1') is-invalid @enderror" id="inss_desconto_1" name="inss_desconto[]">
                                                                        <option value="N">Não Sujeito INSS
                                                                        </option>
                                                                        <option value="Y">Sujeito INSS</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-1">
                                                                <label for="processamento_desconto_id_1" class="form-label">Tipo Proc.</label>
                                                                <div class="input-group mb-3">
                                                                    <select class="form-control @error('processamento_desconto_id') is-invalid @enderror" id="processamento_desconto_id_1" name="processamento_desconto_id[]">
                                                                        <option value="">Selecione</option>
                                                                        @foreach ($processamentos as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-1">
                                                                <label class="form-label">.</label>
                                                                <div class="input-group mb-3">
                                                                    <button type="button" class="btn btn-danger remove-field-desconto"><i class="fas fa-trash"></i> Remover</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-primary my-4" onclick="stepper.previous()">Anterior</button>
                                            <button type="submit" class="btn btn-primary my-4">Salvar</button>

                                            <button type="button" id="add-field-desconto" class="btn btn-success my-4 mx-2 float-right"><i class="fas fa-plus"></i> Adicionar Descontos</button>

                                        </div>

                                    </div>

                                </div>
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

    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

    $(document).ready(function() {
        let maxFields = 10; // Limite de campos dinâmicos
        let fieldCount = 1; // Contador de campos dinâmicos

        let maxFieldsDesconto = 10; // Limite de campos dinâmicos
        let fieldCountDesconto = 1; // Contador de campos dinâmicos

        // Função para adicionar novo campo
        $('#add-field-subsidio').click(function() {
            if (fieldCount < maxFields) {
                fieldCount++;
                let newFieldGroup = $('.field-group:first').clone();
                newFieldGroup.find('input, select').each(function() {
                    let currentId = $(this).attr('id');
                    let currentName = $(this).attr('name');
                    let newId = currentId.replace(/_\d+$/, '_' + fieldCount);
                    let newName = currentName.replace(/\[\]$/, '[]');
                    $(this).attr('id', newId);
                    $(this).attr('name', newName);
                    $(this).val(''); // Limpar valores
                });
                newFieldGroup.appendTo('#dynamic-fields');
            } else {
                alert('Você só pode adicionar até 10 campos.');
            }
        });


        // Função para remover campo
        $('#dynamic-fields').on('click', '.remove-field', function() {
            if (fieldCount > 1) {
                $(this).closest('.field-group').remove();
                fieldCount--;
            } else {
                alert('Você deve ter pelo menos um campo.');
            }
        });


        // Função para adicionar novo campo
        $('#add-field-desconto').click(function() {
            if (fieldCountDesconto < maxFieldsDesconto) {
                fieldCountDesconto++;
                let newFieldGroup = $('.field-group-desconto:first').clone();
                newFieldGroup.find('input, select').each(function() {
                    let currentId = $(this).attr('id');
                    let currentName = $(this).attr('name');
                    let newId = currentId.replace(/_\d+$/, '_' + fieldCountDesconto);
                    let newName = currentName.replace(/\[\]$/, '[]');
                    $(this).attr('id', newId);
                    $(this).attr('name', newName);
                    $(this).val(''); // Limpar valores
                });
                newFieldGroup.appendTo('#dynamic-fields-descontos');
            } else {
                alert('Você só pode adicionar até 10 campos.');
            }
        });


        // Função para remover campo
        $('#dynamic-fields-descontos').on('click', '.remove-field-desconto', function() {
            if (fieldCountDesconto > 1) {
                $(this).closest('.field-group-desconto').remove();
                fieldCountDesconto--;
            } else {
                alert('Você deve ter pelo menos um campo.');
            }
        });
    });

</script>
@endsection
