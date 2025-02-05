@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Fecho do TPA</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pronto-venda') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Painel de venda</li>
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
                <div class="col-12 col-md-6 col-lg-6">
                    <a type="button" href="{{ route('pronto-venda') }}" class="btn btn-light btn-block btn-flat p-3"><i class="fas fa-arrow-left"></i> Voltar</a>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('contas-bancarias.fechamento_create') }}" method="post" class="row">
                                @csrf
                                <div class="col-12 col-md-12 text-center">
                                    <label for="">Montante Disponível ao Fechar o TPA</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Kz</span>
                                        </div>
                                        @php
                                           $saldo = ((($movimento->valor_total??0) + ($movimento->valor_abertura??0) + ($movimento->valor_entrada??0)) - ($movimento->valor_saida??0));
                                        @endphp
                                        <input type="text" placeholder="Informe o montante disponível ao fechar o caixa" class="form-control form-control-lg @error('valor') is-invalid @enderror" value="{{ old('valor') ?? $saldo }}" name="valor">
                                    </div>
                                </div>

                                <div class="input-group mt-3">
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
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6"> 
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
                                    <td colspan="5">Abertura</td>
                                    <td colspan="5" class="text-right">{{ number_format($movimento->valor_abertura ??0, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></td>
                                  </tr>

                                  <tr>
                                    <td colspan="5">Entrada</td>
                                    <td colspan="5" class="text-right">{{ number_format($movimento->valor_entrada ??0, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></td>
                                  </tr>

                                  <tr>
                                    <td colspan="5">Saída</td>
                                    <td colspan="5" class="text-right">{{ number_format($movimento->valor_valor_fecho ??0, 2, ',', '.') }} <small>{{ $empresa->moeda }}</small></td>
                                  </tr>

                                  {{-- -------------------------------------------------------- --}}
                                  
                                  <tr>
                                    <th colspan="5">Saldo Final</th>
                                    <th colspan="5" class="text-right">{{ number_format((($movimento->valor_total ??0) + ($movimento->valor_abertura??0) + ($movimento->valor_entrada??0)) - ($movimento->valor_saida??0), 2, ',', '.') }} <small>{{ $empresa->moeda??"" }}</small></th>
                                  </tr>

                                </tbody>
                            </table>
                        </div>
                        
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection


