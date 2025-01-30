<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }}</title>

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

        th,
        td {
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


@if ($loja->empresa->marca_d_agua_facturas == true)
<body style="background-image: url('images/empresa/{{ $loja->empresa->logotipo }}'); background-attachment: fixed;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: contain;opacity: .1;margin: 140px;">
    @endif

    @if ($loja->empresa->marca_d_agua_facturas == false)
    <body>
        @endif
        
        @if ($loja->empresa->tipo_factura == "Normal")
            <header style="position: absolute;top: 30;right: 30px;left: 30px;">
                <table>
                    <tr>
                        <td rowspan="">
                            <img src="{{ public_path("images/empresa/".$loja->empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                        </td>
                        <td style="text-align: right">
                            <span>Pág: 1/1</span> <br> <br>
                            {{ date('d-m-Y', strtotime($movimento->created_at))  }} <br> <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">
                            <strong style="text-transform: uppercase">{{ $loja->empresa->nome }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">Endereço:</strong> {{ $loja->empresa->morada }}
                        </td>
                        <td>Dados do Operador</td>
                    </tr>
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">NIF:</strong> {{ $loja->empresa->nif }}
                        </td>
                        <td style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                            <strong style="text-transform: uppercase">{{ $movimento->user->name }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">Telefone: </strong> {{ $loja->empresa->telefone ?? '244 222 222 222'}}
                        </td>
                        <td style="border-left: #eaeaea 1px solid; padding: 2px">
                            <strong style="text-transform: uppercase">E-mail: {{ $movimento->user->email ?? '99999999999' }}</strong>
                        </td>
                    </tr>
    
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">E-mail: </strong> {{ $loja->empresa->website }}
                        </td>
                  
                    </tr>
    
                </table>
            </header>
    
            <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
                <table>
                    
                    <tr>
                        <td style="text-transform: uppercase;padding: 10px 0;font-size: 11px;font-weight: bolder;border-bottom: 1px solid #000000">Luanda-Angola <br><br> {{ $titulo }}</td>
                    </tr>
                                   
                    <tr>
                        <td style="text-transform: uppercase;font-weight: bolder;padding: 10px 0;font-size: 13px;border-bottom: 1px solid #000000">{{ $movimento->numero }}</td>
                    </tr>
    
                </table>
    
                <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                    <thead style="border-bottom: 1px dashed #000;x">
                        <tr>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">Motante</th>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">Conta</th>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">Forma Saída</th>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ number_format( $movimento->motante, 2, ',', '.') }}</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ $movimento->caixa->nome }}</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">NUMERÁRIO</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ $movimento->data_at }}</td>
                        </tr>
                        
                        <tr>
                            <th colspan="4" style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: left">OBSERVAÇÕES</th>
                        </tr>
                        <tr>
                            <td colspan="4" style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: left">{{ $movimento->descricao }}</td>
                        </tr>
                    </tbody>
                </table>
    
            </main>
        @endif
      
        @if ($loja->empresa->tipo_factura == "Ticket")
        
            <header style="position: absolute;top: 30;right: 30px;left: 30px;width: 250px;">
                <table>
                    <tr>
                        <td rowspan="">
                            <img src="{{ public_path("images/empresa/".$loja->empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                        </td>
                        <td style="text-align: right">
                            <span>Pág: 1/1</span> <br> <br>
                            {{ date('d-m-Y', strtotime($movimento->created_at))  }} <br> <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0;">
                            <strong style="text-transform: uppercase">{{ $loja->empresa->nome }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">Endereço:</strong> {{ $loja->empresa->morada }}
                        </td>
                        <td>Dados do Operador</td>
                    </tr>
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">NIF:</strong> {{ $loja->empresa->nif }}
                        </td>
                        <td style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                            <strong style="text-transform: uppercase">{{ $movimento->user->name }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">Telefone: </strong> {{ $loja->empresa->telefone ?? '244 222 222 222'}}
                        </td>
                        <td style="border-left: #eaeaea 1px solid; padding: 2px">
                            <strong style="text-transform: uppercase">E-mail: {{ $movimento->user->email ?? '99999999999' }}</strong>
                        </td>
                    </tr>
    
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">E-mail: </strong> {{ $loja->empresa->website }}
                        </td>
                  
                    </tr>
    
                </table>
            </header>
    
            <main style="position: absolute;top: 260px;right: 30px;left: 30px;width: 250px;">
                <table>
                    
                    <tr>
                        <td style="text-transform: uppercase;padding: 10px 0;font-size: 11px;font-weight: bolder;border-bottom: 1px solid #000000">Luanda-Angola <br><br> {{ $titulo }}</td>
                    </tr>
                                   
                    <tr>
                        <td style="text-transform: uppercase;font-weight: bolder;padding: 10px 0;font-size: 13px;border-bottom: 1px solid #000000">{{ $movimento->numero }}</td>
                    </tr>
    
                </table>
    
            
                <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                    <thead style="border-bottom: 1px dashed #000;x">
                        <tr>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: left">Motante</th>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">Conta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: left">{{ number_format( $movimento->motante, 2, ',', '.') }}</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ $movimento->caixa->nome }}</td>
                        </tr>
                    </tbody>
                </table>
                
                
                <table style="border-top: 2px solid #000000">
                    <tbody>
                        <tr>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: left">Forma Saída</th>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">Data</th>
                        </tr>
    
                        <tr>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: left">NUMERÁRIO</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ $movimento->data_at }}</td>
                        </tr>
    
                        <tr>
                            <th colspan="2" style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: left">OBSERVAÇÕES</th>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: left">{{ $movimento->descricao }}</td>
                        </tr>
                    </tbody>
                </table>
            </main>
        @endif
 
    </body>
</html>
