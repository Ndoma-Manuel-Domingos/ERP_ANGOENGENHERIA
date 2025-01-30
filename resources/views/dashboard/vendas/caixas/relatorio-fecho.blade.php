
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FACTURA RECIBO</title>

    <style type="text/css">
        *{
            margin: 0;
            padding: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
        }
        body{
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1{
            font-size: 15pt;
            margin-bottom: 10px;
        }
        h2{
            font-size: 12pt;
        }
        p{
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12pt;
            text-align: justify;
        }
        strong{
            font-size: 12pt;
        }

        table{
            width: 100%;
            text-align: left;
            border-spacing: 0;	
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12pt;
        }
        thead{
            background-color: #fdfdfd;
            font-size: 10px;
        }
        th, td{
            padding: 6px;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }
        strong{
            font-size: 9px;
        }
        
                
        .marca-dagua {
            position: fixed;
            top: 50%;
            left: 50%;
            text-transform: uppercase;
            transform: translate(-50%, -50%);
            font-size: 9em;
            color: rgba(0, 0, 0, 0.1); /* Cor do texto com transparência */
            z-index: 1000; /* Z-index alto para ficar acima do conteúdo */
            pointer-events: none; /* Evitar que o texto interfira com a interação do usuário */
        }
        
        /* Estilo para a impressão */
        @media print {
            @page {
                margin: 10px; /* Remove todas as margens da página */
                background-color: #000;
            }
            .pagina {
                width: 500px;
                height: 100vh;
                page-break-after: always; /* Força quebra de página após cada seção */
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    
        /* Estilo para visualização na tela */
        @media screen {
            .pagina {
                width: 500px;
                height: 100vh;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                padding: 20px;
            }
        }
    </style>

</head>
<body>

    
        
    @if ($tipo_entidade_logado->empresa->tipo_factura == "Normal")
    <header style="position: absolute;top: 30;right: 30px;left: 30px;">
        <table>
            <tr>
                <td rowspan="">
                    <img src="{{ public_path("images/empresa/".$tipo_entidade_logado->empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">
                    <strong>{{ $tipo_entidade_logado->empresa->nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Endereço:</strong> {{ $tipo_entidade_logado->empresa->morada }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $tipo_entidade_logado->empresa->nif }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone: </strong> {{ $tipo_entidade_logado->empresa->telefone }}
                </td> 
            </tr>
            
            <tr>
                <td>
                    <strong>E-mail: </strong> {{ $tipo_entidade_logado->empresa->website }}
                </td>
            </tr>
            
        </table>
    </header>

    <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
        <table>
            <tr>
                <th colspan="10" style="text-transform: uppercase;font-size: 14px">FECHAMENTO DE CAIXA</th>
            </tr>
        </table>
       
        <table>
            <tr>
                <th colspan="6" style="text-transform: uppercase;font-size: 11px;border: 1px solid #ccc;padding: 5px">CONTA: {{ $subconta ? "{$subconta->numero}-{$subconta->nome}" : "TODOS" }}</th>
                <th colspan="4" style="text-transform: uppercase;font-size: 11px;border: 1px solid #ccc;padding: 5px">DATA INICIO: {{ $data_inicio ?? "TODOS" }}</th>
                <th colspan="4" style="text-transform: uppercase;font-size: 11px;border: 1px solid #ccc;padding: 5px">DATA FINAL: {{ $data_final ?? "TODOS" }}</th>
            </tr>
        </table>
        
        
        <table>
            <thead>
              <tr>
                <th colspan="12" style="text-align: center;font-size: 15px;">Resumo dos Movimentos</th>
              </tr>
              <tr>
                <th colspan="5" style="font-size: 12px;">Tipo</th>
                <th style="text-align: right;padding: 10px 0;font-size: 12px;">Credito</th>
                <th style="text-align: right;font-size: 12px;">Debito</th>
                <th colspan="5" style="text-align: right;font-size: 12px;">Montante</th>
              </tr>
            </thead>
            <tbody>
                @php
                    $saldo = $debito - $credito;
                 @endphp
              <tr>
                <td colspan="5" style="font-size: 12px;">Entradas por Multicaixa(TPA)</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">{{ number_format($multicaixa_credito, 2, ',', '.') }}</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">{{ number_format($multicaixa_debito, 2, ',', '.') }}</td>
                <td colspan="5" style="font-size: 12px;text-align: right">{{ number_format($multicaixa, 2, ',', '.') }}</td>
              </tr>

              <tr>
                <td colspan="5" style="font-size: 12px;">Entradas por Numerário(Cash)</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">{{ number_format($numerorio_credito, 2, ',', '.') }}</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">{{ number_format($numerorio_debito, 2, ',', '.') }}</td>
                <td colspan="5" style="font-size: 12px;text-align: right">{{ number_format($numerorio, 2, ',', '.') }}</td>
              </tr>
              
              <tr>
                <td colspan="5" style="font-size: 12px;">Entradas por Multicaixa & Numerário (DUPLO)</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">{{ number_format($duplo_credito, 2, ',', '.') }}</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">{{ number_format($duplo_debito, 2, ',', '.') }}</td>
                <td colspan="5" style="font-size: 12px;text-align: right">{{ number_format($duplo, 2, ',', '.') }}</td>
              </tr>
              
              <tr>
                <td colspan="5" style="font-size: 12px;">Saída <small class="text-info">(incluindo todas as operações)</small></td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">--</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">--</td>
                <td colspan="5" style="font-size: 12px;text-align: right">{{ number_format($credito, 2, ',', '.') }}</td>
              </tr>
              
              <tr>
                <td colspan="5" style="font-size: 12px;">Entrada <small class="text-info">(incluindo todas as operações)</small></td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">--</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">--</td>
                <td colspan="5" style="font-size: 12px;text-align: right">{{ number_format($debito, 2, ',', '.') }}</td>
              </tr>
              
              <tr>
                <th colspan="5" style="font-size: 15px;">Saldo Final</th>
                <td style="padding: 5px 0;font-size: 12px;text-align: right;padding: 10px">--</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">--</td>
                <th colspan="5" style="text-align: right;font-size: 15px;">{{ number_format($saldo, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></th>
              </tr>

            </tbody>
        </table>

    </main>

    @endif
    
    @if ($tipo_entidade_logado->empresa->tipo_factura == "Ticket")
    <header style="position: absolute;top: 30;right: 30px;left: 30px;width: 300px">
        <table>
            <tr>
                <td rowspan="">
                    <img src="{{ public_path("images/empresa/".$tipo_entidade_logado->empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">
                    <strong>{{ $tipo_entidade_logado->empresa->nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Endereço:</strong> {{ $tipo_entidade_logado->empresa->morada }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $tipo_entidade_logado->empresa->nif }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone: </strong> {{ $tipo_entidade_logado->empresa->telefone }}
                </td> 
            </tr>
            
            <tr>
                <td>
                    <strong>E-mail: </strong> {{ $tipo_entidade_logado->empresa->website }}
                </td>
            </tr>
            
        </table>
    </header>

    <main style="position: absolute;top: 230px;right: 30px;left: 30px;width: 300px">
        <table>
            <tr>
                <th colspan="10" style="text-transform: uppercase;font-size: 14px">FECHAMENTO DE CAIXA</th>
            </tr>
        </table>
        
        <table>
            <tr>
                <th colspan="6" style="text-transform: uppercase;font-size: 11px;border: 1px solid #ccc;padding: 5px">CAIXA: {{ $caixa ? $caixa->nome : "TODOS" }}</th>
                <th colspan="4" style="text-transform: uppercase;font-size: 11px;border: 1px solid #ccc;padding: 5px">DATA INICIO: {{ $data_inicio ?? "TODOS" }}</th>
                <th colspan="4" style="text-transform: uppercase;font-size: 11px;border: 1px solid #ccc;padding: 5px">DATA FINAL: {{ $data_final ?? "TODOS" }}</th>
            </tr>
        </table>
        
        <table>
            <thead>
              <tr>
                <th colspan="12" style="text-align: center;font-size: 15px;">Resumo dos Movimentos</th>
              </tr>
              <tr>
                <th colspan="5" style="font-size: 10px;border: 1px solid #ccc;padding: 5px">Tipo</th>
                <th style="text-align: right;padding: 10px 0;font-size: 10px;border: 1px solid #ccc;padding: 5px">Credito</th>
                <th style="text-align: right;font-size: 10px;border: 1px solid #ccc;padding: 5px">Debito</th>
                <th colspan="5" style="text-align: right;font-size: 10px;border: 1px solid #ccc;padding: 5px">Montante</th>
              </tr>
            </thead>
            <tbody>
                @php
                    $saldo = $debito - $credito;
                 @endphp
              <tr>
                <td colspan="5" style="font-size: 10px;">Entradas por Multicaixa(TPA)</td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">{{ number_format($multicaixa_credito, 2, ',', '.') }}</td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">{{ number_format($multicaixa_debito, 2, ',', '.') }}</td>
                <td colspan="5" style="font-size: 10px;text-align: right">{{ number_format($multicaixa, 2, ',', '.') }}</td>
              </tr>

              <tr>
                <td colspan="5" style="font-size: 10px;">Entradas por Numerário(Cash)</td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">{{ number_format($numerorio_credito, 2, ',', '.') }}</td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">{{ number_format($numerorio_debito, 2, ',', '.') }}</td>
                <td colspan="5" style="font-size: 10px;text-align: right">{{ number_format($numerorio, 2, ',', '.') }}</td>
              </tr>
              
              <tr>
                <td colspan="5" style="font-size: 10px;">Entradas por Multicaixa & Numerário (DUPLO)</td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">{{ number_format($duplo_credito, 2, ',', '.') }}</td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">{{ number_format($duplo_debito, 2, ',', '.') }}</td>
                <td colspan="5" style="font-size: 10px;text-align: right">{{ number_format($duplo, 2, ',', '.') }}</td>
              </tr>
              
              <tr>
                <td colspan="5" style="font-size: 10px;">Saída <small class="text-info">(incluindo todas as operações)</small></td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">--</td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">--</td>
                <td colspan="5" style="font-size: 10px;text-align: right">{{ number_format($credito, 2, ',', '.') }}</td>
              </tr>
              
              <tr>
                <td colspan="5" style="font-size: 10px;">Entrada <small class="text-info">(incluindo todas as operações)</small></td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">--</td>
                <td style="padding: 5px 0;font-size: 10px;text-align: right">--</td>
                <td colspan="5" style="font-size: 10px;text-align: right">{{ number_format($debito, 2, ',', '.') }}</td>
              </tr>
              
              <tr>
                <th colspan="5" style="font-size: 15px;">Saldo Final</th>
                <td style="padding: 5px 0;font-size: 12px;text-align: right;padding: 10px">--</td>
                <td style="padding: 5px 0;font-size: 12px;text-align: right">--</td>
                <th colspan="5" style="text-align: right;font-size: 15px;">{{ number_format($saldo, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></th>
              </tr>

            </tbody>
        </table>

    </main>

    @endif

    

</body>
</html>
