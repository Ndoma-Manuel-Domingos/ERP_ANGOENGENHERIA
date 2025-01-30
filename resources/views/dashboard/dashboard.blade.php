@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
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
            
            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Hotelaria"))
                <div class="row">
                           
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-success" title="Hospedes">
                            <div class="inner">
                                <h3>{{ number_format($totalCliente, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Hospedes</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('clientes.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                           
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info" title="Reservas">
                            <div class="inner">
                                <h3>{{ number_format($totalReservas, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Reservas</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('reservas.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
              
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-warning" title="Quartos">
                            <div class="inner">
                                <h3>{{ number_format($totalQuarto, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Quartos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('quartos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
              
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-success" title="Tarifários">
                            <div class="inner">
                                <h3>{{ number_format($totalTarifarios, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Tarifários</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('tarefarios.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                                
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-success" title="Check In Diário">
                            <div class="inner">
                                <h3>{{ number_format($totalReservasCheckIn, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Check In Diário</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('reservas.check_in_diario') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>  
                                
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-danger" title="Check Out Diário">
                            <div class="inner">
                                <h3>{{ number_format($totalReservasCheckOut, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Check Out Diário</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('reservas.check_out_diario') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>  
                               
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-warning" title="Restaurante & Bar">
                            <div class="inner">
                                <h3>::</h3>
                                <p class="text-uppercase">Restaurante & Bar</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('painel.escolha') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div> 
                              
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-primary" title="Pedidos aos Quartos">
                            <div class="inner">
                                <h3>::</h3>
                                <p class="text-uppercase">Pedidos aos Quartos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('pronto-venda-quartos') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>  
    
                </div>
            @endif
        
            @if ($tipo_entidade_logado->empresa->tem_permissao("Tabela Apoio Centro Formação"))
                <div class="row">
                           
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_solicitacao, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Solicitações</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('solicitacoes-documentos') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                           
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_alunos, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Alunos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('alunos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                           
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_formadores, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Formadores</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('formadores.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
              
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_anos_lectivos, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Anos Lectivos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('anos-lectivos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                                
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_salas, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Salas</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('salas.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
         
                                
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_turnos, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Turnos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('turnos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
               
                                
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_cursos, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Cursos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('cursos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                         
                    <div class="col-lg-3 col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_turmas, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Turmas</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('turmas.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
         
                </div>
            @endif
                    
            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Consultas"))
                <div class="row">
                                
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($total_pendentes, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Agendamento Pendente</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('agendamentos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
    
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ number_format($total_expirados, 0, ',', '.')  }}</h3>
                                <p class="text-uppercase">Agendamento Expirados</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('agendamentos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    
                    <div class="col-lg-3 col-md-3 col-12">
                    
                        <div class="small-box bg-warning" title="Quantidade Produtos Em Stock">
                            <div class="inner">
                                <h3>{{ $total_cancelados }}</h3>
                                <p class="text-uppercase">Agendamentos Cancelados</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('agendamentos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $total_atendidos }}</h3>
                                <p class="text-uppercase">Agendamentos Atendidos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('agendamentos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
    
                </div>
            @endif
            
            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
                <div class="row">
                    
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($vendas->total_quantidade, 2, ',', '.')  }}</h3>
                                <p class="text-uppercase">Quantidade de Produtos vendidos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('vendas') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
    
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($vendas->total_vendas, 2, ',', '.')  }}</h3>
                                <p class="text-uppercase">Valor acumulado em vendas</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('vendas') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    
                    <div class="col-lg-3 col-md-3 col-12">
                    
                        <div class="small-box bg-success" title="Quantidade Produtos Em Stock">
                            <div class="inner">
                                <h3>{{ $total_estoque_activo }}</h3>
                                <p class="text-uppercase">Quantidade Produtos em stock activo</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('estoques-produtos', ['status' => 'activo']) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $total_estoque_expirado }}</h3>
                                <p class="text-uppercase">Produtos em stock expirados</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('estoques-produtos', ['status' => 'expirado']) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
    
                </div>
            @endif
            
            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Stock"))  
                <div class="row">
                    
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($vendas->total_quantidade, 2, ',', '.')  }}</h3>
                                <p class="text-uppercase">Quantidade de Produtos Consumidos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('vendas') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
    
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($vendas->total_vendas, 2, ',', '.')  }}</h3>
                                <p class="text-uppercase">Valor acumulado de produtos Consumidos</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('vendas') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    
                    <div class="col-lg-3 col-md-3 col-12">
                    
                        <div class="small-box bg-success" title="Quantidade Produtos Em Stock">
                            <div class="inner">
                                <h3>{{ $total_estoque_activo }}</h3>
                                <p class="text-uppercase">Quantidade Produtos em stock activo</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('estoques-produtos', ['status' => 'activo']) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-12">
    
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $total_estoque_expirado }}</h3>
                                <p class="text-uppercase">Produtos em stock expirados</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('estoques-produtos', ['status' => 'expirado']) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
    
                </div>
            @endif
            
            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Produto"))
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="grafico" width="100" height="55" aria-label="Hello ARIA World" role="img"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <div class="row">
                            
                            <div class="col-12 col-md-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
    
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{ $total_produtos }}</h3>
                                                <p class="text-uppercase">Total Produtos</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-bag"></i>
                                            </div>
                                            <a href="{{ route('produtos.index', ['tipo' => 'P']) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                    
                                    <div class="col-lg-6 col-md-6 col-12">
                    
                                        <div class="small-box bg-warning" title="Quantidade Produtos Em Stock">
                                            <div class="inner">
                                                <h3>{{ $total_servicos }}</h3>
                                                <p class="text-uppercase">Total de Serviços</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-stats-bars"></i>
                                            </div>
                                            <a href="{{ route('produtos.index', ['tipo' => 'S']) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <canvas id="grafico_servicos" width="100" height="36" aria-label="Hello ARIA World" role="img"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if ($tipo_entidade_logado->empresa->tem_permissao("Gestão de Stock") || $tipo_entidade_logado->empresa->tem_permissao("Gestão Vendas"))
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h5 class="m-0">Listagem de 5 produtos com o Stock acima dos vinte(20) items.</h5>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap">
                                    <thead class="">
                                        <tr>
                                            <th>Codigo Barra</th>
                                            <th>Descrição do Activo</th>
                                            <th>Categoria</th>
                                            <th>Marca</th>
                                            <th class="text-right">Quantidade</th>
                                            <th class="text-right">P.unit</th>
                                            <th class="text-right">Taxa Iva</th>
                                            <th class="text-right">Controle Lote</th>
                                            <th class="text-right">Acções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produtos as $item)
                                        <tr>
                                            <td>{{ $item->codigo_barra }}</td>
                                            <td>{{ $item->nome }}</td>
                                            <td>{{ $item->categoria->categoria }}</td>
                                            <td>{{ $item->marca->nome }}</td>
                                            <td class="text-right">{{ number_format($item->quantidade_sum_quantidade, 1, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->preco_venda, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></td>
                                            <td class="text-right">{{ number_format(($item->taxa), 1, ',', '.') }} <small>%</small></td>
                                            <td class="text-right">{{ $item->lote_valicidade }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('produtos.show', $item->id) }}" class="btn btn-sm btn-info mx-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
    
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
    
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <h5 class="m-0">Listagem de 5 produtos com o Stock abaixo dos vinte(20) items.</h5>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap">
                                    <thead class="">
                                        <tr>
                                            <th>Codigo Barra</th>
                                            <th>Descrição do Activo</th>
                                            <th>Categoria</th>
                                            <th>Marca</th>
                                            <th class="text-right">Quantidade</th>
                                            <th class="text-right">P.unit</th>
                                            <th class="text-right">Taxa Iva</th>
                                            <th class="text-right">Controle Lote</th>
                                            <th class="text-right">Acções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produtos_abaixo as $item)
                                        <tr>
                                            <td>{{ $item->codigo_barra }}</td>
                                            <td>{{ $item->nome }}</td>
                                            <td>{{ $item->categoria->categoria }}</td>
                                            <td>{{ $item->marca->nome }}</td>
                                            <td class="text-right">{{ number_format($item->quantidade_sum_quantidade, 1, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->preco_venda, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></td>
                                            <td class="text-right">{{ number_format(($item->taxa), 1, ',', '.') }} <small>%</small></td>
                                            <td class="text-right">{{ $item->lote_valicidade }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('produtos.show', $item->id) }}" class="btn btn-sm btn-info mx-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
    
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
    
                </div>
            @endif

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    var ctx = document.getElementById('grafico').getContext('2d');
    var ctx_servicos = document.getElementById('grafico_servicos').getContext('2d');
   
    var produtos = <?php echo json_encode($produtosMaisVendidos); ?>;
    var servicos = <?php echo json_encode($SevicosMaisPrestados); ?>;
    
    var labels = [];
    var valores = [];
    
    var labels_servico = [];
    var valores_servico = [];

    produtos.forEach(function(item) {
        labels.push(item.produto.nome); // Supondo que o nome do produto esteja em 'nome' dentro do relacionamento 'produto'
        valores.push(item.total_quantidade);
    });
    
    servicos.forEach(function(item) {
        labels_servico.push(item.produto.nome); // Supondo que o nome do produto esteja em 'nome' dentro do relacionamento 'produto'
        valores_servico.push(item.total_quantidade);
    });

    var chart = new Chart(ctx, {
        type: 'line'
        , data: {
            labels: labels
            , datasets: [{
                label: 'Produtos Mais Vendidos nos ultimos 30 dias'
                , data: valores
                , borderWidth: 1
            }]
        }
        , options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },            
            layout: {
                padding: 10
            }
        }
    });
    
    var chart_servico = new Chart(ctx_servicos, {
        type: 'line'
        , data: {
            labels: labels_servico
            , datasets: [{
                label: 'Serviços Mais Prestados nos ultimos 30 dias'
                , data: valores_servico
                , borderWidth: 1
            }]
        }
        , options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },            
            layout: {
                padding: 10
            }
        }
    });

</script>
@endsection
