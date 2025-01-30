@extends('layouts.pdf')

@section('pdf-content')

<header>
    <table style="border: 0">
        <tr>
            <td style="border: 0;">
                <img src= "images/empresa/{{ $empresa->logotipo }}" style="height: 100px;width: 100px">
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
</header>

<table>
    <thead>
        <tr>
            <th colspan="7" style="text-transform: uppercase"> {{ $titulo }}</th>
        </tr>
        <tr>
            <th style="width: 100px">Ref</th>
            <th>Produto</th>
            <th>Tipo</th>
            <th style="text-align: right">Preço</th>
            <th style="text-align: right">Preço Fornecedor</th>
            <th style="text-align: right">IVA %</th>
            @if ($lojas)
                @foreach ($lojas as $loja)
                    <th style="text-align: right">{{ $loja->nome }}</th>
                @endforeach                        
            @endif
        </tr>
    </thead>

    <tbody>
        @foreach ($produtos as $produto)
            <tr>
                <td>{{ $produto->referencia }} </td>
                <td>{{ $produto->nome }} <br><small>{{ $produto->categoria->categoria }}</small></td>
                @if($produto->tipo == 'P')
                  <td>Produto</td>
                @endif 
                @if($produto->tipo == 'S')
                  <td>Serviço</td>
                @endif 
                @if($produto->tipo == 'O')
                  <td>Outro (portes, adiantamentos, etc.)</td>
                @endif
                @if($produto->tipo == 'I')
                <td>Imposto (excepto IVA  e IS) ou Encargo Parafiscal</td>
                @endif
                @if($produto->tipo == 'E')
                <td>Imposto Especial de Consumo (IABA, ISP e IT)</td>
                @endif
                <td style="text-align: right">{{ number_format($produto->preco_venda, 2, ',', '.') }} 
                    <span class="text-secondary">{{ $empresa->moeda }}</span> <br> 
                    <small>S/IVA: {{ number_format($produto->preco, 2, ',', '.') }} 
                    <span class="text-secondary">{{ $empresa->moeda }}</span></small>
                </td>
                <td style="text-align: right">{{ number_format($produto->preco_custo, 2, ',', '.') }} 
                    <span class="text-secondary">{{ $empresa->moeda }}</span>
                </td>
                <td style="text-align: right">{{ number_format($produto->taxa_imposto->valor, 2, ',', '.') }}</td>
                @foreach ($lojas as $loja)
                    @php
                        $estoque = App\Models\Estoque::where('loja_id', $loja->id)
                        ->where('produto_id', $produto->id)
                        ->first();
                    @endphp
                    @if ($estoque)
                        <td style="text-align: right"> <span class="bg-info p-1 text-center">{{ number_format($estoque->stock, 2, ',', '.')  }}</span></td>
                    @else
                        <td style="text-align: right"> <span class="bg-info p-1 text-center">0</span></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
    
    <tfoot>
        <tr>
            <th style="padding: 7px">TOTAL REGISTRO: {{ count($produtos)  }}</th>
        </tr>
    </tfoot>
</table>

@endsection