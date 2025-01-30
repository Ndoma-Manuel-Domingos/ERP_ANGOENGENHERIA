<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            padding: 20px;
            width: 90%;
            margin: 0 auto;
        }

        table{
            width: 100%;
            margin-top: 20px;
            border-spacing: 0;
        }
        td{
            padding: 4px;
        }

        .td{
            border-top: 1px dotted #000000;
            padding: 4px;
            border-bottom: 1px dotted #000000;
        }

        .td-top{
            border-top: 1px dotted#000000;
            padding: 4px;
        }

        .td-bottom{
            padding: 4px;
            border-bottom: 1px dotted #000000;
        }
    </style>

</head>
<body>

    <div style="text-align: left">
        <p style="font-size: 12pt;text-transform: uppercase">{{ $loja->empresa->nome }}</p>
        <p style="font-size: 11pt;">{{ $loja->empresa->morada }}</p>
        <p style="font-size: 11pt;">NIF: {{ $loja->empresa->nif }}</p>
    </div>

    <table>
        <tr>
            <td class="td" colspan="2" style="text-transform: uppercase">{{ $factura->exibir_factura($factura->factura) }}</td>
            <td class="td" style="text-align: right">{{ $factura->factura_next }}</td> 
        </tr>
        <tr>
            <td colspan="2">Original</td>
            <td style="text-align: right">{{ date_format($factura->created_at, "d-m-Y") }}</td> 
        </tr>

    </table>

    <table style="margin-top: 10px">
        <tr>
            <td>NIF:</td>
            <td colspan="2">{{ $factura->cliente->nif?? '----' }}</td>
        </tr>

        <tr>
            <td>Nome:</td>
            <td colspan="2">{{ $factura->cliente->nome?? '----' }}</td>
        </tr>

        <tr>
            <td>Morada:</td>
            <td colspan="2">{{ $factura->cliente->morada?? '---' }}</td>
        </tr>
    </table>

    <table style="margin-top: 10px">
        <tr>
            <td class="td-bottom">Referência Produto</td>
            <td class="td-bottom"></td>
            <td class="td-bottom"></td>
        </tr>

        <tr>
            <td class="td-bottom">Qtd. x Preço</td>
            <td class="td-bottom" style="text-align: right">Taxa</td>
            <td class="td-bottom" style="text-align: right">Total</td>
        </tr>
        @if ($movimentos)
            @foreach ($movimentos as $item)
            <tr>
                <td style="line-height: 15px">{{ $item->produto->nome ?? "" }} <br> {{ $item->quantidade ??0 }} x {{ number_format($item->preco_unitario??0, 2, '.', ',') }}</td>
                <td style="text-align: right">{{ $item->produto->taxa_imposto->valor??0 }} %</td>
                <td style="text-align: right">{{ number_format($item->valor_pagar??0, 2, '.', ',') }} {{ $loja->empresa->moeda?? "" }}</td>
            </tr>   
            @endforeach
        @endif
        
    </table>

    <table>
        <tr>
            <td class="td-top" colspan="3"></td>
        </tr>
        <tr>
            <td colspan="2">SubTotal</td>
            <td style="text-align: right">{{ number_format(($factura->valor_total ?? 0) - ($factura->desconto??0), 2, '.', ',') }} {{ $loja->empresa->moeda ?? "" }}</td> 
        </tr>

        <tr>
            <td colspan="2">Desconto</td>
            <td style="text-align: right">{{ number_format($factura->desconto??0, 2, '.', ',') }} {{ $loja->empresa->moeda ?? "" }}</td> 
        </tr>

        <tr>
            <td colspan="2" style="font-size: 13pt">Total</td>
            <td style="text-align: right;font-size: 13pt">{{ number_format($factura->valor_total??0, 2, '.', ',') }} {{ $loja->empresa->moeda ?? "" }}</td> 
        </tr>

        <tr>
            <td colspan="2">A Pagar: {{ date_format($factura->created_at, "d-m-Y") }}</td>
            <td style="text-align: right">{{ number_format($factura->valor_total??0, 2, '.', ',') }} {{ $loja->empresa->moeda ?? "" }}</td> 
        </tr>

    </table>

    <table>
        <tr>
            <td class="td-bottom">%IVA</td>
            <td class="td-bottom" style="text-align: right">Base</td>
            <td class="td-bottom" style="text-align: right">IVA</td> 
        </tr>
        <tr>
            <td class="td">{{ $taxta ?? 0 }}</td>
            <td class="td" style="text-align: right">{{ number_format($valorBase??0, 2, ',', '.') }} {{ $loja->empresa->moeda ?? "" }}</td>
            <td class="td" style="text-align: right">{{ number_format($valorIva??0, 2, ',', '.') }} {{ $loja->empresa->moeda ?? "" }}</td> 
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="3" style="text-align: center;line-height: 15pt">
                Documento emitido para fins de Formação. Não tem validade fiscal. <br>
                    Bens disponibilizados: {{ date_format($factura->created_at, "d-m-Y") }} <br>
                    qZkY-Processado por programa validado n. 142/AGT <br>
                    Operador: {{ $factura->user->name ?? 0 }} <br>
                    Obrigado pela preferência! <br>
            </td>
        </tr>
    </table>

    
</body>
</html>