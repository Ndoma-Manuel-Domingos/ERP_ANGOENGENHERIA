@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Personalizar Impressão A4</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Bem-vindo</li>
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
                <div class="col-md-12 col-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Impressão</h3>
                        </div>
                        <div class="card-body">
                            <div class="bs-stepper">

                                <div class="bs-stepper-header" role="tablist">
                                    <!-- your steps here -->
                                    <div class="step" data-target="#informacao-empresa">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="informacao-empresa" id="informacao-empresa-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Contactos telefónicos</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#tipo-negocio">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="tipo-negocio" id="tipo-negocio-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Website e e-mail</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#definicao-privacidade">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="definicao-privacidade" id="definicao-privacidade-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Cabeçalho e rodapé</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#definicao-coordenada-bancaria">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="definicao-coordenada-bancaria" id="definicao-coordenada-bancaria-trigger">
                                            <span class="bs-stepper-circle">4</span>
                                            <span class="bs-stepper-label">Coordenadas Bancária</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#logotipo">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="logotipo" id="logotipo-trigger">
                                            <span class="bs-stepper-circle">5</span>
                                            <span class="bs-stepper-label">Logótipo</span>
                                        </button>
                                    </div>

                                </div>

                                <div class="bs-stepper-content">

                                    <form action="{{ route('personalizar-empressora.update', $tipo_entidade_logado->empresa->id) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <!-- your steps content here -->
                                        <div id="informacao-empresa" class="content" role="tabpanel" aria-labelledby="informacao-empresa-trigger">
                                            <div class="form-group">
                                                <label for="telefone">Telefone</label>
                                                <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Telefone" value="{{ $tipo_entidade_logado->empresa->telefone }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="telemovel">Telemóvel</label>
                                                <input type="text" class="form-control" name="telemovel" id="telemovel" placeholder="Telemóvel" value="{{ $tipo_entidade_logado->empresa->telemovel }}">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="fax">Fax</label>
                                                <input type="text" class="form-control" id="fax" name="fax" placeholder="Fax" value="{{ $tipo_entidade_logado->empresa->fax }}">
                                            </div>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="tipo-negocio" class="content" role="tabpanel" aria-labelledby="tipo-negocio-trigger">
                                            <!-- radio -->
                                            <div class="form-group">
                                                <label for="website">Website</label>
                                                <input type="text" class="form-control" name="website" id="website" placeholder="Website" value="{{ $tipo_entidade_logado->empresa->website }}">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="email">E-Mail</label>
                                                <input type="text" class="form-control" name="email" value="{{ $tipo_entidade_logado->empresa->email }}" id="email" placeholder="E-Mail">
                                            </div>

                                            <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="definicao-privacidade" class="content" role="tabpanel" aria-labelledby="definicao-privacidade-trigger">
                                            <div class="form-group">
                                                <label for="cabecalho">Cabeçalho</label>
                                                <input type="text" class="form-control" name="cabecalho" value="{{ $tipo_entidade_logado->empresa->cabecalho }}" id="cabecalho" placeholder="Cabeçalho">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="rodape">Rodapé</label>
                                                <input type="text" class="form-control" name="rodape" value="{{ $tipo_entidade_logado->empresa->rodape }}" id="rodape" placeholder="Rodapé">
                                            </div>

                                            <div class="form-group">
                                                <label for="tipo_factura">Tipo de impressora</label>
                                                <select class="form-control" name="tipo_factura" id="tipo_factura">
                                                    <option value="Ticket" {{ $tipo_entidade_logado->empresa->tipo_factura ==  "Ticket" ? 'selected': '' }}>Ticket</option>
                                                    <option value="Normal" {{ $tipo_entidade_logado->empresa->tipo_factura ==  "Normal" ? 'selected': '' }}>Normal (A4)</option>
                                                </select>
                                            </div>

                                            <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="definicao-coordenada-bancaria" class="content" role="tabpanel" aria-labelledby="definicao-coordenada-bancaria-trigger">
                                            <div class="row mb-4">
                                                <div class="col-12 col-md-12">
                                                    <label for="website">1 - BANCO PRINCIPAL</label>
                                                    <div class="row">
                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <label for="banco">BANCO:</label>
                                                                <input type="text" class="form-control" value="{{ $tipo_entidade_logado->empresa->banco }}" name="banco" id="banco" placeholder="EX: BFA - BANCO DE FORMETO ANGOLA">
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <label for="conta">CONTA:</label>
                                                                <input type="text" class="form-control" value="{{ $tipo_entidade_logado->empresa->conta }}" name="conta" id="conta" placeholder="EX: 0000 0000 0000 0000 0">
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <label for="iban">IBAN:</label>
                                                                <input type="text" class="form-control" value="{{ $tipo_entidade_logado->empresa->iban }}" name="iban" id="iban" placeholder="EX: A006 0000 0000 0000 0000 0000 0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12">
                                                    <label for="website">2 - OUTRO BANCO</label>
                                                    <div class="row">
                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <label for="banco">BANCO:</label>
                                                                <input type="text" class="form-control" value="{{ $tipo_entidade_logado->empresa->banco1 }}" name="banco1" id="banco" placeholder="EX: BFA - BANCO DE FORMETO ANGOLA">
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <label for="conta">CONTA:</label>
                                                                <input type="text" class="form-control" value="{{ $tipo_entidade_logado->empresa->conta1 }}" name="conta1" id="conta" placeholder="EX: 0000 0000 0000 0000 0">
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <label for="iban">IBAN:</label>
                                                                <input type="text" class="form-control" value="{{ $tipo_entidade_logado->empresa->iban1 }}" name="iban1" id="iban" placeholder="EX: A006 0000 0000 0000 0000 0000 0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="logotipo" class="content" role="tabpanel" aria-labelledby="logotipo-trigger">
                                            <h6>Utilize o logótipo do seu negócio para personalizar as faturas, factura recibo, factura pro-forma, etc..</h6>
                                            <div class="form-group">
                                                <label for="logotipo">Logotipo</label>
                                                <input type="file" class="form-control" accept="image/*" name="logotipo" id="logotipo2" placeholder="logotipo">
                                                <input type="hidden" class="form-control" name="logotipo_guardado" value="{{ $tipo_entidade_logado->empresa->logotipo }}">
                                            </div>
                                            <p>Recomendamos a utilização do logo em formato rectangular, por exemplo, 300x60px</p>
                                            <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                                            <button type="submit" class="btn btn-primary">Terminar</button>
                                        </div>

                                    </form>

                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">

                        </div>
                    </div>
                    <!-- /.card -->
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
            let formData = new FormData(); // Cria o objeto FormData
            
            // Adiciona os dados serializados ao FormData
            let serializedData = form.serializeArray();
            $.each(serializedData, function(_, field) {
                formData.append(field.name, field.value);
            });
            
            let fileInput2 = $('#logotipo2')[0].files[0];
            if (fileInput2) {
                formData.append('logotipo', fileInput2); // Adiciona o arquivo
            }

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                processData: false, // Impede o processamento dos dados pelo jQuery
                contentType: false, // Impede a configuração automática do cabeçalho
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso

                    // alert(response.mensagem || 'Arquivo exportado com sucesso!');
                    showMessage('Sucesso!', 'Dados Actualozados com sucesso!', 'success');

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
                            messages += `${value} *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', 'Erro ao processar o pedido. Tente novamente.', 'error');
                    }
                }
            , });
        });
    });


    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

</script>
@endsection
