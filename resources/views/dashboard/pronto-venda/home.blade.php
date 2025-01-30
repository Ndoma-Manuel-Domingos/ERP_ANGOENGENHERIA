@extends('layouts.vendas')

@section('section')

@php
$checkCaixa = App\Models\Caixa::where([
['active', true],
['status', '=', 'aberto'],
['user_id', '=', Auth::user()->id],
])->first();
@endphp

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header"> </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-7">

                    <div class="row mb-4">
                        <div class="col-12">
                            <form action="">
                                <div class="input-group">
                                    <input type="search" name="produto" id="produto" class="form-control form-control-lg produto" placeholder="Pesquisar qualquer produto aqui...">
                                    <div class="input-group-append">
                                        <button type="submit" id="pesquisar_produto" class="btn btn-lg btn-default pesquisar_produto">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">

                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Geral</a>
                                        </li>
                                        @if ($categorias)
                                        @foreach ($categorias as $categoria)
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-profile-tab{{ $categoria->id }}" data-toggle="pill" href="#custom-tabs-four-profile{{ $categoria->id }}" role="tab" aria-controls="custom-tabs-four-profile{{ $categoria->id }}" aria-selected="false">{{ $categoria->categoria }}
                                            </a>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">


                                            <div class="row" id="carregar_produtos">
                                                @foreach ($produtos as $item)
                                                <div class="col-md-2">
                                                    <a href="{{ route('adicionar-produto', $item->id) }}">
                                                        <div class="card card-success shadow-sm bg-light">
                                                            <!-- /.card-header -->
                                                            <div class="card-body text-center py-3 row">
                                                                <div class="col-12 col-md-12 col-sm-12">
                                                                    <img src='{{ asset("images/produtos/{$item->imagem}") }}' alt="" class="attachment-img img img-lg">
                                                                </div>
                                                                <div class="col-12 col-md-12 col-sm-12">
                                                                    <h6 class="pt-3">{{ $item->nome }}</h6>
                                                                    <p><strong>{{ number_format($item->preco_venda, 2, ',', '.') }} <small>{{ $loja->moeda }}</small></strong></p>
                                                                </div>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                    </a>
                                                    <!-- /.card -->
                                                </div>
                                                @endforeach
                                            </div>
                                            <!-- /.row -->
                                        </div>

                                        @if ($categorias)
                                        @foreach ($categorias as $categoria)
                                        <div class="tab-pane fade" id="custom-tabs-four-profile{{ $categoria->id }}" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab{{ $categoria->id }}">
                                            <div class="row">
                                                @foreach ($categoria->produtos as $produto)
                                                @if ($produto->categoria_id == $categoria->id)
                                                <div class="col-md-2">
                                                    <a href="{{ route('adicionar-produto', $produto->id) }}">
                                                        <div class="card card-success shadow-sm bg-light">
                                                            <!-- /.card-header -->
                                                            <div class="card-body text-center py-3 row">
                                                                <div class="col-12">
                                                                    <img src='{{ asset("images/produtos/{$produto->imagem}") }}' alt="" class="attachment-img img img-lg">
                                                                </div>
                                                                <div class="col-12">
                                                                    <h6 class="pt-3">{{ $produto->nome }}</h6>
                                                                    <p><strong>{{ number_format($produto->preco_venda, 2, ',', '.')  }} <small>{{ $loja->moeda }}</small></strong></p>
                                                                </div>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                    </a>
                                                    <!-- /.card -->
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.col-md-6 -->
                <div class="col-lg-3">
                    @if (empty($checkCaixa))
                    <div class="card text-center">
                        <div class="card-header">
                            <img src="{{ asset('dist/img/user.png') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle" style="width: 120px;height: 120px">
                            <h4 class="pt-2">Caixa Fechado</h4>
                            <p class="text-secondary">Para efectuar operações de caixa, nomeadamente, faturar, deverá
                                Abrir a Caixa</p>
                        </div>
                        <div class="card-body text-center">
                            <h6 class="text-center"><strong>Montante</strong></h6>
                            <p class="text-secondary">Introduza o montante disponível em caixa no momento da abertura
                                (pode ser zero).</p>

                            <form action="{{ route('caixa.abertura_caixa_create') }}" method="post" class="px-5">
                                @csrf
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="valor" value="0" placeholder="Informe o Montante Valor">
                                </div>
                                @error('valor')
                                <span>{{ $message }}</span><br>
                                @enderror

                                <button type="submit" class="btn btn-md d-iniline-block btn-primary"><i class="fas fa-box"></i> Abrir Caixa</button>
                            </form>

                        </div>
                    </div>
                    @else
                    <div class="card">
                        <div class="card-body table-responsive" style="height: 500px;">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Qtd</th>
                                        <th class="text-right">Preço</th>
                                        <th style="width: 5px"></th>
                                    </tr>
                                </thead>
                                @if ($movimentos)
                                <tbody>
                                    @foreach ($movimentos as $movimento)
                                    <tr>
                                        <td><a href="{{ route('actualizar-venda', [$movimento->id, "null"]) }}">{{ $movimento->produto->nome }}</a></td>
                                        <td><a href="{{ route('actualizar-venda', [$movimento->id, "null"]) }}">{{ $movimento->quantidade }}</a></td>
                                        <td class="text-right"><a href="{{ route('actualizar-venda', [$movimento->id, "null"]) }}">{{ number_format($movimento->valor_pagar, 2, ',', '.') }} <small>{{ $loja->moeda }}</small></a></td>
                                        <td>
                                            <a href="{{ route('remover-produto', $movimento->id) }}"><i class="fas fa-close text-danger"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @endif
                            </table>
                        </div>
                        <!-- /.card-body -->

                        <a href="#" class="btn btn-default btn-block btn-flat p-3" id="print"><i class="fas fa-print"></i> Imprimir Pedido</a>
                        <div class="bg-dark p-2 px-4">
                            <p class="float-left d-inline-block">TOTAL</p>
                            <div class="float-right d-inline-block">
                                <p><span class="h2">
                                        @if ($total_pagar)
                                        {{ number_format($total_pagar, 2, ',', '.') }} {{ $loja->moeda }}
                                        @else
                                        {{ number_format("0", 2, ',', '.') }} {{ $loja->moeda }}
                                        @endif
                                    </span>
                                    <br>
                                    <small>
                                        @if ($total_produtos)
                                        {{ $total_produtos }}
                                        @else
                                        0
                                        @endif
                                        Produtos /
                                        @if ($total_unidades)
                                        {{ $total_unidades }}
                                        @else
                                        0
                                        @endif
                                        Uni
                                    </small>
                                </p>
                            </div>
                        </div>
                        <div class="bg-info">
                            {{-- <div class="row"> --}}
                            <a href="" class="btn btn-info btn-flat col-md-5 p-4 text-left">
                                <span class="h3 text-white"><i class="fas fa-angle-down"></i> Opções</span>
                            </a>
                            <a href="{{ route('finalizar-venda') }}" class="btn btn-info btn-flat col-12 col-md-7 p-4 text-center float-right">
                                <span class="h3 text-white text-uppercase"><i class="fas fa-check"></i> Finalizar</span>
                            </a>
                            {{-- </div> --}}
                        </div>
                    </div>
                    @endif
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-1"></div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $("#produto").on("input", function(e) {
            e.preventDefault()
            var produto = $("#produto").val()

            $.ajax({
                method: "GET"
                , url: "buscar-produto"
                , data: {
                    produto: produto
                }
                , beforeSend: function() {
                    // $(".ajax_load").fadeIn(200).css("display", "flex");
                }
                , success: function(response) {
                    console.log(response)
                    $("#carregar_produtos").html("")
                    for (let index = 0; index < response.produtos.length; index++) {
                        $('#carregar_produtos').append('<div class="col-md-2">\
                            <a href="adicionar-produto/' + response.produtos[index].id + '">\
                                <div class="card card-success shadow-sm bg-light">\
                                    <div class="card-body text-center py-3 row">\
                                        <div class="col-12 col-md-12 col-sm-12">\
                                            <img src="/assets/images/produtos/' + response.produtos[index].imagem + '" alt="" class="attachment-img img img-lg">\
                                        </div>\
                                        <div class="col-12 col-md-12 col-sm-12">\
                                            <h6 class="pt-3">' + response.produtos[index].nome + '</h6>\
                                            <p><strong>' + response.produtos[index].preco_venda + '<small>' + response.loja.moeda + '</small></strong></p>\
                                        </div>\
                                    </div>\
                                </div>\
                            </a>\
                        </div>');
                    }

                }
            })

        })


        $("#print").click(function() {
            print('index.html');
        });
    });

</script>
@endsection
