@extends('layouts.pdf')

@section('pdf-content')

<table style="border: 0">
    <tr>
        <td style="border: 0;">
            <img src="images/empresa/{{ $tipo_entidade_logado->empresa->logotipo }}" style="height: 100px;width: 100px">
        </td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0">{{ $tipo_entidade_logado->empresa->nome }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>NIF: </strong>{{ $tipo_entidade_logado->empresa->nif }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>Endereço: </strong>{{ $tipo_entidade_logado->empresa->morada }}</td>
    </tr>
    <tr style="border: 0">
        <td style="border: 0"><strong>{{ $tipo_entidade_logado->empresa->cidade }} - {{ $tipo_entidade_logado->empresa->pais }}</strong></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <td style="text-transform: uppercase;padding: 3px" colspan="2" width="50"><strong>Nº da Encomenda: {{ $encomenda->factura ?? '--' }}</strong></td>
            <td style="text-transform: uppercase;padding: 3px" colspan="2" width="50"><strong>Dados da Entrega</strong></td>
        </tr>
    </thead>

    <tbody>

        <tr>
            <td style="width: 30;text-align: left" width="25">Fornecedor(a):</td>
            <td style="text-align: right" width="25">{{ $encomenda->fornecedor->nome ?? '--' }}</td>

            <td style="width: 30;text-align: left" width="25">Loja/Armazém:</td>
            <td style="text-align: right" width="25">{{ $encomenda->loja->nome ?? '--' }}</td>
        </tr>


        <tr>
            <td style="width: 30;text-align: left" width="25">Data da Encomenda:</td>
            <td style="text-align: right" width="25">{{ $encomenda->data_emissao ?? '--' }}</td>

            <td style="width: 30;text-align: left" width="25">Previsão de Entrega:</td>
            <td style="text-align: right" width="25">{{ $encomenda->previsao_entrega ?? '--' }}</td>
        </tr>

        <tr>
            <td style="width: 30;text-align: left" width="25">Utilizador(a):</td>
            <td style="text-align: right" width="25">{{ $encomenda->user ? $encomenda->user->name : 'Nenhum' }}</td>

            <td style="width: 30;text-align: left" width="25">Estado:</td>

            @if ($encomenda->status == 'pendente')
            <td style="text-align: right" width="25">{{ $encomenda->status ?? '--' }}</td>
            @endif

            @if ($encomenda->status == 'entregue')
            <td style="text-align: right" width="25">{{ $encomenda->status ?? '--' }}</td>
            @endif

            @if ($encomenda->status == 'cancelada')
            <td style="text-align: right" width="25">{{ $encomenda->status ?? '--' }}</td>
            @endif

        </tr>

    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th colspan="4" style="text-align: center;">Preço Custo</th>
            <th colspan="2" style="text-align: center;">Qtd</th>
            <th></th>
        </tr>
        <tr>
            <th>Ref.</th>
            <th>Produto</th>
            <th>IVA</th>
            <th>Desc.</th>
            <th>Encom.</th>
            <th>Atual</th>
            <th>Encome.</th>
            <th>Recebida</th>
            <th style="text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @if ($items)
        @foreach ($items as $item)
        <tr>
            <td>{{ $item->produto->codigo_barra ?? "" }}</td>
            <td>{{ $item->produto->nome ?? "" }}</td>
            <td style="text-align: right;">{{ $item->iva ?? 0 }} %</td>
            <td style="text-align: right;">{{ $item->desconto ?? 0 }} %</td>
            <td style="text-align: right;">
                @if ($item->custo != $item->produto->preco_custo)
                <span style="text-decoration: line-through;">{{ number_format($item->produto->preco_custo, 2, ',', '.') }} |</span>
                @endif
                <span>{{ number_format($item->preco_venda, 2, ',', '.') }}</span></td>
            <td style="text-align: right;">{{ number_format($item->produto->preco_custo, 2, ',', '.') }}</td>
            <td style="text-align: right;">{{ $item->quantidade ?? 0 }} Uni</td>
            <td style="text-align: right;">{{ $item->quantidade_recebida ?? 0 }} Uni</td>
            <td style="text-align: right;">{{ number_format($item->totalSiva, 2, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td class="text-right text-uppercase" colspan="8">SubTotal:</td>
            <td style="text-align: right;"><strong>{{ number_format($encomenda->total_sIva, 2, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td class="text-right text-uppercase" colspan="8">Descontos:</td>
            <td style="text-align: right;"><strong>{{ number_format($encomenda->desconto, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-right text-uppercase" colspan="8">Imposto:</td>
            <td style="text-align: right;"><strong>{{ number_format($encomenda->imposto, 2, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td class="text-right text-uppercase" colspan="8">Total:</td>
            <td style="text-align: right;"><strong>{{ number_format($encomenda->total, 2, ',', '.') }}</strong></td>
        </tr>
        @endif
    </tbody>
</table>

@endsection
