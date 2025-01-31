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


        @if ($factura->anulado === 'Y')
        <div class="marca-dagua">Anulada</div>
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
                            {{ date('d-m-Y', strtotime($factura->created_at))  }} <br> <br>
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
                        <td>DADOS CLIENTES</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>NIF:</strong> {{ $loja->empresa->nif }}
                        </td>
                        <td style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                            <strong style="font-size: 9px">{{ $factura->cliente->nome }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Telefone: </strong> {{ $loja->empresa->telefone ?? '244 222 222 222'}}
                        </td>
                        <td style="border-left: #eaeaea 1px solid; padding: 2px">
                            <strong>NIF: {{ $factura->cliente->nif ?? '99999999999' }}</strong>
                        </td>
                    </tr>
    
                    <tr>
                        <td>
    
                        </td>
                        <td style="border-left: #eaeaea 1px solid; padding: 2px">
                            <strong>Endereço: </strong> {{ $factura->cliente->morada ?? 'Endereço' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
    
                        </td>
                        <td style="border-left: #eaeaea 1px solid; padding: 2px">
                            <strong>Telefone: </strong> {{ $factura->cliente->telefone ?? '244 222 222 222' }}
                        </td>
                    </tr>
    
                    <tr>
                        <td>
                            <strong>E-mail: </strong> {{ $loja->empresa->website }}
                        </td>
                        <td style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                            <strong>Conta Corrente N.º: </strong> {{ $factura->cliente->conta ?? '31.1.2.1.1' }}
                        </td>
                    </tr>
    
    
                </table>
            </header>
    
            <main style="position: absolute;top: 290px;right: 30px;left: 30px;">
                <table>
                    <tr>
                        <td style="font-size: 13px">
                            <strong>Luanda-Angola</strong> <br>
                            <strong>RECIBO</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px">
                            <strong>ORIGINAL</strong>
                        </td>
                        <td style="text-align: right;font-size: 13px;margin-top: 5px;display: block"><strong>{{ $factura->factura_next }}</strong></td>
                    </tr>
    
                </table>
    
                @php
                $numero = 0;
                @endphp
                <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                    <thead style="border-bottom: 1px dashed #000;x">
                        <tr>
                            <th style="padding: 2px 0">N.º</th>
                            <th>Data documento</th>
                            <th>Referência Factura</th>
                            <th>Total da Factura</th>
                            <th>Total Imposto</th>
                            <th>Valor Pago</th>
                            <th>Valor A Pagar</th>
                            <th style="text-align: right;padding: 3px 0;">Dívida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 2px 0">1</td>
                            <td>{{ $factura->data_emissao }}</td>
                            <td>{{ $factura->facturas->factura_next }} </td>
                            <td>{{ number_format( $factura->facturas->valor_total, 2, ',', '.') }}</td>
                            <td>{{ number_format( $factura->total_iva, 2, ',', '.') }}</td>
                            <td>{{ number_format( $factura->facturas->valor_pago, 2, ',', '.') }}</td>
                            <td>{{ number_format( $factura->valor_total, 1, ',', '.') }}</td>
                            <td style="text-align: right;padding: 3px 0;">{{ number_format($factura->facturas->valor_divida, 1, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
    
            </main>
    
            <footer style="position: absolute;bottom: 30;right: 30px;left: 30px;">
                <table>
                    <tr>
                        <td>
                            OPERADOR: {{ $factura->user->name }} <br>
                            _______________________
                        </td>
                    </tr>
                </table>
                <table style="border-top: 2px solid #000000">
                    <tbody>
    
                        <tr>
                            <td></td>
                            <td style="text-align: right;padding: 3px 0;"><strong>Total:</strong> {{ number_format($factura->valor_total , '2', ',', '.') }}</td>
                        </tr>
    
                        {{-- <tr>
                        <td style="padding: 3px 0;margin-top: 30px;display: block;font-style: italic">Os bens/serviços foram colocados à disposição do adquirente na data do documento</td>
                    </tr> --}}
    
                        <tr>
                            {{-- <td style="padding: 3px 0;">{{ $factura->obterCaracteres($factura->hash) }}</td> --}}
                            <td style="padding: 3px 0;">-Processado por programa validado Nº 0000/AGT/2024 EA-Viegas</td>
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
            </footer>
            
        @endif
        
        @if ($loja->empresa->tipo_factura == "Ticket")
        
            <header style="position: absolute;top: 30;right: 30px;left: 30px;width: 250px;">
                <table>
                    <tr>
                        <td rowspan="">
                            <img src="{{ public_path("images/empresa/".$loja->empresa->logotipo) }}" alt="" style="text-align: center;height: 80px;width: 70px;">
                        </td>
                        <td style="text-align: right">
                            <span>Pág: 1/1</span> <br> <br>
                            {{ date('d-m-Y', strtotime($factura->created_at))  }} <br> <br>
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
                        <td>DADOS CLIENTES</td>
                    </tr>
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">NIF:</strong> {{ $loja->empresa->nif }}
                        </td>
                        <td style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                            <strong style="text-transform: uppercase">{{ $factura->cliente->nome }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">Telefone: </strong> {{ $loja->empresa->telefone ?? '244 222 222 222'}}
                        </td>
                        <td style="border-left: #eaeaea 1px solid; padding: 2px">
                            <strong style="text-transform: uppercase">NIF: {{ $factura->cliente->nif ?? '99999999999' }}</strong>
                        </td>
                    </tr>
    
                    <tr>
                        <td>
                            <strong style="text-transform: uppercase">E-mail: </strong> {{ $loja->empresa->website }}
                        </td>
                  
                    </tr>
    
                </table>
            </header>
    
            <main style="position: absolute;top: 230px;right: 30px;left: 30px;width: 250px;">
                <table>
                    
                    <tr>
                        <td style="text-transform: uppercase;padding: 10px 0;font-size: 11px;font-weight: bolder">Luanda-Angola <br><br> RECIBO</td>
                    </tr>
                                   
                    <tr>
                        <td style="text-transform: uppercase;font-weight: bolder;font-size: 13px">ORIGINAL  </td>
                        <td style="text-align: right;text-transform: uppercase;font-weight: bolder;font-size: 13px"><strong>{{ $factura->factura_next }}</strong></td>
                    </tr>
    
                </table>
    
                @php
                $numero = 0;
                @endphp
                <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                    <thead style="border-bottom: 1px dashed #000;x">
                        <tr>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">N.º</th>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Data doc.</th>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Refe. Fact.</th>
                            <th style="padding: 2px;text-align: left;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Total da Fact.</th>
                            <th style="padding: 2px;text-align: right;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Imposto</th>
                            <th style="padding: 2px;text-align: right;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Pago</th>
                            <th style="padding: 2px;text-align: right;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Dívida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">1</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">{{ $factura->data_emissao }}|</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">{{ $factura->facturas->factura_next }}|</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ number_format( $factura->facturas->valor_total, 2, ',', '.') }}|</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ number_format( $factura->total_iva, 2, ',', '.') }}|</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ number_format( $factura->facturas->valor_pago, 1, ',', '.') }}|</td>
                            <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000;text-align: right">{{ number_format( $factura->facturas->valor_divida, 1, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tr>
                        <td style="text-transform: uppercase;color: #222222;font-weight: bolder">
                            OPERADOR: {{ $factura->user->name }}  <br>
                            _______________________
                        </td>
                    </tr>
                </table>
                <table style="border-top: 2px solid #000000">
                    <tbody>
    
                        <tr>
                            <td></td>
                            <td style="text-align: right;padding: 3px 0;"><strong>Total: {{ $factura->valor_total }} </strong> {{ number_format($factura->valor_total , '2', ',', '.') }}</td>
                        </tr>
    
                        <tr>
                            <td style="padding: 3px 0;">{{ $factura->obterCaracteres($factura->hash) }}</td>
                            <td style="text-align: right;padding: 3px 0;">{{ date("H:i:s") }}</td>
                        </tr>
    
                        <tr style="">
                            {{-- <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">{{ $factura->valor_extenso ?? 'sem descrição do valor por extensão' }}</td> --}}
                        </tr>
    
                        <tr style="">
                            <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">Software de facturação, desenvolvido pela {{ env('APP_NAME') }}</td>
                        </tr>
    
                    </tbody>
                </table>
            </main>
        @endif
            
    </body>
</html>
