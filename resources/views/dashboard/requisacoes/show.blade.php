@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Requisição - {{ $requisicao->numero }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default">Opções</button>
                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('requisacoes.edit', $requisicao->id) }}">Editar</a>
                            @if ($requisicao->status != 'aprovada')
                            <a class="dropdown-item text-success" href="{{ route('requisacoes.aprovada', $requisicao->id) }}">Marcar como Apravada</a>
                            @endif
                            @if ($requisicao->status != 'rascunho')
                            <a class="dropdown-item text-warning" href="{{ route('requisacoes.rascunho', $requisicao->id) }}">Marcar como Rascunho</a>
                            @endif
                            @if ($requisicao->status != 'rejeitada')
                            <a class="dropdown-item text-danger" href="{{ route('requisacoes.rejeitar', $requisicao->id) }}">Marcar como Rejeitado</a>
                            @endif
                            
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" target="_blink" href="{{ route('requisacoes-imprimir', $requisicao->id) }}">Imprimir PDF</a>
                            <div class="dropdown-divider"></div>

                            <form action="{{ route('requisacoes.destroy', $requisicao->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item" onclick="return confirm('Tens Certeza que Desejas excluir esta Requisição?')">
                                    Apagar Requisição
                                </button>
                            </form>
                        </div>
                    </div>
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="{{ route('requisacoes.index') }}">Voltar</a></li>
                      <li class="breadcrumb-item active">Requisição</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Nº da Requisição: {{ $requisicao->numero ?? '--' }}</h5>
                        </div>
                        <div class="card-body">
                            <h6>Operador(a): <span class="float-right">{{ $requisicao->user->name ?? '--' }}</span></h6>
                            <h6>Data da Requisição: <span class="float-right">{{ $requisicao->data_emissao ?? '--' }}</span></h6>
                            <h6>Aprovador(a): <span class="float-right">{{ $requisicao->aprovador ? $requisicao->aprovador->name : 'Nenhum' }}</span></h6>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dados da Entrega</h5>
                        </div>
                        <div class="card-body">
                            <h6>Loja/Armazém:<span class="float-right">{{ $requisicao->loja->nome ?? '--' }}</span></h6>
                            <h6>Previsão de Entrega:<span class="float-right">{{ $requisicao->previsao_entrega ?? '--' }}</span></h6>
                            @if ($requisicao->status == 'pendente')
                            <h6>Estado:<span class="float-right bg-warning p-1 text-uppercase">{{ $requisicao->status ?? '--' }}</span></h6>
                            @endif

                            @if ($requisicao->status == 'rejeitada')
                            <h6>Estado:<span class="float-right bg-danger p-1 text-uppercase">{{ $requisicao->status ?? '--' }}</span></h6>
                            @endif

                            @if ($requisicao->status == 'rascunho')
                            <h6>Estado:<span class="float-right bg-warning p-1 text-uppercase">{{ $requisicao->status ?? '--' }}</span></h6>
                            @endif

                            @if ($requisicao->status == 'aprovada')
                            <h6>Estado:<span class="float-right bg-success p-1 text-uppercase">{{ $requisicao->status ?? '--' }}</span></h6>
                            @endif

                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-12">
                  <div class="card">
                    <div class="card-body">
                      <table class="table table-hover text-nowrap">
                          <thead>
                              <tr>
                                  <th>Ref.</th>
                                  <th>Produto</th>
                                  <th>Categoria</th>
                                  <th>Marca</th>
                                  <th>Variação</th>
                                  <th class="text-right">Quantidade</th>
                                  <th class="text-right">IVA</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($items as $item)
                              <tr>
                                  <td>{{ $item->produto->referencia }}</td>
                                  <td>{{ $item->produto->nome }}</td>
                                  <td>{{ $item->produto->categoria->categoria ?? "" }}</td>
                                  <td>{{ $item->produto->marca->nome ?? "" }}</td>
                                  <td>{{ $item->produto->variacao->nome ?? "" }}</td>
                                  <td class="text-right">{{ $item->quantidade }}</td>
                                  <td class="text-right">{{ $item->produto->taxa_imposto->descricao ?? "" }}</td>
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
                  </div>
                </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
