@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Identificação e activadades da empresa</h1>
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
                            <h3 class="card-title">Primeiro Passos</h3>
                        </div>
                        <div class="card-body">
                            <div class="bs-stepper">

                                <div class="bs-stepper-header" role="tablist">
                                    <!-- your steps here -->
                                    <div class="step" data-target="#informacao-empresa">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="informacao-empresa" id="informacao-empresa-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Informações Empresa</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#tipo-negocio">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="tipo-negocio" id="tipo-negocio-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Tipo Negócio</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#definicao-privacidade">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="definicao-privacidade" id="definicao-privacidade-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Definições de Privacidades</span>
                                        </button>
                                    </div>

                                </div>

                                <div class="bs-stepper-content">

                                    <form action="{{ route('identidade-empresa.update', $tipo_entidade_logado->empresa->id ) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <!-- your steps content here -->
                                        <div id="informacao-empresa" class="content" role="tabpanel" aria-labelledby="informacao-empresa-trigger">
                                            <div class="form-group">
                                                <label for="nif">Nº Contribuente</label>
                                                <input type="text" class="form-control" name="nif" id="nif" value="{{ $tipo_entidade_logado->empresa->nif ?? '' }}" placeholder="Número de Contribuente">
                                            </div>
                                            <div class="form-group">
                                                <label for="empresa">Empresa</label>
                                                <input type="text" class="form-control" name="empresa" id="empresa" value="{{ $tipo_entidade_logado->empresa->nome ?? ''  }}" placeholder="Nome da Empresa">
                                            </div>
                                            <div class="form-group">
                                                <label for="nome_dono">Nome Próprio</label>
                                                <input type="text" class="form-control" name="nome_dono" id="nome_dono" value="{{ Auth::user()->name ?? ''  }}" placeholder="Nome do Dono">
                                            </div>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="tipo-negocio" class="content" role="tabpanel" aria-labelledby="tipo-negocio-trigger">
                                            @if ($tipo_entidade_logado->empresa->tipo_empresa != "Fisica")
                                            <!-- radio -->
                                            <div class="form-group clearfix">
                                                @foreach ($tipos_entidade as $item)
                                                <div class="icheck-primary d-block bg-light p-3">
                                                    <input type="radio" id="radioPrimary_farmacia{{ $item->id }}" name="tipo_negocio" value="{{ $item->id }}" {{ $tipo_entidade_logado->empresa->tipo_entidade['id'] == $item->id ? 'checked' : ''}}>
                                                    <label for="radioPrimary_farmacia{{ $item->id }}">
                                                        {{ $item->tipo }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                            <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="definicao-privacidade" class="content" role="tabpanel" aria-labelledby="definicao-privacidade-trigger">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5>Promoções por e-mail</h5>
                                                    <p>Ocorrem pontualmente durante o ano.{{ $tipo_entidade_logado->empresa->promocoes_email }}</p>
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary">
                                                            <input type="radio" id="radioPrimary_promocao_email_sim" value="{{ $tipo_entidade_logado->empresa->promocoes_email }}" name="promocao_email" {{ $tipo_entidade_logado->empresa->promocoes_email ? 'checked' : ''}}>
                                                            <label for="radioPrimary_promocao_email_sim">
                                                                Sim, quero receber promoções por e-mail.
                                                            </label>
                                                        </div>

                                                        <div class="icheck-primary">
                                                            <input type="radio" id="radioPrimary_promocao_email_nao" value="{{ $tipo_entidade_logado->empresa->promocoes_email }}" name="promocao_email" {{ $tipo_entidade_logado->empresa->promocoes_email ? '' : 'checked'}}>
                                                            <label for="radioPrimary_promocao_email_nao">
                                                                Não, Não quero receber.
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <h5>Novidades e informações por e-mail</h5>
                                                    <p>Ocorrem em média 3 vezes por mês.</p>
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary">
                                                            <input type="radio" id="radioPrimary_novidades_email_sim" value="{{ $tipo_entidade_logado->empresa->novidade_email }}" name="promocao_novidade_email" {{ $tipo_entidade_logado->empresa->novidade_email ? 'checked' : ''}}>
                                                            <label for="radioPrimary_novidades_email_sim">
                                                                Sim, quero receber novidades e informações por e-mail.
                                                            </label>
                                                        </div>

                                                        <div class="icheck-primary">
                                                            <input type="radio" id="radioPrimary_novidades_email_nao" value="{{ $tipo_entidade_logado->empresa->novidade_email }}" name="promocao_novidade_email" {{ $tipo_entidade_logado->empresa->novidade_email ? '' : 'checked'}}>
                                                            <label for="radioPrimary_novidades_email_nao">
                                                                Não, Não quero receber.
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

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
