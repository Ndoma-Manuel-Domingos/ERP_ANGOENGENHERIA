@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Definir Grupo de Preço</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('produtos.show', $produto->id) }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Produto</li>
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

                <div class="col-md-12 col-12">
                    <div class="card">
                        <form action="{{ route('grupos_preco.produtos.put', $produto->id) }}" method="post" class="" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="card-body row">

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Imposto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="imposto" id="imposto">
                                            <option value=''>Automático</option>
                                            @foreach ($impostos as $item)
                                            <option value="{{ $item->id }}" {{ $produto->imposto_id == $item->id ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('imposto')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Motivo Isenção</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control" name="motivo_isencao">
                                            @foreach ($motivos as $item)
                                            <option value="{{ $item->id }}" {{ $produto->motivo_id == $item->id ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <p class="text-danger">
                                        @error('motivo_isencao')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Preço de Custo</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="preco_custo" name="preco_custo" value="{{ $produto->preco_custo }}" placeholder="Preço de Custo">
                                    </div>
                                    <p class="text-danger">
                                        @error('preco_custo')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                @section('styles')
                                <style>
                                    .btn-aplicao {
                                        border: 1px solid #1a1a1a;
                                        display: inline-block;
                                        padding: 5px 10px;
                                        position: relative;
                                        text-align: center;
                                        transition: background 600ms ease, color 600ms ease;
                                    }

                                </style>
                                @endsection

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">.</label>
                                    <div class="input-group mb-3">
                                        <input id="toggle-on" class="toggle toggle-left" name="iva_recomendar" value="false" type="radio" checked>
                                        <label for="toggle-on" class="btn-aplicao">c/IVA</label>
                                        <input id="toggle-off" class="toggle toggle-right" name="iva_recomendar" value="true" type="radio">
                                        <label for="toggle-off" class="btn-aplicao">s/IVA</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Margem</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="margem" id="margem" value="{{ $produto->margem }}" placeholder="Margem">
                                    </div>
                                    <p class="text-danger">
                                        @error('margem')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Preço de Venda</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="preco_venda" name="preco_venda" value="{{ $produto->preco_venda }}" placeholder="Preço de Venda do Produto">
                                    </div>
                                    <p class="text-danger">
                                        @error('preco_venda')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Preço</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="preco" name="preco" value="{{ $produto->preco }}" placeholder="Preço" disabled>
                                    </div>
                                    <p class="text-danger">
                                        @error('preco')
                                        {{ $message }}
                                        @enderror
                                    </p>
                                </div>

                                <input type="hidden" name="preco_venda" id="preco_venda_guardado" value="" disabled>
                                <input type="hidden" name="preco" id="preco_guardado" value="">

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Estado</label>
                                    <select type="text" class="form-control" name="status">
                                        <option value="activo">Activo</option>
                                        <option value="desactivo" selected>Desactivo</option>
                                    </select>
                                    <p class="text-danger">
                                        @error('status')
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

  $(document).ready(function() {

      $("#toggle-on").on('click', function() {
          if ($("#toggle-on").is(':checked')) {
              $("#preco").prop("disabled", true);
              $("#preco_guardado").prop("disabled", false);
              $("#preco_venda").prop("disabled", false);
              $("#preco_venda_guardado").prop("disabled", true);
          } else {
              $("#preco").prop("disabled", false);
              $("#preco_guardado").prop("disabled", true);
              $("#preco_venda").prop("disabled", true);
              $("#preco_venda_guardado").prop("disabled", false);
          }
      });

      $("#toggle-off").on('click', function() {
          if ($("#toggle-off").is(':checked')) {
              $("#preco").prop("disabled", false);
              $("#preco_guardado").prop("disabled", true);
              $("#preco_venda").prop("disabled", true);
              $("#preco_venda_guardado").prop("disabled", false);
          } else {
              $("#preco").prop("disabled", true);
              $("#preco_guardado").prop("disabled", false);
              $("#preco_venda").prop("disabled", false);
              $("#preco_venda_guardado").prop("disabled", true);
          }
      });

      function valorImposto() {
          var elem = $("#imposto").val();
          if (elem == '1') {
              return 0;
          } else if (elem == '2') {
              return 2;
          } else if (elem == '3') {
              return 5;
          } else if (elem == '4') {
              return 7;
          } else if (elem == '5') {
              return 14;
          } else {
              return 14;
          }
      }

      // mudar valor imposto
      $("#imposto").change(function(eventObject) {
          var elem = $(this).val();
          if (elem == '1') {
              calcularPreco(0);
              calcularMargem(0);
          } else if (elem == '2') {
              calcularPreco(2);
              calcularMargem(2);
          } else if (elem == '3') {
              calcularPreco(5);
              calcularMargem(5);
          } else if (elem == '4') {
              calcularPreco(7);
              calcularMargem(7);
          } else if (elem == '5') {
              calcularPreco(14);
              calcularMargem(14);
          } else {
              calcularPreco(14);
              calcularMargem(14);
          }
      });

      function calcularPreco(imposto) {
          // var tipoImposto = $("#imposto").val();
          if (imposto == 0) {
              $("#preco_venda").val($("#preco_custo").val());
              $("#preco_venda_guardado").val($("#preco_custo").val());

              $("#preco_guardado").val($("#preco_custo").val());
              $("#preco").val($("#preco_custo").val());
          } else {

              var valorDigitado = parseInt($("#preco_custo").val());
              var valor = valorDigitado + (valorDigitado * (imposto / 100));

              $("#preco_venda").val(valor);
              $("#preco_venda_guardado").val(valor);

              if ($("#margem").val() == "" || parseInt($("#margem").val()) < 0) {
                  console.log("sem valor");
              } else {
                  var precoVenda = valorDigitado + (valorDigitado * (imposto / 100));
                  var actualizarPrecoVenda = precoVenda + (precoVenda * (parseInt($("#margem").val()) / 100));
                  $("#preco_venda").val(actualizarPrecoVenda);
                  $("#preco_venda_guardado").val(actualizarPrecoVenda);
              }
          }
      }

      function calcularMargem(imposto) {
          if (imposto == 0) {
              $("#preco_venda").val($("#preco_custo").val());
              $("#preco_venda_guardado").val($("#preco_custo").val());

              $("#preco").val($("#preco_custo").val());
              $("#preco_guardado").val($("#preco_custo").val());
          } else {
              /******************/
              // recuperar preco custo
              var precoCusto = parseInt($("#preco_custo").val());
              var resultPrecoVenda = precoCusto + (precoCusto * (imposto / 100));
              /******************/
              // actualizar preco venda
              var actualizarPrecoVenda = resultPrecoVenda + (resultPrecoVenda * (parseInt($("#margem").val()) / 100));
              $("#preco_venda").val(actualizarPrecoVenda);
              $("#preco_venda_guardado").val(actualizarPrecoVenda);


              // actualizar preco do produto
              var percentagem = parseInt($("#margem").val()) / 100;
              $("#preco").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
              $("#preco_guardado").val(parseInt($("#preco_custo").val()) * (1 + percentagem));
          }

      }

      $("#preco_custo").on('input', function() {
          calcularPreco(valorImposto());
      });

      $("#margem").on('input', function() {
          calcularMargem(valorImposto());
      })

  });
</script>
@endsection
