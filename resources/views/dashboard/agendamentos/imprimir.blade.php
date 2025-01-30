<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AGENDAMENTO</title>

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
    </style>

</head>
<body>

    <header style="position: absolute;top: 30;right: 30px;left: 30px;">
        <table>
            <tr>
                <td rowspan="">
                    <img src="{{ public_path("images/empresa/".$loja->empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                </td>
                <td style="text-align: right">
                    <span style="margin-bottom: 50px">Pág: 1/1</span> <br> <br>
                    {{ date('d-m-Y', strtotime($agendamento->created_at))  }} <br> <br>
                    ORGINAL
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">
                    <strong>{{ $loja->empresa->nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Endereço:</strong> {{ $loja->empresa->morada }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $loja->empresa->nif }}
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone: </strong> {{ $loja->empresa->telefone }}
                </td> 
                <td>
                </td>
            </tr>

            <tr>
                <td>
                    
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
                <td>
                </td>
            </tr> 
            
            <tr>
                <td>
                    <strong>E-mail: </strong> {{ $loja->empresa->website }}
                </td>
                <td>
                  
                </td>
            </tr>

            
        </table>
    </header>

    <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
        <table>
            <tr>
                <td style="font-size: 13px">
                    {{-- <strong>Luanda-Angola</strong> <br> --}}
                    <strong>AGENDAMENTO: <em>{{ $agendamento->numero }}</em> </strong>
                </td>
            </tr>

        </table>

        <table>
            <tr>
                <td style="font-size: 9px;padding: 1px 0">Data de Emissão: {{ $agendamento->created_at }} </td>
            </tr>
        </table>
     
        <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
            <thead style="border-bottom: 1px dashed #000;x">
                <tr>
                    <th style="padding: 10px 0" colspan="5">DADOS DO CLIENTE</th>
                </tr>
                
            </thead>
                <tr>
                    <td  style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 10px;">
                        <strong style="font-size: 9px">NOME: {{ $agendamento->cliente->nome }}</strong>
                    </td>
                </tr>
                <tr>
                    <td  style="border-left: #eaeaea 1px solid; padding: 10px">
                        <strong>NIF/BI:</strong> {{ $agendamento->cliente->nif ?? '99999999999' }}
                    </td>
                </tr>
                <tr>
                    <td  style="border-left: #eaeaea 1px solid; padding: 10px">
                        <strong>ENDEREÇO: </strong> {{ $agendamento->cliente->morada ?? 'Endereço' }}
                    </td>
                </tr>
                <tr>
                    <td style="border-left: #eaeaea 1px solid; padding: 10px" >
                        <strong>TELEFONE: </strong>  {{ $agendamento->cliente->telefone }}
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 10px">
                        <strong>Conta Corrente N.º:  </strong> {{ $agendamento->cliente->conta ?? '31.1.2.1.1' }}
                    </td>
                </tr>
        </table> 
        
        
        <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
            <thead style="border-bottom: 1px dashed #000;x">
                <tr>
                    <th style="padding: 10px 0" colspan="5">DADOS DO AGENDAMENTO</th>
                </tr>
                
            </thead>
                <tr>
                    <td  style="border-left: #eaeaea 1px solid; padding: 10px">
                        <strong>AGENDAMENTO:</strong> {{ $agendamento->numero }}
                    </td>
                </tr>
                <tr>
                    <td  style="border-left: #eaeaea 1px solid; padding: 10px;">
                        <strong style="font-size: 9px">SERVIÇO/PRODUTO: {{ $agendamento->produto->nome }}</strong>
                    </td>
                </tr>
                <tr>
                    <td  style="border-left: #eaeaea 1px solid; padding: 10px">
                        <strong>HORA: </strong> {{ $agendamento->hora }}
                    </td>
                </tr>
                <tr>
                    <td style="border-left: #eaeaea 1px solid; padding: 10px" >
                        <strong>DATA: </strong>  {{ $agendamento->data_at }}
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 10px">
                        <strong>ESTADO DO AGENDAMENTO:  </strong> {{ $agendamento->status }}
                    </td>
                </tr>
        </table> 

    </main>

    {{-- <footer style="position: absolute;bottom: 30;right: 30px;left: 30px;">
        <table>
            <tr>
                <td>
                    OPERADOR: {{ $factura->user->name }}  <br>
                    _______________________
                </td>
            </tr>
        </table>
        <table style="border-top: 2px solid #000000">
            <tbody>
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total Ilíquido:</strong> {{ number_format($factura->total_incidencia, '2', ',', '.') }}</td>
                </tr> 
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total Desconto:</strong> {{ number_format($factura->desconto, '2', ',', '.') }}</td>
                </tr>   
                <tr>
                    <td>Observação: {{ $factura->observacao }}</td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total Imposto:</strong> {{ number_format($factura->total_iva, '2', ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total a Pagar:</strong> {{ number_format($factura->valor_total , '2', ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Valor Entregue:</strong> {{ number_format($factura->valor_entregue , '2', ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Troco:</strong> {{ number_format($factura->valor_troco , '2', ',', '.') }}</td>
                </tr>
           
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total pago:</strong> {{ number_format($factura->valor_total , '2', ',', '.') }}</td>
                </tr>
                
                <tr>
                    <td style="padding: 3px 0;margin-top: 30px;display: block;color: red">Retenção: 0,00</td>
                </tr>
           
                <tr>
                    <td style="padding: 3px 0;margin-top: 30px;display: block;font-style: italic">Os bens/serviços foram colocados à disposição
                        do adquirente na data do documento</td>
                </tr>

                <tr>
                    <td style="padding: 3px 0;">{{ $factura->obterCaracteres($factura->hash) }}</td>
                    <td style="text-align: right;padding: 3px 0;">{{ date("H:i:s") }}</td>
                </tr> 
                
                <tr style="">
                    <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">{{ $factura->valor_extenso ?? 'sem descrição do valor por extensão' }}</td>
                </tr>

                <tr style="">
                    <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">Software de facturação, desenvolvido pela {{ env('APP_NAME') }}</td>
                </tr>
                
            </tbody>
        </table>
    </footer>            --}}
    
</body>
</html>

