@extends('layouts.app')

@php
$checkCaixa = App\Models\Caixa::where([
['active', true],
['status', '=', 'aberto'],
['user_id', '=', Auth::user()->id],
])->first();
@endphp

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Marcas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
        <li class="breadcrumb-item active">Marcas</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.container-fluid --> --}}
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
                @endif

                @if (Session::has('danger'))
                <div class="alert alert-danger">
                    {{ Session::get('danger') }}
                </div>
                @endif

                @if (Session::has('warning'))
                <div class="alert alert-warning">
                    {{ Session::get('warning') }}
                </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-12 col-md-4 col-12">
                @if ($mesa)
                <a type="button" href="{{ route('pronto-venda-mesas-pedidos', Crypt::encrypt($mesa->id)) }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Voltar</a>
                @else    
                <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Voltar</a>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('actualizar-venda-update', [$movimento->id, $mesa ? $mesa->id : ""]) }}" class="row" method="post">
                            @csrf
                            @method('put')
                            <div class="col-12 col-md-12">
                                <label for="">Quantidade</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Qtd</span>
                                    </div>
                                    <input type="text" class="form-control" name="quantidade" value="{{ $movimento->quantidade }}">
                                    <input type="hidden" class="form-control" name="quantidade_anterior" value="{{ $movimento->quantidade }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="">Preço Unitário</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">kz</span>
                                    </div>
                                    <input type="text" class="form-control" name="preco_unitario" value="{{ $movimento->preco_unitario }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="">IVA</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">kz</span>
                                    </div>
                                    <select type="text" class="form-control" name="iva">
                                        <option value=''>Automático</option>
                                        <option value="ISE" {{ $movimento->iva == "ISE" ? 'selected' : '' }}>0%</option>
                                        <option value="RED" {{ $movimento->iva == "RED" ? 'selected' : '' }}>2%</option>
                                        <option value="INT" {{ $movimento->iva == "INT" ? 'selected' : '' }}>5%</option>
                                        <option value="OUT" {{ $movimento->iva == "OUT" ? 'selected' : '' }}>7%</option>
                                        <option value="NOR" {{ $movimento->iva == "NOR" ? 'selected' : '' }}>14%</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="">Desconto Aplicado</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="text" class="form-control" name="desconto_aplicado" value="{{ $movimento->desconto_aplicado }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="">.</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Kz</span>
                                    </div>
                                    <input type="text" class="form-control" name="desconto_aplicado_valor" value="{{ $movimento->desconto_aplicado_valor }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <label for="">Texto Opcional</label>
                                <div class="input-group mb-3">
                                    <textarea name="texto_opcional" placeholder="Se for necessário detalhar, utilize este campo." class="form-control" id="" rows="2">{{ $movimento->texto_opcional }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <label for="">Número(s) de Série</label>
                                <div class="input-group mb-3">
                                    <textarea name="numero_serie" placeholder="Se for mais do que um, utilize a virgula como separador." class="form-control" id="" rows="2">{{ $movimento->numero_serie }}</textarea>
                                </div>
                            </div>

                            <div class="input-group my-3 px-5">
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-info btn-flat">Confirmar</button>
                                </span>
                                <input type="text" class="form-control rounded-0" disabled value="{{ number_format($movimento->valor_pagar, 2, ',', '.')  }} {{ $dados->moeda }}">
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12 col-md-8 col-12">
                
                @if ($mesa)
                <a type="button" href="{{ route('pronto-venda-mesas-pedidos', Crypt::encrypt($mesa->id)) }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Actualizar Grupo de Preços</a>
                @else    
                <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Actualizar Grupo de Preços</a>
                @endif
            
                <div class="card">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-right">Preço</th>
                                <th class="text-right">Preço S/IVA</th>
                                <th class="text-right">Preço Fornecedor</th>
                                <th class="text-right">IVA</th>
                                <th class="text-right">Margem de Lucro</th>
                                <th class="text-right">Estado</th>
                                <th class="text-right">Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($grupo_precos)
                            @foreach ($grupo_precos as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td class="text-right">{{ number_format($item->preco_venda??0, 2, ',', '.') }} <span class="text-secondary">{{ $tipo_entidade_logado->empresa->moeda ?? "" }}</span></td>
                                <td class="text-right">{{ number_format($item->preco??0, 2, ',', '.') }} <span class="text-secondary">{{ $tipo_entidade_logado->empresa->moeda ?? "" }}</span></td>
                                <td class="text-right">{{ number_format($item->preco_custo??0, 2, ',', '.')  }} <span class="text-secondary">{{ $tipo_entidade_logado->empresa->moeda ?? "" }}</span></td>
                                <td class="text-right">{{ $item->produto->taxa_imposto->valor??0 }} %</td>
                                <td class="text-right">{{ number_format($item->margem??0, 2, ',', '.')  }} <span class="text-secondary">%</span></td>
                                <td class="text-right">{{ $item->status ?? "" }}</td>

                                <td style="width: 50px;">
                                    @if ($item->status == "desactivo")
                                    <a href="{{ route('definir_preco_venda.produtos', [$item->id, $movimento->id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-database"></i> Activar</a>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
