@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Configuração da Impressão</h1>
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
                    <button type="button" class="step-trigger" role="tab" aria-controls="informacao-empresa"
                      id="informacao-empresa-trigger">
                      <span class="bs-stepper-circle">1</span>
                      <span class="bs-stepper-label">Tipo de Utilização</span>
                    </button>
                  </div>

                  <div class="line"></div>
                  <div class="step" data-target="#tipo-negocio">
                    <button type="button" class="step-trigger" role="tab" aria-controls="tipo-negocio"
                      id="tipo-negocio-trigger">
                      <span class="bs-stepper-circle">2</span>
                      <span class="bs-stepper-label">A impressora funciona?</span>
                    </button>
                  </div>

                  <div class="line"></div>
                  <div class="step" data-target="#definicao-privacidade">
                    <button type="button" class="step-trigger" role="tab" aria-controls="definicao-privacidade"
                      id="definicao-privacidade-trigger">
                      <span class="bs-stepper-circle">3</span>
                      <span class="bs-stepper-label">Método de Impressão</span>
                    </button>
                  </div>

    
                </div>

                <div class="bs-stepper-content">
                  <form action="{{ route('configurar-empressora.update', $dados->id) }}" method="post">
                    @csrf
                    @method('put')
                    <!-- your steps content here -->
                    <div id="informacao-empresa" class="content" role="tabpanel" aria-labelledby="informacao-empresa-trigger">
                      <div class="form-group clearfix">
                        <h6>Pretende utilizar a impressora através de que tipo de equipamento?</h6> 
                        <h5>Impressão</h5>
                        <div class="icheck-primary">
                          <input type="radio" id="radioPrimary_impressao_computador" {{ $dados->empressao ==  "1" ? 'checked': '' }}  value="1" name="empressao">
                          <label for="radioPrimary_impressao_computador">
                            Através de um computador.
                          </label>
                        </div>

                        <div class="icheck-primary">
                          <input type="radio" id="radioPrimary_impressao_tablet" {{ $dados->empressao ==  "0" ? 'checked': '' }}  value="0" name="empressao">
                          <label for="radioPrimary_impressao_tablet">
                            Através do telemóvel ou tablet.
                          </label>
                        </div>
                      </div>
                      <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                    </div>

                    <div id="tipo-negocio" class="content" role="tabpanel" aria-labelledby="tipo-negocio-trigger">
                      <!-- radio -->
                      <div class="form-group clearfix">
                        <h6>A impressora encontra-se corretamente instalada no seu computador?</h6> 
                        <div class="icheck-primary">
                          <input type="radio" id="radioPrimary_impressao_condicao_sim" {{ $dados->funcionamento ==  "1" ? 'checked': '' }}  value="1" name="funcionamento">
                          <label for="radioPrimary_impressao_condicao_sim">
                            Sim, já consigo imprimir uma página de teste.
                          </label>
                        </div>

                        <div class="icheck-primary">
                          <input type="radio" id="radioPrimary_impressao_condicao_nao" {{ $dados->funcionamento ==  "0" ? 'checked': '' }}  value="0" name="funcionamento">
                          <label for="radioPrimary_impressao_condicao_nao">
                            Não, nem sei como o fazer.
                          </label>
                        </div>
                      </div>

                      <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                      <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                    </div>

                    <div id="definicao-privacidade" class="content" role="tabpanel"
                      aria-labelledby="definicao-privacidade-trigger">
                      <div class="row">
                        <div class="col-12">
                          <h6>A impressora encontra-se corretamente instalada no seu computador?</h6>
                          <div class="form-group clearfix">
                            <div class="icheck-primary">
                              <input type="radio" id="metodo_empressao" name="metodo_empressao" value="1" {{ $dados->funcionamento ==  "1" ? 'checked': '' }} >
                              <label for="metodo_empressao">
                                Impressora Termica
                              </label>
                              <p>Aplicação para optimizar o processo de impressão.</p>
                            </div>

                            <div class="icheck-primary">
                              <input type="radio" id="metodo_empressao1" name="metodo_empressao" value="0" {{ $dados->funcionamento ==  "0" ? 'checked': '' }} >
                              <label for="metodo_empressao1">
                                Impressão pelo browser
                              </label>
                              <p>Impressão com recurso à previsualização do browser.</p>
                            </div>
                          </div>
                        </div>
                      </div>

                      <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                      {{-- <a class="btn btn-primary" onclick="stepper.next()">Próximo</a> --}}
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
    document.addEventListener('DOMContentLoaded', function () {
      window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })
</script>
@endsection