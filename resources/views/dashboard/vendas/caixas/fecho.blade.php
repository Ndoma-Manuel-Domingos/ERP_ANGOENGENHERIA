@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Fecho de caixa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pronto-venda') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Painel de venda</li>
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
                <div class="col-12 col-md-6 col-lg-6">
                    <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Voltar</a>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('caixa.fechamento_caixa_create') }}" method="post" class="row">
                                @csrf
                                <div class="col-12 col-md-12 text-center">
                                    <label for="">Montante Disponível ao Fechar o Caixa</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Kz</span>
                                        </div>
                                        @php
                                           $saldo = $debito - $credito;
                                        @endphp
                                        <input type="text" placeholder="Informe o montante disponível ao fechar o caixa" class="form-control form-control-lg @error('valor') is-invalid @enderror" value="{{ old('valor') ?? $saldo }}" name="valor">
                                    </div>
                                </div>

                                <div class="input-group mt-3">
                                    <span class="input-group-append text-center">
                                        <button type="submit" class="btn btn-info btn-flat mx-2"><i class="fas fa-check"></i> Confirmar</button>
                                        <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-info btn-flat mx-2"><i class="fas fa-close"></i> Cancelar</a>
                                    </span>
                                </div>
                                <!-- /input-group -->

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6"> 
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                  <tr>
                                    <th colspan="12" class="text-center">Resumo dos Movimentos</th>
                                  </tr>
                                  <tr>
                                    <th colspan="5">Tipo</th>
                                    <th class="text-right">Credito</th>
                                    <th class="text-right">Debito</th>
                                    <th colspan="5" class="text-right">Montante</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  
                                  <tr>
                                    <td colspan="5">Entradas por Multicaixa(TPA)</td>
                                    <td class="text-right">{{ number_format($multicaixa_credito, 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($multicaixa_debito, 2, ',', '.') }}</td>
                                    <td colspan="5" class="text-right">{{ number_format($multicaixa, 2, ',', '.') }}</td>
                                  </tr>

                                  <tr>
                                    <td colspan="5">Entradas por Numerário(Cash)</td>
                                    <td class="text-right">{{ number_format($numerorio_credito, 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($numerorio_debito, 2, ',', '.') }}</td>
                                    <td colspan="5" class="text-right">{{ number_format($numerorio, 2, ',', '.') }}</td>
                                  </tr>
                                  
                                  <tr>
                                    <td colspan="5">Entradas por Multicaixa & Numerário (DUPLO)</td>
                                    <td class="text-right">{{ number_format($duplo_credito, 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($duplo_debito, 2, ',', '.') }}</td>
                                    <td colspan="5" class="text-right">{{ number_format($duplo, 2, ',', '.') }}</td>
                                  </tr>
                                  
                                  <tr>
                                    <td colspan="5">Saída <small class="text-info">(incluindo todas as operações)</small></td>
                                    <td class="text-right">--</td>
                                    <td class="text-right">--</td>
                                    <td colspan="5" class="text-right">{{ number_format($credito, 2, ',', '.') }}</td>
                                  </tr>
                                  
                                  <tr>
                                    <td colspan="5">Entrada <small class="text-info">(incluindo todas as operações)</small></td>
                                    <td class="text-right">--</td>
                                    <td class="text-right">--</td>
                                    <td colspan="5" class="text-right">{{ number_format($debito, 2, ',', '.') }}</td>
                                  </tr>
                                  
                                  <tr>
                                    <th colspan="5">Saldo Final</th>
                                    <td class="text-right">--</td>
                                    <td class="text-right">--</td>
                                    <th colspan="5" class="text-right">{{ number_format($saldo, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></th>
                                  </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
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
                }, 
                success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();

                    showMessage('Sucesso!', 'Seja bem vindo ao sistema!', 'success');

                    window.location.href = response.redirect;

                    // window.location.reload();
                }, 
                error: function(xhr) {
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
                }, 
            });
        });
    });
    
</script>
@endsection
