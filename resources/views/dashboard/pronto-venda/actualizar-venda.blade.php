@extends('layouts.vendas')

@section('section')

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
                <!-- /.col-md-6 -->
                <div class="col-lg-3">
                    <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Valtar</a>   
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('actualizar-venda-update', $movimento->id) }}" class="row" method="post">
                                @csrf
                                @method('put')
                                <div class="col-12 col-md-12">
                                    <label for="">Quantidade</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">Qtd</span>
                                      </div>
                                      <input type="text" class="form-control" name="quantidade" value="{{ $movimento->quantidade }}">
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
                                        <option value="ISE" {{ $movimento->iva == "ISE" ? 'selected' : '' }} >0%</option>
                                        <option value="RED" {{ $movimento->iva == "RED" ? 'selected' : '' }} >2%</option>
                                        <option value="INT" {{ $movimento->iva == "INT" ? 'selected' : '' }} >5%</option>
                                        <option value="OUT" {{ $movimento->iva == "OUT" ? 'selected' : '' }} >7%</option>
                                        <option value="NOR" {{ $movimento->iva == "NOR" ? 'selected' : '' }} >14%</option>
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
                <div class="col-lg-7"> </div>
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

