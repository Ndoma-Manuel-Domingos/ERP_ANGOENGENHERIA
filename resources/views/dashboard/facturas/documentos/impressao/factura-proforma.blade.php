<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FACTURA PRÓ-FORMA</title>

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
                        <strong>NIF: {{ $factura->cliente->nif ?? '99999999999' }}</strong>
                    </td>
                </tr>
               
                <tr>
                    <td>
                        
                    </td>
                    <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>Endereço: </strong> {{ $factura->cliente->morada ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td>
                        
                    </td>
                    <td style="border-left: #eaeaea 1px solid; padding: 2px" >
                        <strong>Telefone: </strong>  {{ $factura->cliente->telefone ?? '' }}
                    </td>
                </tr> 
                
                <tr>
                    <td>
                        <strong>E-mail: </strong> {{ $loja->empresa->website }}
                    </td>
                    <td style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>Conta Corrente N.º:  </strong> {{ $factura->cliente->conta ?? '31.1.2.1.1' }}
                    </td>
                </tr>
    
                
            </table>
        </header>
    
        <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
            <table>
                <tr>
                    <td style="font-size: 13px">
                        <strong>Luanda-Angola</strong> <br>
                        <strong>FACTURA PROFORMA</strong>
                    </td>
                </tr>
    
                <tr style="margin-top: 29px;display: block">
                    <td style="font-size: 13px;margin-top: 5px;display: block"><strong>{{ $factura->factura_next }}</strong></td>
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
                            <th style="text-align: right">Qtd</th>
                            <th style="text-align: right">Preço Unitário</th>
                            <th style="text-align: right">Un.</th>
                            <th style="text-align: right">Desconto</th>
                            <th style="text-align: right">Taxa%</th>
                            <th style="text-align: right">Retenção</th>
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
                            <td style="text-align: right">{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->preco_unitario, 2, ',', '.')  }}</td>
                            <td style="text-align: right">un</td>
                            <td style="text-align: right">{{ number_format( $item->desconto_aplicado, 1, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format( $item->exibir_imposto_iva($item->iva), 1, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format( $item->retencao_fonte, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format( $item->valor_pagar - $item->retencao_fonte, 2, ',', '.') }}</td>
                        </tr>    
                        @endforeach
                    </tbody>
                </table> 
            @endif
    
            <table style="margin-top: 50px ">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th style="padding: 4px 0">Taxa%</th>
                        <th>Incidência</th>
                        <th>Valor Imposto</th>
                        <th>Motivo de Isenção</th>
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
                                <td style="padding: 2px 0;border-top: 1px dashed #000;">Isento nos termos da alínea d) do nº1 do artigo 12.º
                                    do CIVA </td>
                                {{-- <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ $motivo }}</td> --}}
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
            <table>
                <tr>
                    <td>
                        OPERADOR: {{ $factura->user->name }}  <br>
                        _______________________
                    </td>
                </tr>
            </table>
            <table style="border-top: 2px solid #000000">
                <tbody>
                    <tr>
                        <td></td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total Ilíquido:</strong> {{ number_format($factura->total_incidencia, '2', ',', '.') }}</td>
                    </tr> 
                    <tr>
                        <td></td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total Desconto:</strong> {{ number_format($factura->desconto, '2', ',', '.') }}</td>
                    </tr>   
                    <tr>
                        <td>Observação: {{ $factura->observacao }}</td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total Imposto:</strong> {{ number_format($factura->total_iva, '2', ',', '.') }}</td>
                    </tr>
                    
                    <tr>
                        <td></td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Retenção Na fonte:</strong> {{ number_format($factura->total_retencao_fonte , '2', ',', '.') }}</td>
                    </tr>
                
                    <tr>
                        <td></td>
                        <td style="text-align: right;padding: 3px 0;"><strong>Total á pagar:</strong> {{ number_format($factura->valor_total , '2', ',', '.') }}</td>
                    </tr>
               
                    <tr>
                        <td style="padding: 3px 0;color: red"><h1>Este documento não serve como factura</h1></td>
                    </tr>
    
               
                    {{-- <tr>
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
        
        <header style="position: absolute;top: 30;right: 30px;left: 30px;width: 250px;">
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
                    <td style="padding: 5px 0;text-transform: uppercase;font-weight: bolder">{{ $loja->empresa->nome }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;text-transform: uppercase;font-weight: bolder">Endereço: {{ $loja->empresa->morada }}</td>
                    <td>DADOS CLIENTES</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;text-transform: uppercase;font-weight: bolder"> NIF: {{ $loja->empresa->nif }} </td>
                    <td  style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;font-weight: bolder">{{ $factura->cliente->nome }}</strong></td>
                </tr>
                <tr>
                    <td>
                        <strong style="text-transform: uppercase">Telefone: </strong> {{ $loja->empresa->telefone }}
                    </td> 
                    <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong style="text-transform: uppercase">NIF: {{ $factura->cliente->nif ?? '99999999999' }}</strong>
                    </td>
                </tr>
               
                <tr>
                    <td>
                        <strong style="text-transform: uppercase">E-mail: </strong> <span style="text-transform: uppercase">{{ $loja->empresa->website }}</span>
                    </td>
                    <td  style="border-left: #eaeaea 1px solid; padding: 2px"></td>
                </tr>
                
            </table>
        </header>
    
        <main style="position: absolute;top: 230px;right: 30px;left: 30px;width: 250px;">
        
            <table>
                <tr>
                    <td style="text-transform: uppercase;padding: 10px 0;font-size: 11px;font-weight: bolder">Luanda-Angola <br><br> FACTURA PRO-FORMA</td>
                </tr>
                <tr>
                    <td style="text-transform: uppercase;font-weight: bolder;font-size: 13px">{{ $factura->factura_next }}</td>
                </tr>
            </table>
    
            <table>
                <tr>
                    <td style="font-size: 10px;padding: 1px 0;text-transform: uppercase;color: #222222;font-weight: bolder">Moeda: <strong>{{ $loja->empresa->moeda ?? 'AOA' }} </strong></td>
                    <td style="font-size: 10px;padding: 1px 0;text-transform: uppercase;color: #222222;font-weight: bolder">Data de Emissão: {{ $factura->data_emissao }}</td>
                </tr>
            </table>
            @php
                $numero = 0;
            @endphp
            @if (count($items_facturas) != 0)
            <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                <thead style="border-bottom: 1px dashed #000;x">
                    <tr>
                        <th style="text-align: left;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Desc</th>
                        <th style="text-align: center;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Qtd</th>
                        <th style="text-align: center;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">P.Unit</th>
                        <th style="text-align: center;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Un</th>
                        <th style="text-align: center;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Des</th>
                        <th style="text-align: center;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Taxa%</th>
                        <th style="padding: 2px;text-align: right;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items_facturas as $item)
                    @php
                        $numero++;
                    @endphp
                    <tr>
                        <td colspan="8" style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #474747;font-weight: bolder;font-size: 10px">{{ $item->produto->nome }}</td>
                    </tr>    
                    <tr>
                        <td style="border-bottom: 1px solid #000"></td>
                        <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                        <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">{{ number_format($item->preco_unitario, 2, ',', '.')  }}</td>
                        <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">{{ $item->produto->unidade }}</td>
                        <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">{{ number_format( $item->desconto_aplicado, 1, ',', '.') }}</td>
                        <td style="font-weight: bolder;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder;border-bottom: 1px solid #000">{{ number_format( $item->exibir_imposto_iva($item->iva), 1, ',', '.') }}</td>
                        <td style="text-align: right;font-weight: bolder;padding: 3px 0;border-bottom: 1px solid #000">{{ number_format( $item->valor_pagar, 2, ',', '.') }}</td>
                    </tr>    
                    @endforeach
                </tbody>
            </table> 
            @endif
    
            <table style="margin-top: 50px ">
                <thead>
                    <tr>
                        <th style="text-align: left;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;text-align: center">Desc.</th>
                        <th style="text-align: left;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;text-align: center">Taxa%</th>
                        <th style="text-align: left;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;text-align: center">Incid.</th>
                        <th style="text-align: left;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;text-align: center">Imposto</th>
                        <th style="text-align: left;padding: 2px;text-transform: uppercase;color: #222222;font-weight: bolder;text-align: center">Mot.Isenção</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($items_facturas) != 0)
                        @if ($total_incidencia_ise >= 0 || $total_iva_ise >= 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">ISENTO</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">0</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                                    <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">Isento nos termos da alínea d) do nº1 do artigo 12.º
                                    do CIVA </td>
                            </tr>  
                        @endif
    
                        @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">IVA</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">7</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center"></td>
                            </tr>  
                        @endif
    
                        @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                            <tr>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">IVA</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">14</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center">{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                                <td style="padding: 2px 0;border-top: 1px dashed #000;font-weight: bolder;text-align: center"></td>
                            </tr>  
                        @endif
    
    
                    @endif
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
                        <td style="text-align: left;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder"><strong>Total Ilíquido:</strong> {{ number_format($factura->total_incidencia, '2', ',', '.') }}</td>
                    </tr> 
                    <tr>
                        <td></td>
                        <td style="text-align: left;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder"><strong>Total Desconto:</strong> {{ number_format($factura->desconto, '2', ',', '.') }}</td>
                    </tr>   
                    <tr>
                        <td>Observação: {{ $factura->observacao }}</td>
                        <td style="text-align: left;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder"><strong>Total Imposto:</strong> {{ number_format($factura->total_iva, '2', ',', '.') }}</td>
                    </tr>
                
                    <tr>
                        <td></td>
                        <td style="text-align: left;padding: 3px 0;text-transform: uppercase;color: #222222;font-weight: bolder"><strong>Total á pagar:</strong> {{ number_format($factura->valor_total , '2', ',', '.') }}</td>
                    </tr>
               
                    <tr>
                        <td style="padding: 3px 0;margin-top: 30px;display: block;color: red">Retenção: {{ number_format($total_retencao , '2', ',', '.')}}</td>
                    </tr>
    
                    <tr>
                        <td style="padding: 3px 0;color: red"><h1>Este documento não serve como factura</h1></td>
                    </tr>
               
                    <tr>
                        <td style="padding: 3px 0;">{{ $factura->obterCaracteres($factura->hash) }}</td>
                        <td style="text-align: left;padding: 3px 0;">{{ date("H:i:s") }}</td>
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

