@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Painel Recursos Humanos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-principal') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
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
                      
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Configuração Recursos Humanos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('configuracao-recurso-humanos') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_funcionarios ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Total Funcionários</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('funcionarios.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_contratos ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Contratos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('contratos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
          
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_contratos_renovados ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Renovação de Contratos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('renovacoes-contratos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format(0 ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Fins de Contratos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('contratos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format(0 ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Readmissão de Contratos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('contratos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">

                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_departamentos ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Departamentos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('departamentos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Cargos">
                        <div class="inner">
                            <h3>{{ number_format($total_cargos ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Cargos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('cargos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Pacotes Salarial">
                        <div class="inner">
                            <h3>{{ number_format($total_pacotes, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Pacotes Salarial</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('pacotes-salarial.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Processamentos">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Processamentos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('processamentos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Pagamentos de Processamentos">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Pagamentos de Processamentos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('pagamentos-processamentos') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Anulação de Processamentos">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Anulação de Processamentos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('anulacao-processamentos') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Emissão de Recibos">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Emissão de Recibos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('emissao-recibo-processamentos') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Marcar Ferias">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Marcar Ferias</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('marcacoes-ferias.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                   
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Marcações de Faltas">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Marcações de Faltas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('marcacoes-faltas.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                   
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Marcações de Ausências">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Marcações de Ausências</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('marcacoes-ausencias.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light" title="Taxas do Imposto de Rendimento de Trabalho">
                        <div class="inner">
                            <h3>.</h3>
                            <p class="text-uppercase">Taxas do IRT</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('taxa_irt') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_motivos_saidas ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Motivos de Saída de Funcionários</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('motivos-saidas.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ number_format($total_motivos_ausencias ?? 0, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Motivos de Ausência de Funcionários</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('motivos-ausencias.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                

            </div>
    
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
