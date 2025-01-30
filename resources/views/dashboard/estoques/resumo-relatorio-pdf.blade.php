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

    <table style="margin-top: 10px">
        <thead>
          <tr>
            <th style="text-align: left" class="td">Produto</th>
            <th style="text-align: left" class="td">P. Custo</th>
            <th style="text-align: left" class="td">Stock</th>
            <th style="text-align: left" class="td">Stock Valor</th>
            <th style="text-align: left" class="td">Stock Previsão</th>
            <th style="text-align: left" class="td">Venda/Dia</th>
            <th style="text-align: left" class="td">Uni. Vendidas</th>
            <th style="text-align: left" class="td">Uni. Entrada stock</th>
          </tr>
        </thead>
        <tbody>
          @if ($resultados)
            @foreach ($resultados as $item2)
              @php
                $total = $item2->produto->preco_custo * $item2->stock;
              @endphp
              <tr>
                <td class="td">
                  {{ $item2->produto->nome }}<br> 
                  <small>{{ $item2->produto->referencia }}</small>
                </td>
                <td class="td">
                  {{ number_format($item2->produto->preco_custo, 2, ',', '.') }} {{ $loja->empresa->moeda }} <br>
                  <small>PVP: {{ number_format($item2->produto->preco_venda, 2, ',', '.') }} {{ $loja->empresa->moeda }} </small>
                </td>
                <td class="td">{{ $item2->stock }}</td>
                <td class="td">{{ number_format($item2->produto->preco_custo * $item2->stock, 2, ',', '.') }} {{ $loja->empresa->moeda }}</td>
                <td class="td">*</td>
                <td class="td">0 Uni</td>
                <td class="td">0 Uni</td>
                <td class="td">{{ number_format($item2->stock, 0, ',', '.') }} Uni</td>
              </tr>     
            @endforeach
          @else
            <tr>
              <td colspan="8">Não foram encontrados resultados</td>
            </tr>
          @endif

        </tbody>
      </table>

    
</body>
</html>