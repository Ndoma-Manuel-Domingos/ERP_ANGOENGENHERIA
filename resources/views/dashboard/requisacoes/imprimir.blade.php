<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REQUISIÇÂO DE PRODUTOS</title>

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
    @if ($empresa->marca_d_agua_facturas == true)
    <body 
        style="background-image: url('images/empresa/{{ $empresa->logotipo }}'); background-attachment: fixed;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;opacity: .1;margin: 140px;" >
    @endif
    
    @if ($empresa->marca_d_agua_facturas == false)
    <body>
    @endif

    <header style="position: absolute;top: 30;right: 30px;left: 30px;">
        <table>
            <tr>
                <td rowspan="">
                    <img src="{{ public_path("images/empresa/".$empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">
                    <strong>{{ $empresa->nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Endereço:</strong> {{ $empresa->morada }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $empresa->nif }}
                </td>
              
            </tr>
            <tr>
                <td>
                    <strong>Telefone: </strong> {{ $empresa->telefone }}
                </td> 
              
            </tr>
          
            <tr>
                <td>
                    <strong>E-mail: </strong> {{ $empresa->website }}
                </td>
            </tr>
            
        </table>
    </header>

    <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
        <table>
            <tr>
                <td style="font-size: 13px">
                    <strong>REQUISIÇÃO DE PRODUTOS Nº {{ $requisicao->numero }}</strong>
                </td>
            </tr>

        </table>

        <table>
            <tr>
                <td style="font-size: 9px;padding: 1px 0">Estado: <strong>{{ $requisicao->status }} </strong></td>
            </tr>
        </table>
        @php
            $numero = 0;
        @endphp
        <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
            <thead style="border-bottom: 1px dashed #000;x">
                <tr>
                    <th style="padding: 2px 0">N.º</th>
                    <th>Descrição</th>
                    <th>Qtd</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requisicao->items as $item)
                @php
                    $numero++;
                @endphp
                <tr>
                    <td style="padding: 2px 0">{{ $numero }}</td>
                    <td>{{ $item->produto->nome }}</td>
                    <td>{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                </tr>    
                @endforeach
            </tbody>
        </table> 
    
    </main>

</body>
</html>

