@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Aprovar Requisição</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('requisacoes.show', $requisicao->id) }}">Voltar</a></li>
            <li class="breadcrumb-item active">Requisição</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>Produtos a remeter</h5>
                <p>Seleccione os produtos que pretende remeter e indique a respetiva quantidade. Poderá efetuar tantas receções quantas necessárias.</p>
            </div>
            <form action="{{ route('requisacoes.aprovada.store') }}" method="post" class="">
            @csrf
            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">
            <div class="card-body row">
                @if ($items)
                <div class="col-12">
                <table class="table table-head-fixed text-nowrap">
                    <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="text-center">Stock Atual</th>
                        <th style="width: 15%">Quantidade</th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($items as $item)
                        <tr>
                          <td>{{ $item->produto->nome ?? '' }}</td>
                          <td class="text-center">{{ $item->produto->total_produto($item->produto->id)  }}</td>
                          <td><input type="text" class="form-control" name="quantidade{{ $item->id }}" value="{{ $item->quantidade }}"></td>
                          <input type="hidden" name="ids[]" value="{{ $item->id }}">
                        </tr>
                      @endforeach
                    </tbody>
                </table>
                </div>
                @endif
                <div class="col-12 text-center">
                    <p> Ao remeter os produtos irá atualizar o stock na <strong>{{ $requisicao->loja->nome }}</strong>.</p>
                    {{-- <p> O Preço de Custo será calculado com base no preço de custo médio atual e segundo as unidades disponíveis em stock (preço de custo médio).</p> --}}
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Salvar e Remeter Produtos</button>
            </div>
            </form>
        </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
