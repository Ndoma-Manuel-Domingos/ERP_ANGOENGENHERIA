<!DOCTYPE html>
<html lang="pt-pt">

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
            width: 100%;
        }
        .table{
            width: 95%;
        }

        .table th {
            text-align: left;
            border-bottom: #000000 dashed 1px;
            padding: 5px;
        }

        .table tr td {
            text-align: left;
            border-bottom: #d7d7d7 dashed 1px;
            padding: 5px;
        }

        h1{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            text-transform: uppercase;
            border-bottom: 1px solid #000000;
            /* margin: 0 auto; */
            margin-bottom: 10px;
            padding-bottom: 5px;
            width: 95%;
        }
    </style>
</head>

<body>
    <header>
        <h1>{{ $titulo }}</h1>
    </header>

    <table class="table">
        <thead>
            <tr>
                <th>Factura</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Vencimento</th>
                <th>Estado</th>
                <th style="text-align: right">Dívida</th>
            </tr>
        </thead>
        <tbody>
            @if ($facturas)
            @foreach ($facturas as $item)
            <tr>
                <td>{{ $item->factura_next}}</td>
                <td>{{ $item->cliente->nome }}</td>
                <td>{{ $item->data_emissao }}</td>
                <td>{{ $item->data_vencimento }}</td>
                <td class="text-uppercase"><span class="bg-warning p-1"><i class="fas fa-exclamation-triangle"></i></span> {{ $item->status_factura }}</td>
                <td style="text-align: right">{{ number_format($item->valor_total, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

</body>

</html>