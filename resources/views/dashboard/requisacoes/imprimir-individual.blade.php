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
        <tr class="card-header">
            <td style="text-transform: uppercase" colspan="2" width="50"><strong>Nº da Requisição: {{ $requisicao->numero ?? '--' }}</strong></td>
            <td style="text-transform: uppercase" colspan="2" width="50"><strong>Dados da Entrega</strong></td>
        </tr>
    </thead>
    
    <tbody>
        <tr>
            <td style="width: 30;text-align: left" width="25">Operador(a):</td>
            <td style="text-align: right" width="25">{{ $requisicao->user->name ?? '--' }}</td>
            
            <td style="width: 30;text-align: left" width="25">Loja/Armazém:</td>
            <td style="text-align: right" width="25">{{ $requisicao->loja->nome ?? '--' }}</td>
        </tr>
        
       <tr>
            <td style="width: 30;text-align: left" width="25">Data da Requisição:</td>
            <td style="text-align: right" width="25">{{ $requisicao->data_emissao ?? '--' }}</td>
            
            <td style="width: 30;text-align: left" width="25">Previsão de Entrega:</td>
            <td style="text-align: right" width="25">{{ $requisicao->previsao_entrega ?? '--' }}</td>
        </tr>
        
       <tr>
            <td style="width: 30;text-align: left" width="25">Aprovador(a):</td>
            <td style="text-align: right" width="25">{{ $requisicao->aprovador ? $requisicao->aprovador->name : 'Nenhum' }}</td>
            
            <td style="width: 30;text-align: left" width="25">Estado:</td>            
             @if ($requisicao->status == 'pendente')
            <td style="text-align: right" width="25">{{ $requisicao->status ?? '--' }}</td>
            @endif

            @if ($requisicao->status == 'rejeitada')
            <td style="text-align: right" width="25">{{ $requisicao->status ?? '--' }}</td>
            @endif

            @if ($requisicao->status == 'rascunho')
            <td style="text-align: right" width="25">{{ $requisicao->status ?? '--' }}</td>
            @endif

            @if ($requisicao->status == 'aprovada')
            <td style="text-align: right" width="25">{{ $requisicao->status ?? '--' }}</td>
            @endif
            
        </tr>
    
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>Ref.</th>
            <th>Produto</th>
            <th>Categoria</th>
            <th>Marca</th>
            <th style="text-align: right">P. Venda</th>
            <th style="text-align: right">Quantidade</th>
            <th style="text-align: right">IVA</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            <td>{{ $item->produto->codigo_barra }}</td>
            <td>{{ $item->produto->nome }}</td>
            <td>{{ $item->produto->categoria->categoria ?? "" }}</td>
            <td>{{ $item->produto->marca->nome ?? "" }}</td>
            <td style="text-align: right">{{ number_format($item->produto->preco_venda, 2, ',', '.')  }}</td>
            <td style="text-align: right">{{ $item->quantidade }}</td>
            <td style="text-align: right">{{ $item->produto->taxa_imposto->valor ?? "" }} %</td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection
