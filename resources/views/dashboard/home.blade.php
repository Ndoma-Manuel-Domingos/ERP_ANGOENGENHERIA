@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1 class="m-0">Painel Administrativo</h1> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Painel Principal</a></li>
                        <li class="breadcrumb-item active">Configurações</li>
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
                <div class="col-md-9 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h3 mb-3">Olá {{ Auth::user()->name }}, Seja Bem-vindo ao {{ env('APP_NAME') }}</h2>
                            <h3 class="h5">Vamos ajudar a configurar a sua Conta empresarial</h3>
                        </div>
                        <div class="card-footer">
                            <p>A {{ env('APP_NAME') }} proporciona uma experiência aprazível, através da funcionalidade do Software de gestão e faturação{{ env('APP_NAME') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>ABRIAR O CAIXA</h3>
                            <p class="text-uppercase">Abra o caixa e comece suas vendas agora mesmo!</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('caixa.caixas') }}" class="small-box-footer py-4">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- /.row -->
            </div>

            <div class="row">
                <div class="col-12 col-md-3">

                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h1><i class="fa fa-check-circle text-success"></i></h1>
                            <h4>IDENTIFICAÇÃO E ACTIVIDADES</h4>
                            <p>
                                Adicionar o nome, e o NIF ( NÚMERO DE IDENTIFICAÇÃO FISCAL ) da empresa, Informar o tipo de negócio, confirmar as definições de privacidade.
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <a href="{{ route('identidade-empresa.index') }}" class="btn btn-primary d-block my-4">Actualizar</a>

                            {{-- <p>A {{ env('APP_NAME') }} proporciona uma experiência aprazível, através da funcionalidade do Software de gestão e faturação {{ env('APP_NAME') }} </p> --}}
                        </div>
                        <div class="card-footer"></div>
                    </div>

                </div>

                <div class="col-12 col-md-3">

                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h1><i class="fa fa-check-circle text-success"></i></h1>
                            <h4>INFORMAÇÕES DA EMPRESA</h4>
                            <p>
                                Adicionar o endereço da empresa, registo e conservatória, capital social da empresa, informações comercial e contacto gerais da empresa.
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <a href="{{ route('dados-empresa.index') }}" class="btn btn-primary d-block my-4">Actualizar</a>

                            {{-- <p>A {{ env('APP_NAME') }} proporciona uma experiência aprazível, através da funcionalidade do Software de gestão e faturação {{ env('APP_NAME') }}</p> --}}
                        </div>
                        <div class="card-footer"></div>
                    </div>

                </div>

                <div class="col-12 col-md-3">

                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h1><i class="fa fa-check-circle text-success"></i></h1>
                            <h4>CONFIGURAÇÃO DE IMPRESSÃO</h4>
                            <p>
                                Confirmar o tipo de dispositivo para a impressão, Confirmação do estado da impressora, método de impressão.
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <a href="{{ route('configurar-empressora.index') }}" class="btn btn-primary d-block my-4">Actualizar</a>

                            {{-- <p>A {{ env('APP_NAME') }} proporciona uma experiência aprazível, através da funcionalidade do Software de gestão e faturação {{ env('APP_NAME') }}</p> --}}
                        </div>
                        <div class="card-footer"></div>
                    </div>

                </div>

                <div class="col-12 col-md-3">

                    <div class="card">
                        <div class="card-header text-center py-4">
                            <h1><i class="fa fa-check-circle text-success"></i></h1>
                            <h4>PERSONALIZAR IMPRESSÃO</h4> <br>
                            <p>
                                Logotipo, contactos, e-mail, dados bancários,<br>endereço, e slogan.
                            </p>
                        </div>

                        <div class="card-body text-center">
                            <a href="{{ route('personalizar-empressora.index') }}" class="btn btn-primary d-block my-4">Actualizar</a>

                            {{-- <p>A {{ env('APP_NAME') }} proporciona uma experiência aprazível, através da funcionalidade do Software de gestão e faturação {{ env('APP_NAME') }}</p> --}}
                        </div>
                        <div class="card-footer"></div>
                    </div>

                </div>
            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
