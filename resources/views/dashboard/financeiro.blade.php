@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Painel Financeiro</h1>
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
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($contasReceberAtraso, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Contas a Receber em atraso</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('transacoes-financeiras', ['type' => "contas_receber_atraso"]) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($contasReceberMes, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Contas a Receber em aberto para esta mês</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('transacoes-financeiras', ['relatorio' => "contas_receber_mes"]) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($contasPagarAtraso, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Contas a Pagar em atraso</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('transacoes-financeiras', ['relatorio' => "contas_pagar_atraso"]) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($contasPagarMes, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Contas a Pagar em aberto para este mês</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('transacoes-financeiras', ['relatorio' => "contas_pagar_mes"]) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-info" title="Quantidade Produtos Em Stock">
                        <div class="inner">
                            <h3>{{ number_format($saldoAtual, 0, ',', '.')  }}</h3>
                            <p class="text-uppercase">Saldo Actual</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('transacoes-financeiras', ['relatorio' => ""]) }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h4">Visão Financeira</h1>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoAnual" width="800" height="200"></canvas>
                        </div>
                        <div class="card-footer text-center">
                            <h5>Totais Anuais</h5>
                            <div class="row">
                                <div class="col-12 col-md-4"></div>
                                <div class="col-12 col-md-4">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Receita</th>
                                                <th>Despesa</th>
                                                <th>Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="total-receita" style="background-color: rgba(75, 192, 192, 0.6)" class="text-success">0.00</td>
                                                <td id="total-despesa" style="background-color: rgba(255, 99, 132, 0.6)" class="text-danger">0.00</td>
                                                <td id="total-saldo" style="background-color: rgba(153, 102, 255, 0.6)" class="text-dark">0.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h4">Receitas por Plano de Conta</h1>
                        </div>
                        <div class="card-body p-5">
                            <canvas id="graficoReceitas" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h4">Dispesas por Plano de Conta</h1>
                        </div>
                        <div class="card-body p-5">
                            <canvas id="graficoDispesas" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="m-0 text-center h4">Evolução dos saldos Finais</h1>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoSaldos" width="800" height="200"></canvas>
                        </div>
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
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoAnual').getContext('2d');
        fetch('{{ route("operacaoes-financeiras.grafico-anual") }}')
            .then(response => response.json())
            .then(data => {
                const meses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
                const receitas = [];
                const despesas = [];
                const saldos = [];

                for (let mes = 1; mes <= 12; mes++) {
                    receitas.push(data.mensal[mes]?.receita || 0);
                    despesas.push(data.mensal[mes]?.despesa || 0);
                    saldos.push(data.mensal[mes]?.saldo || 0);
                }
                
                // Totais anuais
                const totalReceita = data.totais.receita;
                const totalDespesa = data.totais.despesa;
                const totalSaldo = data.totais.saldo;
                
                
                 // Exibindo Totais no HTML
                document.getElementById('total-receita').innerText = totalReceita.toFixed(1);
                document.getElementById('total-despesa').innerText = totalDespesa.toFixed(1);
                document.getElementById('total-saldo').innerText = totalSaldo.toFixed(1);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: meses,
                        datasets: [
                            {
                                label: 'Receita',
                                data: receitas,
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            },
                            {
                                label: 'Despesa',
                                data: despesas,
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            },
                            {
                                label: 'Saldo',
                                data: saldos,
                                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        scales: {
                            x: {
                                stacked: false,
                            },
                            y: {
                                stacked: false,
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoReceitas').getContext('2d');

        fetch('{{ route("operacaoes-financeiras.grafico-receitas") }}')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.nome);
                const valores = data.map(item => item.total);

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Receitas',
                            data: valores,
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 205, 86, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(201, 203, 207, 0.6)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 205, 86, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(201, 203, 207, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        }
                    }
                });
            });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoDispesas').getContext('2d');

        fetch('{{ route("operacaoes-financeiras.grafico-despesas") }}')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.nome);
                const valores = data.map(item => item.total);

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Dispesas',
                            data: valores,
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(255, 205, 86, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(201, 203, 207, 0.6)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 205, 86, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(201, 203, 207, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        }
                    }
                });
            });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ctx = document.getElementById('graficoSaldos').getContext('2d');

        fetch('{{ route("operacaoes-financeiras.grafico-saldos") }}')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => `Mês ${item.mes}`);
                const saldos = data.map(item => item.saldo);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Saldo Mensal',
                            data: saldos,
                            backgroundColor: 'rgba(54, 162, 235, 0.4)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Saldo (AOA)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Meses'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return `Saldo: AOA ${context.raw.toFixed(2)}`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
    });
</script>

@endsection
