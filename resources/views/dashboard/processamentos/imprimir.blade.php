<!DOCTYPE html>
<html lang="pdf">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }} | {{ $descricao }}</title>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
        }

        body {
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 15pt;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12pt;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12pt;
            text-align: justify;
        }

        strong {
            font-size: 12pt;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12pt;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 10px;
        }

        th, td {
            padding: 6px;
            font-size: 9px;
            margin: 0;
            padding: 0; 
        }

        strong {
            font-size: 9px;
        }

        .marca-dagua {
            position: fixed;
            top: 50%;
            left: 50%;
            text-transform: uppercase;
            transform: translate(-50%, -50%);
            font-size: 9em;
            color: rgba(0, 0, 0, 0.1);
            /* Cor do texto com transparência */
            z-index: 1000;
            /* Z-index alto para ficar acima do conteúdo */
            pointer-events: none;
            /* Evitar que o texto interfira com a interação do usuário */
        }

    </style>
</head>
<body>

    <header>
        <table style="border: 0">
            <tr>
                <td style="border: 0;">
                    <img src="images/empresa/{{ $empresa->logotipo }}" style="height: 100px;width: 100px">
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
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Nº</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Nº ORD</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Nome Completo</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Categoria</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Cargo</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Proces.</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Estado</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">S. Base</th>
                <th colspan="{{ count($subsidios) * 1 }}" style="border: 1px solid #010101;padding: 2px;text-align: center">Subsídios</th>
                
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">S. Iliquido</th>

                <th colspan="4" style="border: 1px solid #010101;padding: 2px;text-align: center">Segurança Social</th>

                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;">Desconto</th>
                <th rowspan="2" style="border: 1px solid #010101;padding: 2px;text-align: right">S. líquido</th>
            </tr>
            <tr>
                
                @foreach ($subsidios as $item2)
                <th style="border: 1px solid #010101;padding: 2px;text-align: center">{{ $item2->nome }}</th>
                @endforeach
                
                <th style="border: 1px solid #010101;padding: 2px;text-align: center">INSS 3%</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center">INSS 8%</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center">IRT</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: center">Falta</th>

            </tr>
        </thead>
        <tbody>

            @php
            $valor_base = 0;
            $valor_iliquido = 0;
            $inss = 0;
            $inss_empresa = 0;
            $irt = 0;
            $outros_descontos = 0;
            $total_desconto = 0;
            $valor_liquido = 0;

            $valor_base_social = 0;
            $valor_iliquido_social = 0;
            $inss_social = 0;
            $inss_empresa_social = 0;
            $irt_social = 0;
            $outros_descontos_social = 0;
            $total_desconto_social = 0;
            $valor_liquido_social = 0;

            $valor_base_pessoal = 0;
            $valor_iliquido_pessoal = 0;
            $inss_pessoal = 0;
            $inss_empresa_pessoal = 0;
            $outros_descontos_pessoal = 0;
            $irt_pessoal = 0;
            $total_desconto_pessoal = 0;
            $valor_liquido_pessoal = 0;
            @endphp

            <tr>
                <td colspan="{{ 16 + count($subsidios) }}" style="border: 1px solid #010101;padding: 2px;text-align: left">ORGÃOS SOCIAIS</td>
            </tr>
            
            @foreach ($processamentos_orgacao_social as $key => $item)
            <tr>
                <td style="padding: 3px">{{ $key + 1 }}</td>
                <td style="padding: 3px">{{ $item->funcionario->numero_mecanografico }}</td>
                <td style="padding: 3px">{{ $item->funcionario->nome }}</td>
                <td style="padding: 3px">{{ $item->funcionario->contrato->categoria->nome }}</td>
                <td style="padding: 3px">{{ $item->funcionario->contrato->cargo->nome }}</td>
                <td style="padding: 3px">{{ $item->processamento->nome }}</td>
                @if ($item->status == 'Pendente')
                <td style="padding: 3px"><span class="badge bg-info">{{ $item->status }}</span></td>
                @endif
                @if ($item->status == 'Pago')
                <td style="padding: 3px"><span class="badge bg-success">{{ $item->status }}</span></td>
                @endif
                @if ($item->status == 'Anulado')
                <td style="padding: 3px"><span class="badge bg-warning">{{ $item->status }}</span></td>
                @endif
                <td style="text-align: right;padding: 3px">{{ number_format($item->valor_base, 2, ',', '.') }}</td>
                
                @foreach ($subsidios as $item1)
                    @if ($item->funcionario->contrato->subsidios_contrato)
                        @foreach ($item->funcionario->contrato->subsidios_contrato as $sub)
                            @if ($sub->subsidio_id == $item1->id)
                            <td style="text-align: right;padding: 3px">{{ number_format($sub->salario, 2, ',', '.') }}</td>
                            @endif
                        @endforeach
                    @else
                    <td style="text-align: right;padding: 3px">-</td>
                    @endif
                @endforeach
                
                @if (count($subsidios) - count($item->funcionario->contrato->subsidios_contrato) >= 0)
                    <td style="text-align: right;padding: 3px" colspan="{{ count($subsidios) - count($item->funcionario->contrato->subsidios_contrato) }}">-</td>
                @endif
                
                
                <td style="text-align: right;padding: 3px">{{ number_format($item->valor_iliquido, 2, ',', '.') }}</td>

                <td style="text-align: right;padding: 3px">{{ number_format($item->inss, 2, ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">{{ number_format($item->inss_empresa, 2, ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">{{ number_format($item->irt, 2, ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">{{ number_format($item->outros_descontos, 2, ',', '.') }}</td>

                <td style="text-align: right;padding: 3px">{{ number_format($item->total_desconto, 2, ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">{{ number_format($item->valor_liquido, 2, ',', '.') }}</td>

                @php
                    $valor_base_social += $item->valor_base;
                    $valor_iliquido_social += $item->valor_iliquido;
                    $inss_social += $item->inss;
                    $inss_empresa_social += $item->inss_empresa;
                    $irt_social += $item->irt;
                    $outros_descontos_social += $item->outros_descontos;
                    $total_desconto_social += $item->total_desconto;
                    $valor_liquido_social += $item->valor_liquido;
                @endphp

            </tr>
            @endforeach

            <tr>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right" colspan="7">TOTAL</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right" colspan="">{{ number_format($valor_base_social, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right" colspan="{{ count($subsidios) }}"></th>

                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($valor_iliquido_social, 2, ',', '.') }}</th>

                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($inss_social, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($inss_empresa_social, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($irt_social, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($outros_descontos_social, 2, ',', '.') }}</th>

                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_desconto_social, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($valor_liquido_social, 2, ',', '.') }}</th>

            </tr>

            <tr>
                <td colspan="{{ 16 + count($subsidios) }}" style="border: 1px solid #010101;padding: 2px;text-align: left">PESSOAL</td>
            </tr>
            
            @foreach ($processamentos_pessoal as $key => $item)
            <tr>
                <td style="padding: 3px">{{ $key + 1 }}</td>
                <td style="padding: 3px">{{ $item->funcionario->numero_mecanografico }}</td>
                <td style="padding: 3px">{{ $item->funcionario->nome }}</td>
                <td style="padding: 3px">{{ $item->funcionario->contrato->categoria->nome }}</td>
                <td style="padding: 3px">{{ $item->funcionario->contrato->cargo->nome }}</td>
                <td style="padding: 3px">{{ $item->processamento->nome }}</td>
                @if ($item->status == 'Pendente')
                <td style="padding: 3px"><span class="badge bg-info">{{ $item->status }}</span></td>
                @endif
                @if ($item->status == 'Pago')
                <td style="padding: 3px"><span class="badge bg-success">{{ $item->status }}</span></td>
                @endif
                @if ($item->status == 'Anulado')
                <td style="padding: 3px"><span class="badge bg-warning">{{ $item->status }}</span></td>
                @endif
                <td style="text-align: right;padding: 3px">{{ number_format($item->valor_base, 2, ',', '.') }}</td>
                
                @foreach ($subsidios as $item1)
                    @if ($item->funcionario->contrato->subsidios_contrato)
                        @foreach ($item->funcionario->contrato->subsidios_contrato as $sub)
                            @if ($sub->subsidio_id == $item1->id)
                            <td style="text-align: right;padding: 3px">{{ number_format($sub->salario, 2, ',', '.') }}</td>
                            @endif
                        @endforeach
                    @else
                    <td style="text-align: right;padding: 3px">-</td>
                    @endif
                @endforeach
                
                @if (count($subsidios) - count($item->funcionario->contrato->subsidios_contrato) > 0)
                    <td style="text-align: right;padding: 3px" colspan="{{ count($subsidios) - count($item->funcionario->contrato->subsidios_contrato) }}">-</td>
                @endif
                
                <td style="text-align: right;padding: 3px">{{ number_format($item->valor_iliquido, 2, ',', '.') }}</td>

                <td style="text-align: right;padding: 3px">{{ number_format($item->inss, 2, ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">{{ number_format($item->inss_empresa, 2, ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">{{ number_format($item->irt, 2, ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">{{ number_format($item->outros_descontos, 2, ',', '.') }}</td>

                <td style="text-align: right;padding: 3px">{{ number_format($item->total_desconto, 2, ',', '.') }}</td>
                <td style="text-align: right;padding: 3px">{{ number_format($item->valor_liquido, 2, ',', '.') }}</td>

                @php
                $valor_base_pessoal += $item->valor_base;
                $valor_iliquido_pessoal += $item->valor_iliquido;
                $inss_pessoal += $item->inss;
                $inss_empresa_pessoal += $item->inss_empresa;
                $irt_pessoal += $item->irt;
                $outros_descontos_pessoal += $item->outros_descontos;
                $total_desconto_pessoal += $item->total_desconto;
                $valor_liquido_pessoal += $item->valor_liquido;
                @endphp

            </tr>
            @endforeach

            <tr>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right" colspan="7">TOTAL</th>
                
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($valor_base_pessoal, 2, ',', '.') }}</th>
                
                <th style="border: 1px solid #010101;padding: 2px;text-align: right" colspan="{{ count($subsidios) }}"></th>
                
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($valor_iliquido_pessoal, 2, ',', '.') }}</th>

                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($inss_pessoal, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($inss_empresa_pessoal, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($irt_pessoal, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($outros_descontos_pessoal, 2, ',', '.') }}</th>

                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($total_desconto_pessoal, 2, ',', '.') }}</th>
                <th style="border: 1px solid #010101;padding: 2px;text-align: right">{{ number_format($valor_liquido_pessoal, 2, ',', '.') }}</th>

            </tr>

            @foreach ($processamentos as $key => $item)
            @php
            $valor_base += $item->valor_base;
            $valor_iliquido += $item->valor_iliquido;
            $inss += $item->inss;
            $inss_empresa += $item->inss_empresa;
            $irt += $item->irt;
            $total_desconto += $item->total_desconto;
            $outros_descontos += $item->outros_descontos;
            $valor_liquido += $item->valor_liquido;
            @endphp
            @endforeach

            <tr>
                <th style="padding: 5px;text-align: right" colspan="7">TOTAL</th>

                <th style="text-align: right">{{ number_format($valor_base, 2, ',', '.') }}</th>
                
                
                <th style="padding: 2px;text-align: right" colspan="{{ count($subsidios) }}"></th>
                
                <th style="text-align: right">{{ number_format($valor_iliquido, 2, ',', '.') }}</th>

                <th style="text-align: right">{{ number_format($inss, 2, ',', '.') }}</th>
                <th style="text-align: right">{{ number_format($inss_empresa, 2, ',', '.') }}</th>
                <th style="text-align: right">{{ number_format($irt, 2, ',', '.') }}</th>

                <th style="text-align: right">{{ number_format($outros_descontos, 2, ',', '.') }}</th>
                <th style="text-align: right">{{ number_format($total_desconto, 2, ',', '.') }}</th>
                <th style="text-align: right">{{ number_format($valor_liquido, 2, ',', '.') }}</th>

            </tr>
        </tbody>
    </table>

</body>
</html>
