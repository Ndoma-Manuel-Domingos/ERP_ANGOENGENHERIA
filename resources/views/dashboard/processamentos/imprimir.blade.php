@extends('layouts.pdf')

@section('pdf-content')

<header>
    <table style="border: 0">
        <tr>
            <td style="border: 0;">
                <img src= "images/empresa/{{ $empresa->logotipo }}" style="height: 100px;width: 100px">
            </td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0">{{ $empresa->nome }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>NIF: </strong>{{ $empresa->nif }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>Endereço: </strong>{{ $empresa->morada }}</td>
        </tr>
        <tr style="border: 0">
            <td style="border: 0"><strong>{{ $empresa->cidade }} - {{ $empresa->pais }}</strong></td>
        </tr>
    </table>
</header>

<table>
    <thead>
        <tr>
            <th colspan="2" style="text-transform: uppercase">Tipo de Processamento</th>
            <th colspan="2" style="text-transform: uppercase">Exercício</th>
            <th colspan="2" style="text-transform: uppercase">Período</th>
            <th colspan="2" style="text-transform: uppercase">Estado Processamento</th>
            <th colspan="2" style="text-transform: uppercase">Data Inicio</th>
            <th colspan="2" style="text-transform: uppercase">Data Final</th>
        </tr>

        <tr>
            <th colspan="2">{{ $tipo_processamento ? $tipo_processamento->nome : 'TODOS' }}</th>
            <th colspan="2">{{ $exercicio ? $exercicio->nome : 'TODOS' }}</th>
            <th colspan="2">{{ $periodo ? $periodo->nome : 'TODOS' }}</th>
            <th colspan="2">{{ $requests['status'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2">{{ $requests['data_final'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>


<table class="table table-hover text-nowrap">
    <thead>
        <tr>
            <th rowspan="2">Nº</th>
            <th rowspan="2">Nº ORD</th>
            <th rowspan="2">Nome Completo</th>
            <th rowspan="2">Categoria</th>
            <th rowspan="2">Cargo</th>
            <th rowspan="2">Proces.</th>
            <th rowspan="2">Estado</th>
            <th rowspan="2">S. Base</th>
            <th rowspan="2">S. Iliquido</th>
            
            <th colspan="3" style="text-align: center">Segurança Social</th>
            
            <th rowspan="2">Desconto</th>
            <th rowspan="2">S. líquido</th>

        </tr>
        <tr>
            
            <th style="text-align: center">INSS 3%</th>
            <th style="text-align: center">INSS 8%</th>
            <th style="text-align: center">IRT</th>
     
        </tr>
    </thead>
    <tbody>
        
        @php
            $valor_base = 0;
            $valor_iliquido = 0;
            $inss = 0;
            $inss_empresa = 0;
            $irt = 0;
            $total_desconto = 0;
            $valor_liquido = 0;
            
            $valor_base_social = 0;
            $valor_iliquido_social = 0;
            $inss_social = 0;
            $inss_empresa_social = 0;
            $irt_social = 0;
            $total_desconto_social = 0;
            $valor_liquido_social = 0;
            
            $valor_base_pessoal = 0;
            $valor_iliquido_pessoal = 0;
            $inss_pessoal = 0;
            $inss_empresa_pessoal = 0;
            $irt_pessoal = 0;
            $total_desconto_pessoal = 0;
            $valor_liquido_pessoal = 0;
        @endphp
        
        <tr>
            <td colspan="15" style="text-align: left">ORGÃOS SOCIAIS</td>
        </tr>
        
        @foreach ($processamentos_orgacao_social as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->funcionario->numero_mecanografico }}</td>
            <td>{{ $item->funcionario->nome }}</td>
            <td>{{ $item->funcionario->contrato->categoria->nome }}</td>
            <td>{{ $item->funcionario->contrato->cargo->nome }}</td>
            <td>{{ $item->processamento->nome }}</td>
            @if ($item->status == 'Pendente')
            <td><span class="badge bg-info">{{ $item->status }}</span></td>
            @endif
            @if ($item->status == 'Pago')
            <td><span class="badge bg-success">{{ $item->status }}</span></td>
            @endif
            @if ($item->status == 'Anulado')
            <td><span class="badge bg-warning">{{ $item->status }}</span></td>
            @endif
            <td style="text-align: right">{{ number_format($item->valor_base, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->valor_iliquido, 2, ',', '.') }}</td>
            
            <td style="text-align: right">{{ number_format($item->inss, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->inss_empresa, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->irt, 2, ',', '.') }}</td>
            
            <td style="text-align: right">{{ number_format($item->total_desconto, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->valor_liquido, 2, ',', '.') }}</td>
            
            @php
                $valor_base_social += $item->valor_base;
                $valor_iliquido_social += $item->valor_iliquido;
                $inss_social += $item->inss;
                $inss_empresa_social += $item->inss_empresa;
                $irt_social += $item->irt;
                $total_desconto_social += $item->total_desconto;
                $valor_liquido_social += $item->valor_liquido;
            @endphp
            
        </tr>
        @endforeach
        
        <tr>
            <th style="padding: 5px;text-align: right" colspan="7">TOTAL</th>
            
            <th style="text-align: right">{{ number_format($valor_base_social, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($valor_iliquido_social, 2, ',', '.') }}</th>
            
            <th style="text-align: right">{{ number_format($inss_social, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($inss_empresa_social, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($irt_social, 2, ',', '.') }}</th>
            
            <th style="text-align: right">{{ number_format($total_desconto_social, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($valor_liquido_social, 2, ',', '.') }}</th>
            
        </tr>
          
        <tr>
            <td colspan="15" style="text-align: left">PESSOAL</td>
        </tr>
        
        @foreach ($processamentos_pessoal as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->funcionario->numero_mecanografico }}</td>
            <td>{{ $item->funcionario->nome }}</td>
            <td>{{ $item->funcionario->contrato->categoria->nome }}</td>
            <td>{{ $item->funcionario->contrato->cargo->nome }}</td>
            <td>{{ $item->processamento->nome }}</td>
            @if ($item->status == 'Pendente')
            <td><span class="badge bg-info">{{ $item->status }}</span></td>
            @endif
            @if ($item->status == 'Pago')
            <td><span class="badge bg-success">{{ $item->status }}</span></td>
            @endif
            @if ($item->status == 'Anulado')
            <td><span class="badge bg-warning">{{ $item->status }}</span></td>
            @endif
            <td style="text-align: right">{{ number_format($item->valor_base, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->valor_iliquido, 2, ',', '.') }}</td>
            
            <td style="text-align: right">{{ number_format($item->inss, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->inss_empresa, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->irt, 2, ',', '.') }}</td>
            
            <td style="text-align: right">{{ number_format($item->total_desconto, 2, ',', '.') }}</td>
            <td style="text-align: right">{{ number_format($item->valor_liquido, 2, ',', '.') }}</td>
            
            @php
                $valor_base_pessoal += $item->valor_base;
                $valor_iliquido_pessoal += $item->valor_iliquido;
                $inss_pessoal += $item->inss;
                $inss_empresa_pessoal += $item->inss_empresa;
                $irt_pessoal += $item->irt;
                $total_desconto_pessoal += $item->total_desconto;
                $valor_liquido_pessoal += $item->valor_liquido;
            @endphp
            
        </tr>
        @endforeach
        
        
        <tr>
            <th style="padding: 5px;text-align: right" colspan="7">TOTAL</th>
            
            <th style="text-align: right">{{ number_format($valor_base_pessoal, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($valor_iliquido_pessoal, 2, ',', '.') }}</th>
            
            <th style="text-align: right">{{ number_format($inss_pessoal, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($inss_empresa_pessoal, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($irt_pessoal, 2, ',', '.') }}</th>
            
            <th style="text-align: right">{{ number_format($total_desconto_pessoal, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($valor_liquido_pessoal, 2, ',', '.') }}</th>
            
        </tr>
        
        @foreach ($processamentos as $key => $item)
            @php
              $valor_base += $item->valor_base;
              $valor_iliquido += $item->valor_iliquido;
              $inss += $item->inss;
              $inss_empresa += $item->inss_empresa;
              $irt += $item->irt;
              $total_desconto += $item->total_desconto;
              $valor_liquido += $item->valor_liquido;
             @endphp
        @endforeach
        
        
        <tr>
            <th style="padding: 5px;text-align: right" colspan="7">TOTAL</th>
            
            <th style="text-align: right">{{ number_format($valor_base, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($valor_iliquido, 2, ',', '.') }}</th>
            
            <th style="text-align: right">{{ number_format($inss, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($inss_empresa, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($irt, 2, ',', '.') }}</th>
            
            <th style="text-align: right">{{ number_format($total_desconto, 2, ',', '.') }}</th>
            <th style="text-align: right">{{ number_format($valor_liquido, 2, ',', '.') }}</th>
            
        </tr>
    </tbody>
</table>



@endsection