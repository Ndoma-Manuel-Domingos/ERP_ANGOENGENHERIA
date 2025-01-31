@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Actualização dos Dados da Empresa</h1>
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
                            {{-- <h3 class="card-title">Configuração</h3> --}}
                        </div>
                        <div class="card-body">
                            <div class="bs-stepper">

                                <div class="bs-stepper-header" role="tablist">
                                    <!-- your steps here -->
                                    <div class="step" data-target="#endereco-empresa">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="endereco-empresa" id="endereco-empresa-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Endereço, Sede da Empresa </span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#dado-estrutura">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="dado-estrutura" id="dado-estrutura-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Dados Estrutura da Empresa</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#informacao-comercial">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="informacao-comercial" id="informacao-comercial-trigger">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">Informações Comercial</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#regiao">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="regiao" id="regiao-trigger">
                                            <span class="bs-stepper-circle">4</span>
                                            <span class="bs-stepper-label">Região</span>
                                        </button>
                                    </div>

                                    <div class="line"></div>
                                    <div class="step" data-target="#impostos">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="impostos" id="impostos-trigger">
                                            <span class="bs-stepper-circle">5</span>
                                            <span class="bs-stepper-label">Impostos Predefinido</span>
                                        </button>
                                    </div>

                                </div>

                                <div class="bs-stepper-content">

                                    <form action="{{ route('dados-empresa.update', $dados->empresa->id ) }}" enctype="multipart/form-data" method="post">
                                        @csrf
                                        @method('put')
                                        <!-- your steps content here -->
                                        <div id="endereco-empresa" class="content" role="tabpanel" aria-labelledby="endereco-empresa-trigger">
                                            <div class="form-group">
                                                <label for="morada">Morada</label>
                                                <input type="text" class="form-control" name="morada" id="morada" value="{{ $dados->empresa->morada }}" placeholder="Informe a Morada">
                                            </div>
                                            <div class="form-group">
                                                <label for="codigo_postal">Codigo Postal</label>
                                                <input type="text" class="form-control" name="codigo_postal" id="codigo_postal" value="{{ $dados->empresa->codigo_postal }}" placeholder="Informe o codigo Postal">
                                            </div>
                                            <div class="form-group">
                                                <label for="cidade">Cidade</label>
                                                <input type="text" class="form-control" name="cidade" id="cidade" value="{{ $dados->empresa->cidade }}" placeholder="informe a cidade">
                                            </div>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="dado-estrutura" class="content" role="tabpanel" aria-labelledby="dado-estrutura-trigger">
                                            <div class="form-group">
                                                <label for="convervatoria">Conservatória</label>
                                                <input type="text" class="form-control" value="{{ $dados->empresa->conservatoria }}" name="conservatoria" id="convervatoria" placeholder="Conservatória">
                                            </div>

                                            <div class="form-group">
                                                <label for="capital_social">Capital Social</label>
                                                <input type="text" class="form-control" value="{{ $dados->empresa->capital_social }}" name="capital_social" id="capital_social" placeholder="Capital Social">
                                            </div>
                                            
                                           

                                            <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="informacao-comercial" class="content" role="tabpanel" aria-labelledby="informacao-comercial-trigger">

                                            <div class="form-group">
                                                <label for="nome_comercial">Nome Comercial</label>
                                                <input type="text" class="form-control" value="{{ $dados->empresa->nome_comercial }}" name="nome_comercial" id="nome_comercial" placeholder="Nome Comercial">
                                            </div>

                                            <div class="form-group">
                                                <label for="slogan">Slogan</label>
                                                <input type="text" class="form-control" value="{{ $dados->empresa->slogan }}" name="slogan" id="slogan" placeholder="Slogan">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="tipo_inventario">Tipo Inventário </label>
                                                <select class="select2 form-control" name="tipo_inventario" id="tipo_inventario">
                                                    <option value="PERMANENTE" {{ $dados->empresa->tipo_inventario }}>Permanente</option>
                                                    <option value="PERIODICO" {{ $dados->empresa->tipo_inventario }}>Periódico</option>
                                                </select>
                                            </div>
                                            <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="regiao" class="content" role="tabpanel" aria-labelledby="regiao-trigger">

                                            <div class="form-group">
                                                <label for="pais">País</label>
                                                <select class="form-control" name="pais" id="pais">
                                                    <option value="Angola" {{ $dados->empresa->pais == "Angola" ? 'selected' : "" }}>Angola</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="slogan">Moeda</label>
                                                <select class="form-control" name="moeda">
                                                    <option value="Kz" {{ $dados->empresa->taxa_iva ==  "Kz" ? 'selected': '' }}>Kwanza (Kz)</option>
                                                    {{--<option value="Dolar" {{ $dados->empresa->taxa_iva ==  "Dolar" ? 'selected': '' }}>Dolar ($)</option>--}}
                                                </select>
                                            </div>

                                            <a class="btn btn-primary" onclick="stepper.previous()">Anterior</a>
                                            <a class="btn btn-primary" onclick="stepper.next()">Próximo</a>
                                        </div>

                                        <div id="impostos" class="content" role="tabpanel" aria-labelledby="impostos-trigger">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="tipo_regime_id">Tipo de Regime do IVA</label>
                                                        <select class="form-control" name="tipo_regime_id" id="tipo_regime_id">
                                                            <option value="regime_exclusao"  {{ $dados->empresa->tipo_regime_id == 'regime_exclusao' ? 'selected' : '' }}>REGIME DE EXCLUSÃO</option>
                                                            <option value="regime_geral" {{ $dados->empresa->tipo_regime_id == 'regime_geral' ? 'selected' : '' }}>REGIME GERAL</option>
                                                            <option value="regime_simplificado" {{ $dados->empresa->tipo_regime_id == 'regime_simplificado' ? 'selected' : '' }}>REGIME SIMPLIFICADO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="taxa_iva">Taxa de IVA Predefinida</label>
                                                        <select class="form-control" name="taxa_iva" id="taxa_iva">
                                                            @foreach ($impostos as $item)
                                                                <option value="{{ $item->id }}" {{ $dados->empresa->imposto_id == $item->id ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label for="motivo_isencao">Motivo Predefinido de Isenção (Se Aplicável)</label>
                                                        <select class="form-control" name="motivo_isencao" id="motivo_isencao">
                                                            @foreach ($motivos as $item)
                                                                <option value="{{ $item->id }}" {{ $dados->empresa->motivo_id == $item->id ? 'selected': '' }}>{{ $item->descricao }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label for="sigla_factura">Sigla Factura</label>
                                                        <input class="form-control" name="sigla_factura" id="sigla_factura" value="{{ $dados->empresa->sigla_factura }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label for="ano_factura">Ano da Seríe para Factura</label>
                                                        <input class="form-control" name="ano_factura" id="ano_factura" value="{{ $dados->empresa->ano_factura }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label for="taxa_retencao_fonte">Taxa da Retenção Na Fonte</label>
                                                        <input class="form-control" name="taxa_retencao_fonte" id="taxa_retencao_fonte" value="{{ $dados->empresa->taxa_retencao_fonte }}">
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
