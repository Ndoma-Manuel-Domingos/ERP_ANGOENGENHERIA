@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe Contrato</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('contratos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Contrato</li>
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
                    <div class="card">
                        <div class="card-header">
                            <h6>Dados do Contrato</h6>
                        </div>
                        <div class="card-body">
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

                                <div class="col-12 col-md-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Contrato Nº </th>
                                                <td class="text-right">{{ $contrato->numero ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Tipo Contrato</th>
                                                <td class="text-right">{{ $contrato->tipo_contrato->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Cargo</th>
                                                <td class="text-right"> {{ $contrato->cargo->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Categoria</th>
                                                <td class="text-right"> {{ $contrato->categoria->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Data Inicio & Data Final </th>
                                                <td class="text-right">{{ $contrato->data_inicio ?? '-------------' }} - {{ $contrato->data_final ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Hora Entrada & Saída </th>
                                                <td class="text-right">{{ $contrato->hora_entrada ?? '-------------' }} - {{ $contrato->hora_saida ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Estado Contrato</th>
                                                <td class="text-right"> {{ $contrato->status ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Salário Base</th>
                                                <td class="text-right"> {{ number_format($contrato->salario_base ?? 0, 2, ',', '.' ) ?? '-------------' }} AOA</td>
                                            </tr>
                                            <tr>
                                                <th>Forma Pagamento</th>
                                                <td class="text-right"> {{ $contrato->forma_pagamento->titulo ?? "" }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Subsídio de Natal</th>
                                                <th>Subsídio de Ferias</th>
                                            </tr>

                                            <tr>
                                                <td class="text-left">{{ number_format($contrato->subsidio_natal ?? 0, 1, ',', '.' ) }} % => {{ number_format(($contrato->salario_base * ($contrato->subsidio_natal / 100)) ?? 0, 2, ',', '.' ) ?? '-------------' }} AOA</td>
                                                <td class="text-left">{{ number_format($contrato->subsidio_ferias ?? 0, 1, ',', '.' ) }} % => {{ number_format(($contrato->salario_base * ($contrato->subsidio_ferias / 100)) ?? 0, 2, ',', '.' ) ?? '-------------' }} AOA</td>
                                            </tr>
                                            
                                            <tr>
                                                <th class="text-left" colspan="2">Mês Pagamento & Forma Pagamento</th>
                                            </tr>
                                            
                                            <tr>
                                                <td class="text-left">{{ $contrato->descricao_mes($contrato->mes_pagamento_natal) }} - {{ $contrato->forma_pagamento_subcidio($contrato->forma_pagamento_natal) }}</td>
                                                <td class="text-left">{{ $contrato->descricao_mes($contrato->mes_pagamento_ferias) }} - {{ $contrato->forma_pagamento_subcidio($contrato->forma_pagamento_ferias) }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <th>Dias Processamentos:</th>
                                                <td class="text-right">{{ $contrato->dias_processamentos($contrato->dias_processamento) }}</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th> OUTROS SUBSÍDIOS</th>
                                                <th class="text-right"> SUJEITO A INSS</th>
                                                <th class="text-right"> SUJEITO A IRT</th>
                                                <th class="text-right"> TIPO PROCESSAMENTO</th>
                                                <th class="text-right"> LIMITE ISENÇÃO</th>
                                                <th class="text-right"> VALOR</th>
                                            </tr>
                                            @foreach ($contrato->subsidios_contrato as $item)
                                                <tr>
                                                    <th>{{ $item->subsidio->nome ?? "" }}</th>
                                                    <th class="text-right">{{ $item->inss == "Y" ? "SIM" : "NÃO" }}</th>
                                                    <th class="text-right">{{ $item->irt == "Y" ? "SIM" : "NÃO" }}</th>
                                                    <th class="text-right">{{ $item->processamento->nome ?? "" }}</th>
                                                    <td class="text-right">{{ number_format($item->limite_isencao ?? 0, 1, ',', '.' ) ?? 0 }} - AOA</td>
                                                    <td class="text-right">{{ number_format($item->salario ?? 0, 1, ',', '.' ) ?? 0 }} - AOA</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th> DESCONTOS</th>
                                                <th class="text-right"> SUJEITO A INSS</th>
                                                <th class="text-right"> SUJEITO A IRT</th>
                                                <th class="text-right"> TIPO PROCESSAMENTO</th>
                                                <th class="text-right"> VALOR</th>
                                            </tr>
                                            @foreach ($contrato->descontos_contrato as $item)
                                                <tr>
                                                    <th>{{ $item->desconto->nome ?? "" }}</th>
                                                    <th class="text-right">{{ $item->inss == "Y" ? "SIM" : "NÃO" }}</th>
                                                    <th class="text-right">{{ $item->irt == "Y" ? "SIM" : "NÃO" }}</th>
                                                    <th class="text-right">{{ $item->processamento->nome ?? "" }}</th>
                                                    <td class="text-right">{{ number_format($item->salario ?? 0, 1, ',', '.' ) ?? 0 }}{{ $item->tipo_valor == "P" ? '%' : " - AOA" }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Dados Pessoais</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                              
                                <div class="col-12 col-md-4">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Nome</th>
                                                <td class="text-right">{{ $contrato->funcionario->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Gênero</th>
                                                <td class="text-right">{{ $contrato->funcionario->genero ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Data Nascimento</th>
                                                <td class="text-right"> {{ $contrato->funcionario->data_nascimento ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-4">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>Estado Cívil</th>
                                                <td class="text-right">{{ $contrato->funcionario->estado_civil->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Nome da Mãe</th>
                                                <td class="text-right">{{ $contrato->funcionario->nome_da_mae ?? '-------------' }} </td>
                                            </tr>

                                            <tr>
                                                <th>Seguradora</th>
                                                <td class="text-right">{{ $contrato->funcionario->seguradora->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-4">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>País</th>
                                                <td class="text-right">{{ $contrato->funcionario->pais ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Estado Cívil</th>
                                                <td class="text-right">
                                                    {{ $contrato->funcionario->estado_civil->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>NIF/Bilhete</th>
                                                <td class="text-right">{{ $contrato->funcionario->nif ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Morada</th>
                                                <th>Províncias</th>
                                                <th>Município</th>
                                                <th>Distrito</th>
                                            </tr>
                                            <tr>
                                                <td>{{ $contrato->funcionario->morada ?? '-------------' }}
                                                    <br>{{ $contrato->funcionario->codigo_postal ?? '-------------' }}
                                                </td>
                                                <td>{{ $contrato->funcionario->provincia->nome ?? '-------------' }}</td>
                                                <td>{{ $contrato->funcionario->municipio->nome ?? '-------------' }}</td>
                                                <td>{{ $contrato->funcionario->distrito->nome ?? '-------------' }}</td>
                                            </tr>
                                            {{-- -------------------------------------------- --}}
                                            <tr>
                                                <th colspan="4">Contactos</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Telefone</td>
                                                <td colspan="2">Telemóvel</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ $contrato->funcionario->telefone ?? '-------------' }}</td>
                                                <td colspan="2">{{ $contrato->funcionario->telemovel ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">E-mail</td>
                                                <td colspan="2">Website</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{ $contrato->funcionario->email ?? '-------------' }}</td>
                                                <td colspan="2">{{ $contrato->funcionario->website ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
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
