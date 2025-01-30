@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Balanço Inicial</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Voltar</a></li>
                        <li class="breadcrumb-item active">Balanço Inicial</li>
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
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('contabilidade-balanco-inicial-novo') }}" class="btn-primary btn-sm">Novo Balanço Inicial</a>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap" id="carregar_tabela"  style="width: 100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center">Exercícios</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">Designação</th>
                                        <th class="text-right">2xxx</th>
                                        <th class="text-right">2xxx-1</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr>
                                        <th colspan="3" class="text-uppercase">Activo</th>
                                    </tr>
                                    {{-- Activos não correcte --}}
                                    <tr>
                                        <th colspan="3">Activos não correntes:</th>
                                    </tr>
                                  
                                    <tr>
                                        <td colspan="3" class="pl-5">11 - Imobilizações corpóreas</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $meios_fixos_investimento_classe1,
                                    ])
                                  
                                    <tr>
                                        <td colspan="3" class="pl-5">12 - Imobilizações incorpóreas</td>
                                    </tr>
                                     
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $meios_fixos_investimento_classe2,
                                    ])
                                  
                                    <tr>
                                        <td colspan="3" class="pl-5">13 - Investimentos em subsidiárias e associadas</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $meios_fixos_investimento_classe3,
                                    ])
                                    
                                    <tr>
                                        <td colspan="3" class="pl-5">Outros activos financeiros</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $meios_fixos_investimento_financeiros,
                                    ])
                                  
                                    <tr>
                                        <td colspan="3" class="pl-5">Outros activos não correntes</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $meios_fixos_investimento_outros_activos_nao_correntes,
                                    ])
                                   
                                    <tr>
                                        <th class="text-uppercase">SUBTOTAL</th>
                                        <th class="text-right">
                                            @php $saldo_fina_activos_nao_corrente = 0; @endphp
                                            @if ($saldos_activos_nao_correntes['debito'] > $saldos_activos_nao_correntes['credito'])
                                                @php $saldo_fina_activos_nao_corrente = $saldos_activos_nao_correntes['debito'] - $saldos_activos_nao_correntes['credito']; @endphp
                                                {{ number_format($saldo_fina_activos_nao_corrente, 2, ',', '.') }}
                                            @else
                                                @if ($saldos_activos_nao_correntes['credito'] > $saldos_activos_nao_correntes['debito'])
                                                @php $saldo_fina_activos_nao_corrente = $saldos_activos_nao_correntes['credito'] - $saldos_activos_nao_correntes['debito']; @endphp
                                                {{ number_format($saldos_activos_nao_correntes['credito'] - $saldos_activos_nao_correntes['debito'], 2, ',', '.') }}
                                                @else
                                                @php $saldo_fina_activos_nao_corrente = 0 @endphp
                                                @endif
                                            @endif
                                        </th>
                                        <th class="text-right">0</th>
                                    </tr> 
                                    {{-- Activos correcte--}}
                                    <tr>
                                        <th colspan="3">Activos correntes:</th>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="3" class="pl-5">21 - Existências</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $activo_corrente_existencias,
                                    ])
                                    
                                    <tr>
                                        <td colspan="3" class="pl-5">31/35/37 - Contas a receber</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $activo_corrente_terceiros,
                                    ])
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $activo_corrente_contas_receber,
                                    ])
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $activo_corrente_contas_receber_2,
                                    ])
                                    
                                    <tr>
                                        <td colspan="3" class="pl-5">43/45 - Disponibilidades</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $activo_corrente_disponibilidade,
                                    ])
                                  
                                    <tr>
                                        <th class="text-uppercase">SUBTOTAL</th>   
                                        <th class="text-right">
                                            @php $saldo_fina_activos_corrente = 0; @endphp
                                            @if ($saldos_activos_correntes['debito'] > $saldos_activos_correntes['credito'])
                                                @php $saldo_fina_activos_corrente = $saldos_activos_correntes['debito'] - $saldos_activos_correntes['credito']; @endphp
                                                {{ number_format($saldo_fina_activos_corrente, 2, ',', '.') }}
                                            @else
                                                @if ($saldos_activos_correntes['credito'] > $saldos_activos_correntes['debito'])
                                                @php $saldo_fina_activos_corrente = $saldos_activos_correntes['credito'] - $saldos_activos_correntes['debito']; @endphp
                                                {{ number_format($saldos_activos_correntes['credito'] - $saldos_activos_correntes['debito'], 2, ',', '.') }}
                                                @else
                                                @php $saldo_fina_activos_corrente = 0 @endphp
                                                @endif
                                            @endif
                                        </th>
                                        <th class="text-right">0</th>
                                    </tr>
                                    
                                    <tr>
                                        <th class="text-uppercase">TOTAL DOS ACTIVOS</th>
                                        <th class="text-right">{{ number_format($saldo_fina_activos_nao_corrente + $saldo_fina_activos_corrente, 2, ',', '.') }}</th>
                                        <th class="text-right">0</th>
                                    </tr>
                                    {{-- capital proprio --}}
                                    <tr>
                                        <th colspan="3" class="text-uppercase">Capital Próprio e Passivo</th>
                                    </tr>
                                    <tr>
                                        <td class="pl-5" colspan="3">51 - Capital</td>
                                    </tr>
                                    
                                    @php $saldo_final_capital_social = 0; @endphp
                                    @if ($saldo_capital_social['debito'] > $saldo_capital_social['credito'])
                                        @php $saldo_final_capital_social = $saldo_capital_social['debito'] - $saldo_capital_social['credito']; @endphp
                                    @else
                                        @if ($saldo_capital_social['credito'] > $saldo_capital_social['debito'])
                                        @php $saldo_final_capital_social = $saldo_capital_social['credito'] - $saldo_capital_social['debito']; @endphp
                                        @else
                                        @php $saldo_final_capital_social = 0 @endphp
                                        @endif
                                    @endif
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $contas_capital_social,
                                    ])
                                    <tr>
                                        <td class="pl-5" colspan="3">55 - Reservas</td>
                                    </tr>
                                    
                                    @php $saldo_final_reserva = 0; @endphp
                                    @if ($saldo_reserva_legais['debito'] > $saldo_reserva_legais['credito'])
                                        @php $saldo_final_reserva = $saldo_reserva_legais['debito'] - $saldo_reserva_legais['credito']; @endphp
                                    @else
                                        @if ($saldo_reserva_legais['credito'] > $saldo_reserva_legais['debito'])
                                        @php $saldo_final_reserva = $saldo_reserva_legais['credito'] - $saldo_reserva_legais['debito']; @endphp
                                        @else
                                        @php $saldo_final_reserva = 0 @endphp
                                        @endif
                                    @endif
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $contas_reserva_legais,
                                    ])
                                    <tr>
                                        <td class="pl-5" colspan="3">81 - Resultados transitados</td>
                                    </tr>
                                    
                                    @php $saldo_final_resultado_transitado = 0; @endphp
                                    @if ($saldo_resultado_transitados['debito'] > $saldo_resultado_transitados['credito'])
                                        @php $saldo_final_resultado_transitado = $saldo_resultado_transitados['debito'] - $saldo_resultado_transitados['credito']; @endphp
                                    @else
                                        @if ($saldo_resultado_transitados['credito'] > $saldo_resultado_transitados['debito'])
                                        @php $saldo_final_resultado_transitado = $saldo_resultado_transitados['credito'] - $saldo_resultado_transitados['debito']; @endphp
                                        @else
                                        @php $saldo_final_resultado_transitado = 0 @endphp
                                        @endif
                                    @endif
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $contas_resultado_transitados,
                                    ])
                                    <tr>
                                        <td class="pl-5" colspan="3">88 - Resultados do exercício</td>
                                    </tr> 
                                    
                                    @php $saldo_final_liquido_exercicios = 0; @endphp
                                    @if ($saldo_resultado_liquido_exercicios['debito'] > $saldo_resultado_liquido_exercicios['credito'])
                                        @php $saldo_final_liquido_exercicios = $saldo_resultado_liquido_exercicios['debito'] - $saldo_resultado_liquido_exercicios['credito']; @endphp
                                    @else
                                        @if ($saldo_resultado_liquido_exercicios['credito'] > $saldo_resultado_liquido_exercicios['debito'])
                                        @php $saldo_final_liquido_exercicios = $saldo_resultado_liquido_exercicios['credito'] - $saldo_resultado_liquido_exercicios['debito']; @endphp
                                        @else
                                        @php $saldo_final_liquido_exercicios = 0 @endphp
                                        @endif
                                    @endif
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $contas_resultado_liquido_exercicios,
                                    ])
                                    {{-- capital proprio --}}
                                    <tr>
                                        <th class="text-uppercase">CAPITAL PROPRIO</th>
                                        <th class="text-right">{{ number_format($saldo_final_capital_social + $saldo_final_reserva + $saldo_final_resultado_transitado + $saldo_final_liquido_exercicios , 2, ',', '.') }}</th>
                                        <th class="text-right">0</th>
                                    </tr>
                                 
                                    <tr>
                                        <th colspan="3" class="text-uppercase">Passivo não corrente:</th>
                                    </tr>
                                    <tr>
                                        <td class="pl-5" colspan="3">33 - Empréstimos de médio e longo prazos</td>
                                    </tr>
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $contas_passivo_nao_corrente,
                                    ])
                                    <tr>
                                        <th class="text-uppercase">SUBTOTAL</th>   
                                        <th class="text-right">
                                            @php $saldo_final_passivo_nao_corrente = 0; @endphp
                                            @if ($saldos_passivo_nao_corrente['debito'] > $saldos_passivo_nao_corrente['credito'])
                                                @php $saldo_final_passivo_nao_corrente = $saldos_passivo_nao_corrente['debito'] - $saldos_passivo_nao_corrente['credito']; @endphp
                                                {{ number_format($saldo_final_passivo_nao_corrente, 2, ',', '.') }}
                                            @else
                                                @if ($saldos_passivo_nao_corrente['credito'] > $saldos_passivo_nao_corrente['debito'])
                                                @php $saldo_final_passivo_nao_corrente = $saldos_passivo_nao_corrente['credito'] - $saldos_passivo_nao_corrente['debito']; @endphp
                                                {{ number_format($saldos_passivo_nao_corrente['credito'] - $saldos_passivo_nao_corrente['debito'], 2, ',', '.') }}
                                                @else
                                                @php $saldo_final_passivo_nao_corrente = 0 @endphp
                                                @endif
                                            @endif
                                        </th>
                                        <th class="text-right">0</th>
                                    </tr>
                                    
                                    <tr>
                                        <th colspan="3" class="text-uppercase">Passivo corrente:</th>
                                    </tr>  
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $contas_passivo_corrente,
                                    ])
                                    
                                    @php $saldo_final_passivo_corrente_1 = 0; @endphp
                                    @if ($saldo_passivo_corrente['debito'] > $saldo_passivo_corrente['credito'])
                                        @php $saldo_final_passivo_corrente_1 = $saldo_passivo_corrente['debito'] - $saldo_passivo_corrente['credito']; @endphp
                                    @else
                                        @if ($saldo_passivo_corrente['credito'] > $saldo_passivo_corrente['debito'])
                                        @php $saldo_final_passivo_corrente_1 = $saldo_passivo_corrente['credito'] - $saldo_passivo_corrente['debito']; @endphp
                                        @else
                                        @php $saldo_final_passivo_corrente_1 = 0 @endphp
                                        @endif
                                    @endif
                                    
                                    <tr>
                                        <td class="pl-5" colspan="3">Outros valores a receber e a pagar</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $outras_contas_passivos_correntes,
                                    ])
                                    
                                    @php $saldo_final_passivo_corrente_2 = 0; @endphp
                                    @if ($saldo_outras_contas_passivos_correntes['debito'] > $saldo_outras_contas_passivos_correntes['credito'])
                                        @php $saldo_final_passivo_corrente_2 = $saldo_outras_contas_passivos_correntes['debito'] - $saldo_outras_contas_passivos_correntes['credito']; @endphp
                                    @else
                                        @if ($saldo_outras_contas_passivos_correntes['credito'] > $saldo_outras_contas_passivos_correntes['debito'])
                                        @php $saldo_final_passivo_corrente_2 = $saldo_outras_contas_passivos_correntes['credito'] - $saldo_outras_contas_passivos_correntes['debito']; @endphp
                                        @else
                                        @php $saldo_final_passivo_corrente_2 = 0 @endphp
                                        @endif
                                    @endif
                                     
                                    <tr>
                                        <td class="pl-5" colspan="3">Outros Passívos Correntes</td>
                                    </tr>
                                    
                                    @include('dashboard.contabilidade.partes.balanco-analitico', [
                                        'dados' => $outras_contas_passivos_correntes1,
                                    ])
                                    
                                    @php $saldo_final_passivo_corrente_3 = 0; @endphp
                                    @if ($saldo_outras_contas_passivos_correntes1['debito'] > $saldo_outras_contas_passivos_correntes1['credito'])
                                        @php $saldo_final_passivo_corrente_3 = $saldo_outras_contas_passivos_correntes1['debito'] - $saldo_outras_contas_passivos_correntes1['credito']; @endphp
                                    @else
                                        @if ($saldo_outras_contas_passivos_correntes1['credito'] > $saldo_outras_contas_passivos_correntes1['debito'])
                                        @php $saldo_final_passivo_corrente_3 = $saldo_outras_contas_passivos_correntes1['credito'] - $saldo_outras_contas_passivos_correntes1['debito']; @endphp
                                        @else
                                        @php $saldo_final_passivo_corrente_3 = 0 @endphp
                                        @endif
                                    @endif
                                    
                                    <tr>
                                        <th class="text-uppercase">SUBTOTAL</th>   
                                        <th class="text-right">
                                            {{ number_format($saldo_final_passivo_corrente_1 + $saldo_final_passivo_corrente_2 + $saldo_final_passivo_corrente_3, 2, ',', '.') }}
                                        </th>
                                        <th class="text-right">0</th>
                                    </tr>
                                 
                                    
                                    <tr>
                                        <th class="text-uppercase">TOTAL PASSIVOS</th>   
                                        <th class="text-right">
                                            {{ number_format($saldo_final_passivo_corrente_1 + $saldo_final_passivo_corrente_2 + $saldo_final_passivo_corrente_3 + $saldo_final_passivo_nao_corrente, 2, ',', '.') }}
                                        </th>
                                        <th class="text-right">0</th>
                                    </tr>
                                 
                                    <tr>
                                        <th colspan="3" class="text-uppercase">Total do capital próprio e passivo</th>
                                    </tr>
                                </tbody>
                            </table>
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
