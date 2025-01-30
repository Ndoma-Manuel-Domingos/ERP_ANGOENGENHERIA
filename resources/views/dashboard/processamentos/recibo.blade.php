<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RECIBO</title>

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

@if ($tipo_entidade_logado->empresa->marca_d_agua_facturas == true)
<body style="background-image: url('images/empresa/{{ $tipo_entidade_logado->empresa->logotipo }}'); background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center center;
    background-size: contain;opacity: .1;margin: 140px;">
@endif

@if ($tipo_entidade_logado->empresa->marca_d_agua_facturas == false)
<body>
@endif

        <header style="position: absolute;top: 30;right: 30px;left: 30px;">
            <table>
                <tr>
                    <td style="padding: 5px 0;">
                        <strong>{{ $tipo_entidade_logado->empresa->nome ?? "" }}</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>NIF:</strong> {{ $tipo_entidade_logado->empresa->nif ?? "" }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>E-mail: </strong> {{ $tipo_entidade_logado->empresa->website ?? "" }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Endereço: </strong> {{ $tipo_entidade_logado->empresa->mprada ?? "" }}
                    </td>
                </tr>

            </table>
        </header>

        <main style="position: absolute;top: 120px;right: 30px;left: 30px;">
            <table>
                <tr>
                    <th style="font-size: 13px;text-transform: uppercase;border-bottom: 2px solid #3f3f3f;padding: 5px;">Recibo de {{ $processamento->processamento->nome }} </th>
                </tr>
            </table>

            <table>
                <tr>
                    <th colspan="2" style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nome</th>
                    <th style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nº Mecan.</th>
                    <th style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nº Benef.</th>
                    <th style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nº Contrib.</th>
                </tr>
                
                <tr>
                    <td colspan="2" style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->funcionario->nome ?? "" }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->funcionario->numero_mecanografico ?? "" }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">12</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->funcionario->nif ?? "" }}</td>
                </tr>
                
                <tr>
                    <th style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Período</th>
                    <th style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Data Fecho</th>
                    <th style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Vencimento</th>
                    <th style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Venc./Horas</th>
                    <th style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Nº Dias Úteis</th>
                </tr>
                
                <tr>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->periodo->nome ?? "" }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->data_registro ?? "" }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ number_format($processamento->valor_base ?? 0, 2, ',', '.') }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">681,82</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->dias_processados ?? "" }}</td>
                </tr>
                
                <tr>
                    <th colspan="2" style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Departamento</th>
                    <th colspan="2" style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Cargo</th>
                    <th colspan="2" style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">Categoria</th>
                </tr>
                
                <tr>
                    <td colspan="2" style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->funcionario->contrato->cargo->departamento->nome ?? "" }}</td>
                    <td colspan="2" style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->funcionario->contrato->cargo->nome ?? "" }}</td>
                    <td colspan="2" style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $processamento->funcionario->contrato->categoria->nome ?? "" }}</td>
                </tr>
                
            </table>
       
            
            <table style="margin-top: 50px;border-top: 1px solid #000000;padding: 10px">
                <tr>
                    <th>Cód</th>
                    <th>Data</th>
                    <th colspan="3">Descrição</th>
                    <th style="text-align: right">Remuneração</th>
                    <th style="text-align: right">Desconto</th>
                </tr>
                
                <tr>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">R01</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="3">Vencimento</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px;text-align: right">{{ number_format($processamento->valor_base ?? 0, 2, ',', '.') }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                </tr>
                @foreach ($processamento->funcionario->contrato->subsidios_contrato as $item)
                <tr>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $item->subsidio->numero ?? "" }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="3">{{ $item->subsidio->nome ?? "" }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px;text-align: right">{{ number_format($item->salario, 2, ',', '.') }}</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                </tr>
                @endforeach
                
                <tr>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">D001</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="3">Segurança Social(3%)</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px;text-align: right">{{ number_format($processamento->inss, 2, ',', '.') }}</td>
                </tr>
                      
                <tr>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">D002</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="3">IRT({{$processamento->taxa_irt}}%)</td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                    <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px;text-align: right">{{ number_format($processamento->irt, 2, ',', '.') }}</td>
                </tr>
                          
                @foreach ($processamento->funcionario->contrato->descontos_contrato as $item)
                @if ($item->tipo == 'O')
                    <tr>
                        <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px">{{ $item->desconto->numero ?? "" }}</td>
                        <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                        <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px" colspan="3">{{ $item->desconto->nome ?? "" }}</td>
                        <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px"></td>
                        <td style="border-bottom: 1px solid #cecece;padding: 5px;margin: 5px;text-align: right">{{ number_format($item->salario, 2, ',', '.') }} {{ $item->tipo_valor == "P" ? "%" : " AOA" }}</td>
                    </tr>
                @endif
                @endforeach
                
            </table>
          
        </main>

        <footer style="position: absolute;bottom: 30;right: 30px;left: 30px;">
            <table style="margin-bottom: 80px">
                <tr>
                    <td style="text-align: center">O Funcionário</td>
                    <td style="text-align: center">O Recursos Humanos</td>
                </tr>
                <tr>
                    <td style="text-align: center">____________________________________________________________</td>
                    <td style="text-align: center">____________________________________________________________</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><em>Declaro que recebi a quantia constante neste recibo, </em><br> _________________________________________________ </td>
                </tr>
            </table>
            <table style="border-top: 2px solid #000000">
                <tbody>
                    <tr>
                        <th>Forma de Pagamento 100%</th>
                        <th style="text-align: right;padding: 5px 0;" width="100px">Total Ilíquido</th>
                        <th style="text-align: right;padding: 5px 0;" width="100px">Total Desconto</th>
                    </tr>
                    <tr>
                        <td>{{ $processamento->funcionario->contrato->forma_pagamento->titulo ?? "" }}</td>
                        <th style="text-align: right;padding: 5px 0;" width="100px">{{ number_format($processamento->valor_iliquido, '2', ',', '.') }}</th>
                        <th style="text-align: right;padding: 5px 0;" width="100px">{{ number_format($processamento->total_desconto, '2', ',', '.') }}</th>
                    </tr>
                    
                    <tr>
                        <th colspan=""></th>
                        <th style="text-align: right;padding: 5px 0;font-size: 12px;" width="100px">Total Pago (AKZ)</th>
                        <th style="text-align: right;padding: 5px 0;font-size: 12px;"  width="100px">{{ number_format($processamento->valor_liquido , '2', ',', '.') }}</th>
                    </tr>
                   
                    <tr style="">
                        <td style="padding: 5px 0; text-align: center;border-top: 2px solid #000000" colspan="3">Software de facturação (Recurso Humano), desenvolvido pela {{ env('APP_NAME') }}</td>
                    </tr>

                </tbody>
            </table>
        </footer>

    </body>
</html>
