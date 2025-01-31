
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FECHO DO TPA - RELATÓRIO</title>

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
                    <img src="{{ public_path("images/empresa/".$tipo_entidade_logado->empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                </td>
                <td style="text-align: right">
                    <span style="margin-bottom: 50px">Pág: 1/1</span> <br> <br>
                    {{ date('d-m-Y', strtotime($movimento->created_at))  }} <br> <br>
                    ORIGINAL
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
                <td>OPERADOR</td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $tipo_entidade_logado->empresa->nif }}
                </td>
                <td  style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                    <strong style="font-size: 9px">{{ $movimento->user->name }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone: </strong> {{ $tipo_entidade_logado->empresa->telefone }}
                </td> 
                <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>NIF:</strong> {{ $movimento->user->nif ?? '99999999999' }}
                </td>
            </tr>

            <tr>
                <td>
                    
                </td>
                <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>Endereço: </strong> {{ $movimento->user->morada ?? 'Endereço' }}
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
                <td style="border-left: #eaeaea 1px solid; padding: 2px" >
                    <strong>Telefone: </strong>  {{ $movimento->user->telefone }}
                </td>
            </tr> 
            
            <tr>
                <td>
                    <strong>E-mail: </strong> {{ $tipo_entidade_logado->empresa->website }}
                </td>
                <td style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>Conta Corrente N.º:  </strong> {{ $movimento->user->conta ?? '31.1.2.1.1' }}
                </td>
            </tr>

            
        </table>
    </header>

    <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
        <table>
            <tr>
                <td style="margin-top: 29px;font-size: 13px">
                    <strong>FECHAMENTO DO TPA</strong>
                </td>
            </tr>
        </table>
    
        <table style="margin-top: 50px ">
            <thead>
                <tr>
                    <th style="padding-bottom: 10px">DADOS DO TPA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>ID: </span></td>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px">{{ $movimento->banco ? $movimento->banco->id : "" }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>NOME: </span></td>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px">{{ $movimento->banco ? $movimento->banco->nome : "" }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>ESTADO: </span></td>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px">{{ $movimento->banco ? $movimento->banco->status : "" }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>TIPO DO BANCO: </span></td>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px">{{ $movimento->banco ? $movimento->banco->tipo_banco : "" }}</td>
                </tr>
            </tbody>
        </table> 
        
        
        
        <table style="margin-top: 20px ">
            <thead>
                <tr>
                    <th style="padding-bottom: 10px">RELATÓRIO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>HORA DE ABERTURA: </span></td>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px;text-align: right">{{ $movimento->hora_abertura ?? "" }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>HORA FECHAMENTO: </span></td>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px;text-align: right">{{ $movimento->hora_fecho ?? "" }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>DATA ABERTURA: </span></td>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px;text-align: right">{{ $movimento->data_abertura ?? "" }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>DATA FECHAMENTO: </span></td>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px;text-align: right">{{ $movimento->data_fecho ?? "" }}</td>
                </tr>
                
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>VALOR INICIAL: </span></td>
                    <th style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px;text-align: right">{{ number_format($movimento->valor_abertura??0, 2, ',', '.') }}</th>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>VALOR FINAL: </span></td>
                    <th style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px;text-align: right">{{ number_format($movimento->valor_valor_fecho??0, 2, ',', '.') }}</th>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>VALOR TPA: </span></td>
                    <th style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px;text-align: right">{{ number_format($movimento->valor_multicaixa??0, 2, ',', '.') }}</th>
                </tr>
                <tr>
                    <td style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px"><span>VALOR TOTAL: </span></td>
                    <th style="border: 1px solid #eaeaea;padding: 4px 10px;width: 200px;text-align: right">{{ number_format($movimento->valor_total, 2, ',', '.') }}</th>
                </tr>
            </tbody>
        </table> 
        
        
    </main>

</body>
</html>

