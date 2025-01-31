<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? '' }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        
    <link rel="stylesheet" href="{{ asset('dist/css/sweetalert2.min.css') }}">
    
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <style>
        .container-bg {
            position: relative;
            overflow: hidden;
            height: 100vh;
            /* Ajuste conforme necessário */
        }

        .container-bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/dist/img/focused-young-man-paying-bill-store.jpg');
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
            opacity: 0.9;
            filter: blur(5px);
            /* Ajuste o nível de desfoque aqui */
            z-index: -1;
            /* Certifique-se de que o fundo fique atrás do conteúdo */
        }
                
        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: -2;
        }

        .spinner {
            width: 100px;
            height: 100px;
            border: 10px solid #f3f3f3;
            border-top: 10px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
                
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

    </style>

</head>

<body class="hold-transition login-page container-bg">
    <div class="login-box">
        <div class="card card-outline card-dark">
            <div class="card-body">
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

                <form action="{{ route('create') }}" method="post" class="row">
                    @csrf

                    <div class="col-6 col-md-12">
                        <label for="tipo_empresa">Tipo da Empresa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="tipo_empresa" id="tipo_empresa" class="form-control form-control-lg">
                                <option value="">--Selecione Tipo Empresa--</option>
                                <option value="Juridica">Empresa</option>
                                <option value="Fisica">Comerciante/Técnico Profissional</option>
                            </select>
                        </div>
                        <p class="text-danger">
                            @error('tipo_empresa')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>


                    <div class="col-6 col-md-12" style="display: block" id="campo_nome_empresa">
                        <label for="nome_empresa">Nome da Empresa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" name="nome_empresa" id="nome_empresa" value="{{ old('nome_empresa') ?? '' }}" placeholder="Nome da Empresa">
                        </div>
                        <p class="text-danger">
                            @error('nome_empresa')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>


                    <div class="col-6 col-md-6">
                        <label for="nif">Nº Contribuinte <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" name="nif" value="{{ old('nif') ?? '' }}" placeholder="Nº Contribuinte">
                        </div>
                        <p class="text-danger">
                            @error('nif')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-6 col-md-6">
                        <label for="username">Nome do Proprietario <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" name="username" value="{{ old('username') ?? '' }}" placeholder="Informe o seu Nome">
                        </div>
                        <p class="text-danger">
                            @error('username')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-6 col-md-6">
                        <label for="email">E-mail <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="email" class="form-control form-control-lg" value="{{ old('email') ?? '' }}" name="email" placeholder="Email">
                        </div>
                        <p class="text-danger">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-6 col-md-6">
                        <label for="telefone">Telefone <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" value="{{ old('telefone') ?? '' }}" name="telefone" id="telefone" placeholder="Telefone">
                        </div>
                        <p class="text-danger">
                            @error('telefone')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12" style="display: block" id="campo_tipo_negocio">
                        <label for="tipo_negocio">Tipo de Negócio <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="tipo_negocio" id="tipo_negocio" class="form-control form-control-lg">
                                <option value="">--Selecione Tipo Negócio--</option>
                                @if ($tipos_entidade)
                                @foreach ($tipos_entidade as $item)
                                <option value="{{ $item->id }}">{{ $item->tipo }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <p class="text-danger">
                            @error('tipo_negocio')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-6 col-md-6">
                        <label for="password">Senha <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg" value="{{ old('password') ?? '' }}" id="password" name="password" placeholder="Senha">
                        </div>
                        <p class="text-danger">
                            @error('password')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-6 col-md-6">
                        <label for="r_password">Confirmar Senha <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg" value="{{ old('r_password') ?? '' }}" id="r_password" name="r_password" placeholder="Confirmar Senha">
                        </div>
                        <p class="text-danger">
                            @error('r_password')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-12">
                        <button type="submit" class="btn-lg btn-dark btn-block">Criar Conta</button>
                    </div>

                </form>

                <p class="mb-0 mt-3 text-center">
                    <a href="{{ route('login') }}"  class="text-right h4">Usar a sua conta!</a>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 col-md-12 text-center text-dark mt-3">
            <h3>"Dê o próximo passo para transformar seu negócio!"</h3>
            <h6>
                Com o nosso sistema, você terá as ferramentas certas para gerenciar seu negócio de forma eficiente e organizada. Controle suas operações, economize <br>tempo e foque no que realmente importa: o crescimento e o sucesso do seu empreendimento.<br>
            </h6>
            <h6>
                <strong>Crie sua conta agora</strong> e descubra como é simples ter o controle nas suas mãos!
            </h6>

            <h6 class="mt-2">
                Angoengenharia & Sistemas Informáticos - Prestação de serviço, LDA
            </h6>
            <h6 class="mt-2">
                Contacto de suporte: <strong>+244 974 507 034</strong>
            </h6>
        </div>
    </div>

    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    {{-- sweetalert2 --}}
    <script src="{{ asset('dist/js/sweetalert2@11.js') }}"></script>
    
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

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
    
                        showMessage('Sucesso!', 'Seja bem vindo ao sistema!', 'success');
    
                        window.location.href = response.redirect;
    
    
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
                
        function progressBeforeSend(title = "Processando...", text = "Por favor, aguarde.", icon = 'info' ) {
            Swal.fire({
              title: title,
              text: text,
              icon: icon,
              allowOutsideClick: false,
              showConfirmButton: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
        }
    
        function showMessage(title, text, icon) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
            });
        }
    
        function showProgressModal() {
            const modal = document.getElementById("loading-modal");
            modal.style.display = "flex";
            modal.style.zIndex = "999999"; // Corrigido: zIndex ao invés de z-index
        }
    
        function hideProgressModal() {
            const modal = document.getElementById("loading-modal");
            modal.style.display = "none";
            modal.style.zIndex = "-2"; // Corrigido: zIndex ao invés de z-index
        }
        
    
        $(function() {

            $('#tipo_empresa').on('change', function(e) {
                e.preventDefault();

                var forma_pagamento = $('#tipo_empresa').val();

                if (forma_pagamento == "Juridica") {

                    document.getElementById("tipo_negocio").disabled = false;
                    document.getElementById("nome_empresa").disabled = false;

                    $("#campo_nome_empresa").css({
                        "display": "block"
                    });
                    $("#campo_tipo_negocio").css({
                        "display": "block"
                    });


                } else if (forma_pagamento == "Fisica") {

                    document.getElementById("tipo_negocio").disabled = true;
                    document.getElementById("nome_empresa").disabled = true;

                    $("#campo_nome_empresa").css({
                        "display": "none"
                    });
                    $("#campo_tipo_negocio").css({
                        "display": "none"
                    });
                }
            })
        });

    </script>
</body>

</html>
