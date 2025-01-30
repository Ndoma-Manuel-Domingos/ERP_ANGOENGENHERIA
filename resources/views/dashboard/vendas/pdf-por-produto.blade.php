<!DOCTYPE html>
<html lang="pdf">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }} | {{ $descricao }}</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body{
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }
        h1{
            font-size: 13pt;
            margin-bottom: 10px;
        }
        table{
            width: 100%;
            border-spacing: 0;
            margin-top: 20px;
            border: 1px solid #dadada;
        }

        thead{
            /* background-color: #dadada; */
            text-align: left;
            border-bottom: 1px dashed #919191;
            border-right: 1px dashed #919191;
            border-left: 1px dashed #919191;
        }

        thead > tr > th{
            padding: 5px;
            text-align: left;
        }
        
        thead > tr > th{
            border-bottom: 1px dashed #919191;
            border-right: 1px dashed #919191;
            border-left: 1px dashed #919191;
        }

        tbody > tr > td{
            padding: 5px;
            border-bottom: 1px dashed #919191;
            border-right: 1px dashed #919191;
            border-left: 1px dashed #919191;
            /* border 1px tailwind */
        }
        footer{
            position: fixed;
            bottom: 0;
        }

        .text-center{
            text-align: center;
        }

        .text-start{
            text-align: left;
        }

        .text-end{
            text-align: right;
        }
        
                
        /* Estilo para a impressão */
        @media print {
            @page {
                margin: 3px; /* Remove todas as margens da página */
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

<table>
    <thead>
        <tr>
            <th colspan="10" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
    </thead>
</table>


<table>
    <thead>
        <tr>
            <th colspan="2" style="text-transform: uppercase;font-size: 20px">Data Inicio</th>
            <th colspan="2" style="text-transform: uppercase;font-size: 20px">Data Final</th>
        </tr>

        <tr>
            <th colspan="2" style="text-transform: uppercase;font-size: 20px">{{ $requests['data_inicio'] ?? 'TODOS' }}</th>
            <th colspan="2" style="text-transform: uppercase;font-size: 20px">{{ $requests['data_final'] ?? 'TODOS' }}</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="text-align: left;font-size: 20px">Ref</th>
            <th style="text-align: left;font-size: 20px">Desc.</th>
            <th style="text-align: left;font-size: 20px">Qtd</th>
            <th style="text-align: left;font-size: 20px">Total</th>
        </tr>
    </thead>

    <tbody>
    
        @php
            $total = 0;
        @endphp
        @foreach ($vendas as $key => $item)
        <tr>
            <td style="text-align: left;font-size: 20px">{{ $key + 1 }}</td>
            <td style="text-align: left;font-size: 20px">{{ $item->produto->nome }}</td>
            <td style="text-align: left;font-size: 20px">{{ number_format($item->total_quantidade, 0, ',', '.') }}</td>
            <td style="text-align: left;font-size: 20px">{{ number_format($item->total_valor, 2, ',', '.') }}</td>
        </tr>
        @php
            $total += $item->total_valor;
        @endphp
        
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td style="text-align: left;font-size: 20px" class="text-left;" colspan="3">TOTAL</td>
            <td style="text-align: left;font-size: 20px" class="text-left;">{{ number_format($total ?? 0, 2, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

</body>
</html>


<script>
    window.print();
</script>