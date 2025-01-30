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
                    <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Voltar</a>   
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('caixa.fechamento_caixa_create') }}" method="post" class="row">
                                @csrf
                                <div class="col-12 col-md-12 text-center">
                                    <label for="">Montante Disponível ao Fechar Caixa</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">Kz</span>
                                      </div>
                                      <input type="text" class="form-control form-control-lg" name="valor">
                                    </div>

                                    @error('valor')
                                        <span class="text-danger">{{ $message }}</span><br>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12 text-center">
                                    <div class="input-group mb-3">
                                      <select name="print" id="" class="form-control form-control-lg">
                                        <option value="1">Imprimir</option>
                                        <option value="2">Download CSV</option>
                                      </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 text-center">
                                    <div class="input-group mb-3">
                                      <select name="lista" id="" class="form-control form-control-lg">
                                        <option value="1">Resumo dos Movimentos</option>
                                        <option value="2">Lista dos Movimentos</option>
                                      </select>
                                    </div>
                                </div>

                                <div class="input-group my-3 px-5">
                                    <span class="input-group-append text-center">
                                        <button type="submit" class="btn btn-info btn-flat mx-2"><i class="fas fa-check"></i> Confirmar</button>
                                        <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-info btn-flat mx-2"><i class="fas fa-close"></i> Cancelar</a>
                                    </span>
                                </div>
                                <!-- /input-group -->

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7"> 
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                  <tr>
                                    <th colspan="10" class="text-center">Resumo dos Movimentos</th>
                                  </tr>
                                  <tr>
                                    <th colspan="5">Tipo</th>
                                    <th colspan="5" class="text-right">Montante</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td colspan="5">abertura</td>
                                    <td colspan="5" class="text-right">23</td>
                                  </tr>

                                  <tr>
                                    <td colspan="5">Entrada</td>
                                    <td colspan="5" class="text-right">23</td>
                                  </tr>


                                  <tr>
                                    <td colspan="5">Saída</td>
                                    <td colspan="5" class="text-right">23</td>
                                  </tr>

                                  {{-- -------------------------------------------------------- --}}

                                  <tr>
                                    <th colspan="10" class="text-center">Faturação a Prazo</th>
                                  </tr>
                                  <tr>
                                    <th colspan="5">Nº Documento</th>
                                    <th colspan="5" class="text-right">Valor</th>
                                  </tr>

                                  <tr>
                                    <td colspan="5">0</td>
                                    <td colspan="5" class="text-right">0,00</td>
                                  </tr>


                                  {{-- -------------------------------------------------------- --}}

                                  <tr>
                                    <th colspan="10" class="text-center">Resumo por Entradas e Saídas de Caixa</th>
                                  </tr>
                                  <tr>
                                    <th colspan="5">Tipo de Movimento</th>
                                    <th colspan="5" class="text-right">Valor</th>
                                  </tr>

                                  <tr>
                                    <td colspan="5">Entradas de Dinheiro na Caixa</td>
                                    <td colspan="5" class="text-right">0,00</td>
                                  </tr>

                                  <tr>
                                    <td colspan="5">Saídas de Dinheiro na Caixa</td>
                                    <td colspan="5" class="text-right">0,00</td>
                                  </tr>


                                  {{-- -------------------------------------------------------- --}}

                                  <tr>
                                    <th colspan="10" class="text-center">Mapa de Impostos</th>
                                  </tr>
                                  <tr>
                                    <th colspan="2">Taxa</th>
                                    <th colspan="2" class="text-center">Nº Docs</th>
                                    <th colspan="2" class="text-center">Base</th>
                                    <th colspan="2" class="text-center">IVA</th>
                                    <th colspan="2" class="text-center">Total</th>
                                  </tr>

                                  <tr>
                                    <td colspan="2" class="text-center">0</td>
                                    <td colspan="2" class="text-center">0</td>
                                    <td colspan="2" class="text-center">0</td>
                                    <td colspan="2" class="text-center">0</td>
                                    <td colspan="2" class="text-center">0</td>
                                  </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
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

