@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Nova Requisição</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
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
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('requisacoes.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="numero" class="form-label text-right">Nº Requisição:</label>
                                        <input type="text" class="form-control" id="numero" name="numero" value="{{ $totalRequisicao }}" placeholder="Número da Requisição:">
                                        <p class="text-danger col-sm-3">
                                            @error('numero')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>



                                    <div class="col-12 col-md-3">
                                        <label for="loja_id" class="form-label text-right">Loja/Armazém:</label>
                                        <select class="form-control select2" id="loja_id" name="loja_id">
                                            @foreach ($lojas as $loja)
                                            <option value="{{ $loja->id ?? old('fornecedor_selecionado') }}">{{ $loja->nome }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger col-sm-3">
                                            @error('loja_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="observacao" class="form-label text-right">Observações:</label>
                                        <input type="text" class="form-control" id="observacao" name="observacao" value="{{ old('observacao') }}" placeholder="Observações ">
                                        <p class="text-danger col-sm-3">
                                            @error('observacao')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="produto" class="form-label text-right">Pesquisar Produto:</label>
                                        <select class="form-control select2" id="produto" name="produto">
                                            <option value="">Selecione o produto</option>
                                            @if ($produtos)
                                            @foreach ($produtos as $item2)
                                            <option value="{{ $item2->id }}">{{ $item2->nome }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-1">
                                        <label for="" class="form-label">.</label><br>
                                        <a href="" class="btn btn-primary" id="salvarItem">Confirmar</a>
                                    </div>

                                    @if ($items)
                                    <div class="col-12 col-md-12 mt-5">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5px"></th>
                                                    <th>Produto</th>
                                                    <th>Qtd</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $item)
                                                <tr>
                                                    <td class="bg-light">
                                                        <a href="{{ route('requisacoes.remover-produto', $item->id) }}" id="remover_id" class="text-danger bg-danger p-1 img-circle"><i class="fas fa-close text-white"></i></a>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control produto_id" value="{{ $item->produto->nome ?? '' }}" name="produto_id{{ $item->id }}" id="{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control quantidade quantidade{{ $item->id }}" value="{{ $item->quantidade ?? 0 }}" data-custo="{{ $item->custo ?? 0 }}" data-total="{{ $item->total }}" name="quantidade{{ $item->id }}" id="{{ $item->id }}">
                                                    </td>
                                                  
                                                    <input type="hidden" name="ids[]" value="{{ $item->id }}">
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                <button type="reset" class="btn btn-danger">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


@section('scripts')
<script>
    $(function() {
      $("#salvarItem").on('click', function(e) {
        e.preventDefault();
        
        // Supondo que o valor do produto está em um campo com id 'produto'
        const produtoId = $("#produto").val();
          
        if(produtoId != ""){
            // Gerar a URL usando o Laravel Blade
            const url = `{{ route('requisacoes.adicionar-produto', ':produto') }}`.replace(':produto', produtoId);
            // Redirecionar
            window.location.href = url;
        }
          
      })
      //Date picker
      $('#reservationdate').datetimepicker({
          format: 'L'
      });
    });

</script>
@endsection
