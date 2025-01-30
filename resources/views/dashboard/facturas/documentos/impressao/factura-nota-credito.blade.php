<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NOTA DE CRÉDITO</title>

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
        
        .marca-dagua-2 {
            position: fixed;
            top: 50%;
            left: 20%;
            text-transform: uppercase;
            transform: translate(-50%, -50%);
            font-size: 40px;
            color: rgba(0, 0, 0, 0.1); /* Cor do texto com transparência */
            z-index: 1000; /* Z-index alto para ficar acima do conteúdo */
            pointer-events: none; /* Evitar que o texto interfira com a interação do usuário */
        }
    </style>

</head>  
    
    @if ($loja->empresa->marca_d_agua_facturas == true)
    <body 
        style="background-image: url('images/empresa/{{ $loja->empresa->logotipo }}'); background-attachment: fixed;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;opacity: .1;margin: 140px;" >
    @endif
    
    @if ($loja->empresa->marca_d_agua_facturas == false)
    <body>
    @endif
    
    @if ($loja->empresa->tipo_factura == "Normal")
        
        @if ($factura->anulado === 'Y')
        <div class="marca-dagua">Anulada</div>
        @endif
        
    
        <header style="position: absolute;top: 30;right: 30px;left: 30px;">
            <table>
                <tr>
                    <td rowspan="">
                        <img src="{{ public_path("images/empresa/".$loja->empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                    </td>
                    <td style="text-align: right">
                        <span style="margin-bottom: 50px">Pág: 1/1</span> <br> <br>
                        {{ date('d-m-Y', strtotime($factura->created_at))  }} <br> <br>
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
                    <td>DADOS CLIENTES</td>
                </tr>
                <tr>
                    <td>
                        <strong>NIF:</strong> {{ $loja->empresa->nif }}
                    </td>
                    <td  style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                        <strong style="font-size: 9px">{{ $factura->cliente->nome }}</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Telefone: </strong> {{ $loja->empresa->telefone }}
                    </td> 
                    <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>NIF:</strong> CONSUMIDOR FINAL {{-- {{ $factura->cliente->nif ?? '99999999999' }} --}} 
                    </td>
                </tr>
               
                <tr>
                    <td>
                        
                    </td>
                    <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>Endereço: </strong> {{ $factura->cliente->morada ?? 'Endereço' }}
                    </td>
                </tr>
                <tr>
                    <td>
                        
                    </td>
                    <td style="border-left: #eaeaea 1px solid; padding: 2px" >
                        <strong>Telefone: </strong>  {{ $factura->cliente->telefone }}
                    </td>
                </tr> 
                
                <tr>
                    <td>
                        <strong>E-mail: </strong> {{ $loja->empresa->website }}
                    </td>
                    <td style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>Conta Corrente N.º:  </strong> {{ $factura->cliente->conta }}
                    </td>
                </tr>
                
            </table>
        </header>
    
        <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
            <table>
                <tr>
                    <td style="font-size: 13px">
                        <strong>Luanda-Angola</strong> <br>
                        <strong>NOTA DE CRÉDITO</strong> <br>
                        <span>Motivo: Anulação</span> <br>
                        <strong>Documento de Origem: {{ $factura->origem->factura_next }}
                        </strong>
                    </td>
                    
                    @if ($factura->convertido_factura == "Y")
                        <td style="font-size: 13px;margin-top: 5px;display: block;float: right"><strong>{{ $factura->factura_next }} conforme {{ $factura->numeracao_proforma }}</strong></td>
                    @else
                        <td style="font-size: 13px;margin-top: 5px;display: block;float: right"><strong>{{ $factura->factura_next }}</strong></td>
                    @endif
                </tr>
                
            </table>
    
            <table>
                <tr>
                    <td style="font-size: 9px;padding: 1px 0">Moeda: <strong>{{ $loja->empresa->moeda ?? 'AOA' }} </strong></td>
                    <td style="font-size: 9px;padding: 1px 0">Data de Emissão: {{ $factura->data_emissao }}</td>
                </tr>
            </table>
            @php
                $numero = 0;
            @endphp
            @if (count($items_facturas) != 0)
                <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                    <thead style="border-bottom: 1px dashed #000;x">
                        <tr>
                            <th style="padding: 2px 0">N.º</th>
                            <th>Descrição</th>
                            <th>Preço Unit.</th>
                            <th>Qtd</th>
                            <th>Un.</th>
                            <th>Desc. %</th>
                            <th>Taxa. %</th>
                            <th style="text-align: right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items_facturas as $item)
                        @php
                            $numero++;
                        @endphp
                        <tr>
                            <td style="padding: 2px 0">{{ $numero }}</td>
                            <td>{{ $item->produto->nome }}</td>
                            <td>{{ number_format($item->preco_unitario, 2, ',', '.')  }} Kz</td>
                            <td>{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                            <td>{{ $item->produto->unidade }}</td>
                            <td>{{ number_format( $item->desconto_aplicado, 1, ',', '.') }}</td>
                            <td>{{ number_format( $item->exibir_imposto_iva($item->iva), 1, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format( $item->valor_pagar, 2, ',', '.') }} KZ</td>
                        </tr>    
                        @endforeach
                    </tbody>
                </table> 
            @endif
    
            <table style="margin-top: 50px ">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th style="padding: 4px 0">Taxa/Valor</th>
                        <th>Incid.Qtd</th>
                        <th>Total</th>
                        <th>Motivo Isenção/Codigo</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($items_facturas) != 0)
                        @if ($total_incidencia_ise >= 0 || $total_iva_ise >= 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">ISENTO</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">0</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA</td>
                                {{-- <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ $motivo ?? "" }}</td> --}}
                            </tr>  
                        @endif
    
                        @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">7</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;"></td>
                            </tr>  
                        @endif
    
                        @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">14 </td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;"></td>
                            </tr>  
                        @endif
    
                    @endif
                </tbody>
            </table> 
           
        </main>
    
        <footer style="position: absolute;bottom: 30;right: 30px;left: 30px;">        
            <table style="margin: 50px 0;">
                <tr>
                    <td>
                        O CLIENTE: <br>
                        _______________________
                    </td>
                </tr>
            </table>
            <table>
                <tr style="float: right">
                    <td>
                        OPERADOR: {{ $factura->user->name }}  <br>
                        _______________________
                    </td>
                </tr>
            </table>
            <table style="border-top: 2px solid #000000">
                <tbody>
                    <tr>
                        <td>COORDENADAS BANCÁRIAS</td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total Inliquido:</strong> {{ number_format($factura->total_incidencia, '2', ',', '.') }}</td>
                    </tr> 
                    <tr>
                        <td>BANCO: {{ $loja->empresa->banco }}</td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total Desconto:</strong> {{ number_format($factura->desconto, '2', ',', '.') }}</td>
                    </tr>   
                    <tr>
                        <td>CONTA: {{ $loja->empresa->conta }}</td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total Imposto:</strong> {{ number_format($factura->total_iva, '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>IBAN: {{ $loja->empresa->iban }}</td>
                        <td style="text-align: right;padding: 3px 0;"></td>
                    </tr>
                    
                    <tr>
                        <td>BANCO: {{ $loja->empresa->banco1 }}</td>
                        <td style="text-align: right;padding: 3px 0;"></td>
                    </tr>   
                    <tr>
                        <td>CONTA: {{ $loja->empresa->conta1 }}</td>
                        <td style="text-align: right;padding: 3px 0;"></td>
                    </tr>
                    <tr>
                        <td>IBAN: {{ $loja->empresa->iban1 }}</td>
                        <td style="text-align: right;padding: 3px 0;"></td>
                    </tr>
                    <tr>
                        <td>Observação: {{ $factura->observacao }}</td>
                        <td style="text-align: right;padding: 3px 0;"></td>
                    </tr>
                    {{-- <tr>
                        <td></td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total:</strong> {{ number_format($factura->valor_total , '2', ',', '.') }}</td>
                    </tr> --}}
                    {{-- <tr>
                        <td></td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total a pagar:</strong> {{ number_format($factura->valor_total , '2', ',', '.') }}</td>
                    </tr> --}}
               
                    {{-- <tr>
                        <td style="padding: 3px 0;margin-top: 30px;display: block;font-style: italic">Os bens/serviços foram colocados à disposição do adquirente na data do documento</td>
                        <td style="text-align: right;padding: 3px 0;">Data de Vencimento: {{ $factura->data_vencimento }}</td>
                    </tr>
    
                    <tr>
                        <td style="padding: 3px 0;margin-top: 30px;display: block;font-style: italic">Os bens/serviços foram colocados à disposição do adquirente na data do documento</td>
                    </tr> --}}
    
                    <tr>
                        <td style="padding: 3px 0;">{{ $factura->obterCaracteres($factura->hash) }}</td>
                        <td style="text-align: right;padding: 3px 0;">{{ date("H:i:s") }}</td>
                    </tr>
    
                    <tr style="">
                        <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">{{ $factura->valor_extenso ?? 'sem descrição do valor por extensão' }}</td>
                    </tr>
    
                    <tr style="">
                        <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">Software de facturação, desenvolvido pela {{ env('APP_NAME') }} </td>
                    </tr>
                    
                </tbody>
            </table>
        </footer>
    @endif
    
    @if ($loja->empresa->tipo_factura == "Ticket")
    
            
    @if ($factura->anulado === 'Y')
    <div class="marca-dagua-2">Anulada</div>
    @endif

    <header style="position: absolute;top: 30;right: 30px;left: 30px;;width: 250px;">
        <table>
            <tr>
                <td rowspan="">
                    <img src="{{ public_path("images/empresa/".$loja->empresa->logotipo) }}" alt="" style="text-align: center;height: 100px;width: 170px;">
                </td>
                <td style="text-align: right">
                    <span style="margin-bottom: 50px">Pág: 1/1</span> <br> <br>
                    {{ date('d-m-Y', strtotime($factura->created_at))  }} <br> <br>
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
                <td>DADOS CLIENTES</td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $loja->empresa->nif }}
                </td>
                <td  style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                    <strong style="font-size: 9px">{{ $factura->cliente->nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone: </strong> {{ $loja->empresa->telefone }}
                </td> 
                <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>NIF:</strong> CONSUMIDOR FINAL {{-- {{ $factura->cliente->nif ?? '99999999999' }} --}} 
                </td>
            </tr>
           
            <tr>
                <td>
                    
                </td>
                <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>Endereço: </strong> {{ $factura->cliente->morada ?? 'Endereço' }}
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
                <td style="border-left: #eaeaea 1px solid; padding: 2px" >
                    <strong>Telefone: </strong>  {{ $factura->cliente->telefone }}
                </td>
            </tr> 
            
            <tr>
                <td>
                    <strong>E-mail: </strong> {{ $loja->empresa->website }}
                </td>
                <td style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>Conta Corrente N.º:  </strong> {{ $factura->cliente->conta }}
                </td>
            </tr>
            
        </table>
    </header>

    <main style="position: absolute;top: 230px;right: 30px;left: 30px;;width: 250px;">
        <table>
            <tr>
                <td style="font-size: 13px">
                    <strong>Luanda-Angola</strong> <br>
                    <strong>NOTA DE CRÉDITO</strong> <br>
                    <span>Motivo: Anulação</span> <br>
                    <strong>Documento de Origem: {{ $factura->origem->factura_next }}
                    </strong>
                </td>
                
                @if ($factura->convertido_factura == "Y")
                    <td style="font-size: 13px;margin-top: 5px;display: block;float: right"><strong>{{ $factura->factura_next }} conforme {{ $factura->numeracao_proforma }}</strong></td>
                @else
                    <td style="font-size: 13px;margin-top: 5px;display: block;float: right"><strong>{{ $factura->factura_next }}</strong></td>
                @endif
            </tr>
            
        </table>

        <table>
            <tr>
                <td style="font-size: 9px;padding: 1px 0">Moeda: <strong>{{ $loja->empresa->moeda ?? 'AOA' }} </strong></td>
                <td style="font-size: 9px;padding: 1px 0">Data de Emissão: {{ $factura->data_emissao }}</td>
            </tr>
        </table>
        @php
            $numero = 0;
        @endphp
        @if (count($items_facturas) != 0)
            <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                <thead style="border-bottom: 1px dashed #000;x">
                    <tr>
                        <th style="padding: 2px 0">N.º</th>
                        <th>Descrição</th>
                        <th>Preço Unit.</th>
                        <th>Qtd</th>
                        <th>Un.</th>
                        <th>Desc. %</th>
                        <th>Taxa. %</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items_facturas as $item)
                    @php
                        $numero++;
                    @endphp
                    <tr>
                        <td style="padding: 2px 0">{{ $numero }}</td>
                        <td>{{ $item->produto->nome }}</td>
                        <td>{{ number_format($item->preco_unitario, 2, ',', '.')  }} Kz</td>
                        <td>{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                        <td>{{ $item->produto->unidade }}</td>
                        <td>{{ number_format( $item->desconto_aplicado, 1, ',', '.') }}</td>
                        <td>{{ number_format( $item->exibir_imposto_iva($item->iva), 1, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format( $item->valor_pagar, 2, ',', '.') }} KZ</td>
                    </tr>    
                    @endforeach
                </tbody>
            </table> 
        @endif

        <table style="margin-top: 50px ">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th style="padding: 4px 0">Taxa/Valor</th>
                    <th>Incid.Qtd</th>
                    <th>Total</th>
                    <th>Motivo Isenção/Codigo</th>
                </tr>
            </thead>
            <tbody>
                @if (count($items_facturas) != 0)
                    @if ($total_incidencia_ise >= 0 || $total_iva_ise >= 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">ISENTO</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">0</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA</td>
                            {{-- <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ $motivo ?? "" }}</td> --}}
                        </tr>  
                    @endif

                    @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">7</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;"></td>
                        </tr>  
                    @endif

                    @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">14 </td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;"></td>
                        </tr>  
                    @endif

                @endif
            </tbody>
        </table> 
        
        <table style="margin: 50px 0;">
            <tr>
                <td>
                    O CLIENTE: <br>
                    _______________________
                </td>
            </tr>
        </table>
        <table>
            <tr style="float: right">
                <td>
                    OPERADOR: {{ $factura->user->name }}  <br>
                    _______________________
                </td>
            </tr>
        </table>
        <table style="border-top: 2px solid #000000">
            <tbody>
                <tr>
                    <td>COORDENADAS BANCÁRIAS</td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total Inliquido:</strong> {{ number_format($factura->total_incidencia, '2', ',', '.') }}</td>
                </tr> 
                <tr>
                    <td>BANCO: {{ $loja->empresa->banco }}</td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total Desconto:</strong> {{ number_format($factura->desconto, '2', ',', '.') }}</td>
                </tr>   
                <tr>
                    <td>CONTA: {{ $loja->empresa->conta }}</td>
                    <td style="text-align: right;padding: 3px 0;"><strong>Total Imposto:</strong> {{ number_format($factura->total_iva, '2', ',', '.') }}</td>
                </tr>
                <tr>
                    <td>IBAN: {{ $loja->empresa->iban }}</td>
                    <td style="text-align: right;padding: 3px 0;"></td>
                </tr>
                
                <tr>
                    <td>BANCO: {{ $loja->empresa->banco1 }}</td>
                    <td style="text-align: right;padding: 3px 0;"></td>
                </tr>   
                <tr>
                    <td>CONTA: {{ $loja->empresa->conta1 }}</td>
                    <td style="text-align: right;padding: 3px 0;"></td>
                </tr>
                <tr>
                    <td>IBAN: {{ $loja->empresa->iban1 }}</td>
                    <td style="text-align: right;padding: 3px 0;"></td>
                </tr>
                <tr>
                    <td>Observação: {{ $factura->observacao }}</td>
                    <td style="text-align: right;padding: 3px 0;"></td>
                </tr>
  
                <tr>
                    <td style="padding: 3px 0;">{{ $factura->obterCaracteres($factura->hash) }}</td>
                    <td style="text-align: right;padding: 3px 0;">{{ date("H:i:s") }}</td>
                </tr>

                <tr style="">
                    <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">{{ $factura->valor_extenso ?? 'sem descrição do valor por extensão' }}</td>
                </tr>

                <tr style="">
                    <td style="padding: 3px 0; text-align: center;border-top: 2px solid #000000" colspan="2">Software de facturação, desenvolvido pela {{ env('APP_NAME') }} </td>
                </tr>
                
            </tbody>
        </table>
       
    </main>

    @endif
</body>
</html>

