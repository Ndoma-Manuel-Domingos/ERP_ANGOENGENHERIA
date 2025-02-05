<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? '' }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
            height: 100vh; /* Ajuste conforme necessário */
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
            filter: blur(5px); /* Ajuste o nível de desfoque aqui */
            z-index: -1; /* Certifique-se de que o fundo fique atrás do conteúdo */
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
    
    <div class="loading-modal d-flex" id="loading-modal">
        <div class="spinner"></div>
    </div>

    <div class="login-box">    
        <div class="card card-outline card-dark">
            <div class="card-body py-5">
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

                <form action="{{ route('check') }}" method="post" class="pt-3">
                    @csrf
                    <div class="col-12 mb-3">
                        <label for="email" class="form-label">Usuário: <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') ?? '' }}" placeholder="E-mail">
                        </div>
                        <p class="text-danger">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="password" class="form-label">Senha: <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg" id="password" name="password" value="{{ old('password') ?? '' }}" placeholder="Senha">
                        </div>
                        <p class="text-danger">
                            @error('password')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn-lg btn-dark btn-block">Acessar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                
            </div>
            <div class="card-footer">
                <p class="mb-0 mt-4 text-right">
                    <a href="{{ route('register') }}" class="text-right h4">Já tens uma conta?</a> <br>
                    <a href="{{ route('update_pass') }}" class="text-left text-dark h5">Redefinir minha senha.</a>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 col-md-12 text-center text-dark mt-3">
            <h3>"Bem-vindo de volta!"</h3>
            <h6>
                Cada acesso é um passo em direção ao sucesso. Aproveite o sistema para alcançar seus objetivos e tornar seus dias mais produtivos. <br>
            </h6>
            <h6>
                <strong>Lembre-se:</strong> grandes conquistas começam com pequenas ações. Vamos construir o futuro juntos!
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

    {{-- sweetalert2 --}}
    <script src="{{ asset('dist/js/sweetalert2@11.js') }}"></script>
    
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
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
    
                        // window.location.reload();
    
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
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });

    </script>
</body>

</html>