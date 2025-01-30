<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            padding: 20px;
            width: 100%;
        }
        main{
            width: 100%;
            float: left;
        }
        div{
            padding: 2px;
        }

        .div-border{
            border-top: 2px solid #000000;
            border-left: 2px solid #000000;
        }

        .header{
            width: 100%;
            float: left;
            position: fixed;
        }

        .dados_empresa{
            width: 40%;
            float: left;
            clear: both;
        }
        .dados_clientes{
            width: 50%;
            margin-right: 40px;
            float: right;
            clear: both;
        }

        .dados_factura{
            width: 100%;
            float: right;
            clear: both;
        }

        .header-title{
            text-transform: uppercase;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }

        .main{
            width: 100%;
            background-color: #000000;
        }

        .table{
            width: 95%;
            border-spacing: 0;
            margin-top: 20px;
        }

        .table01{
            /* border-top: 2px solid #000000;
            border-left: 2px solid #000000; */
            width: 40%;
        }
        .table03{
            width: 50%;
            margin-top: 50px;
        }

        .table03 th,
        .table03 td{
            text-align: left;
            padding: 2px;
        }

        .table04{
            width: 95%;
            margin-top: 30px;
        }


        .table th{
            text-align: left;
            border-bottom: #000000 dashed 1px;
            padding: 2px;
        }

        .table tr td{
            text-align: left;
            /* border-bottom: #000000 dashed 1px; */
            padding: 2px;
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

    <table class="table01">
        <tbody>
            <tr>
                <td class="header-title"><strong>{{ $loja->nome_empresa }}</strong></td>
            </tr>
            <tr>
                <td>Morada: {{ $loja->empresa->morada }}</td>
            </tr>
            <tr>
                <td>NIF: {{ $loja->nif }}</td>
            </tr>
            <tr>
                <td>Telefone: {{ $loja->empresa->telefone }}</td>
            </tr>
            <tr>
                <td>E-mail: {{ $loja->email }}</td>
            </tr>
            <tr>
                <td>Website: {{ $loja->empresa->website }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table01">
        <tbody>
            <tr>
                <td class="header-title"><strong>DADOS CLIENTE</strong></td>
            </tr>
            <tr>
                <td>Endereço: {{ $factura->cliente->nome }}</td>
            </tr>
            <tr>
                <td>NIF: {{ $factura->cliente->nif }}</td>
            </tr>
            <tr>
                <td>Telefone: {{ $factura->cliente->telefone }}</td>
            </tr>
            <tr>
                <td>E-mail: {{ $factura->cliente->email }}</td>
            </tr>
            <tr>
                <td>Conta Corrente: {{ $factura->cliente->nome }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table04">
        <tr>
            <td style="text-transform: uppercase">LUANDA-{{ $loja->empresa->pais }} <br>
                {{ $factura->exibir_nome_factura($factura->factura, $factura->ano_factura, $factura->codigo_factura) }}</td>
            <td style="text-align: right;text-transform: uppercase">{{ $factura->exibir_factura($factura->factura) }}</td>
        </tr>

        <tr>
            <td>Moeda. {{ $loja->empresa->moeda }}</td>
            <td style="text-align: right;text-transform: uppercase">Forma Pagamento: {{ $factura->pagamento }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Nª</th>
                <th>Descrição</th>
                <th>Preço Unit.</th>
                <th>Qtd</th>
                <th>Un.</th>
                <th>Desc.</th>
                <th>Taxa%</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>produto</td>
                <td>1.000,37</td>
                <td>1,0</td>
                <td>Un</td>
                <td>0,0</td>
                <td>14,0</td>
                <td>1.140,42</td>
            </tr>
        </tbody>
    </table>

    <table class="table03">
        <thead>
            <tr>
                <th>Taxa Valor</th>
                <th>Incid/Qtd</th>
                <th>Total</th>
                <th>Motivo Insenção/código</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>IVA (14,00%)</td>
                <td>1.000,37</td>
                <td>140,06</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="table">
        <tbody>
            <tr>
                <td>OPERADOR: Manuel Jacinto</td>
            </tr>
            <tr>
                <td>_________________________</td>
            </tr>

            <tr>
                <td class="">________________________________________________________________________________________________________________________________</td>
            </tr>
        </tbody>
    </table>

    <table class="table">
        <tbody>
            
            <tr>
                <td><strong>Total inliquido: </strong>1.000,37</td>
            </tr>

            <tr>
                <td><strong>Total Desconto: </strong>0,00</td>
            </tr>


            <tr>
                <td><strong>Total Imposto: </strong>140,05</td>
            </tr>

            <tr>
                <td><strong>Retenção: </strong>0,00</td>
            </tr>

            <tr>
                <td><strong>Total Pago: </strong>1.140,42</td>
            </tr>

            <tr>
                <td><strong>Troco: </strong>0,00</td>
            </tr>
           
        </tbody>
    </table>

    <table class="table">
        <tbody>
            <tr style="">
                <td></td>
            </tr>

            <tr>
                <td><strong>Observação: </strong> Os bens serviços foram colocados à disposição do adquirente na data do documento</td>
            </tr>

            <tr>
                {{-- <td>EWEV-Processado por Programa válido n31.1/AGT20</td> --}}
            </tr>

            <tr>
                <td style="text-align: center;margin-top: 50px;">Software de facturação, desenvolvido pela {{ env('APP_NAME') }}</td>
            </tr>

            <tr>
                <td style="text-align: right">{{ date('h:i:s') }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>