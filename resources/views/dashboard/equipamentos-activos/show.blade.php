@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detalhe do Equipamento Activo</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('equipamentos-activos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Equipamento/Activo</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-12">
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
                <div class="col-12">
                    <div class="card">
                        @if ($equipamento_activo)
                        <!-- /.card-header -->
                        <div class="card-header">
                          <img src="/images/imobilizados/{{ $equipamento_activo->anexo }}" style="height: 150px;width: 150px">
                        </div>
                        <div class="card-body table-responsive">
                            <div class="row">

                                <div class="col-12 col-md-4 table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <th>Designação</th>
                                                <td class="text-right">{{ $equipamento_activo->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Nº Serie</th>
                                                <td class="text-right">{{ $equipamento_activo->numero_serie ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Codigo Barra</th>
                                                <td class="text-right">{{ $equipamento_activo->codigo_barra ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-4 table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>Estado</th>
                                                <td class="text-right">{{ $equipamento_activo->status ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Descrição</th>
                                                <td class="text-right">{{ $equipamento_activo->descricao ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Subconta</th>
                                                <td class="text-right">{{ $equipamento_activo->conta->numero ?? '-------------' }} - {{ $equipamento_activo->conta->nome ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-4 table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>

                                            <tr>
                                                <th>Classificação</th>
                                                <td class="text-right">{{ $equipamento_activo->classificacao->nome ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Referência</th>
                                                <td class="text-right">{{ $equipamento_activo->code ?? '-------------' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Número da Factura</th>
                                                <td class="text-right">{{ $equipamento_activo->numero_factura ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12 col-md-12 table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            {{-- -------------------------------------------- --}}
                                            <tr>
                                                <th colspan="4">Informações Financeiras</th>
                                            </tr>
                                            <tr>
                                                <th>Base de Incidência</th>
                                                <th>Iva %</th>
                                                <th>Iva Dedutível %</th>
                                                <th>Iva Não Dedutível %</th>
                                            </tr>
                                            <tr>
                                                <td>{{ number_format($equipamento_activo->base_incidencia ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->iva ?? 0, 1, ',', '.') }} - {{ number_format($equipamento_activo->iva_total ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->iva_d ?? 0, 1, ',', '.') }} - {{ number_format($equipamento_activo->iva_dedutivel ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->iva_nd ?? 0, 1, ',', '.') }} - {{ number_format($equipamento_activo->iva_n_dedutivel ?? 0, 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Custo Aquisição</th>
                                                <th>Valor Contabilistico</th>
                                                <th>Data Aquisição</th>
                                                <th>Data Utilizaçao</th>
                                            </tr>
                                            <tr>
                                                <td>{{ number_format($equipamento_activo->custo_aquisicao ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->valor_contabilistico ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ $equipamento_activo->data_aquisicao ?? '-------------' }}</td>
                                                <td>{{ $equipamento_activo->data_utilizacao ?? '-------------' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Data Registro</th>
                                                <th>Quantidade</th>
                                                <th>Valor Total</th>
                                                <th>Estado Financeiro</th>
                                            </tr>
                                            <tr>
                                                <td>{{ $equipamento_activo->data_att ?? '-------------' }}</td>
                                                <td>{{ number_format($equipamento_activo->quantidade ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ number_format($equipamento_activo->total ?? 0, 2, ',', '.') }}</td>
                                                <td>{{ $equipamento_activo->staus_financeiro ?? '-------------' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>


                        <div class="card-footer clearfix d-flex">
                            <a href="{{ route('equipamentos-activos.edit', $equipamento_activo->id) }}" class="btn btn-sm btn-success mx-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('equipamentos-activos.destroy', $equipamento_activo->id ) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm('Tens Certeza que Desejas excluir esta Equipamento ou Activo?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endif

                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
