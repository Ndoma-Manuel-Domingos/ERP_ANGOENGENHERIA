@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Registro de {{ $requests['tipo'] ?? "" }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('operacaoes-financeiras.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Financeiras</li>
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

                <div class="col-12 col-md-12">
                    <div class="card">
                        <form action="{{ route('operacaoes-financeiras.store') }}" method="post" class="">
                            @csrf
                            <div class="card-body row">

                                <div class="col-12 col-md-4">
                                    <label for="referencia" class="form-label">Designação</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('referencia') is-invalid @enderror" name="referencia" id="referencia" value="{{ old('referencia') }}" placeholder="Informe a referença. Ex FR AGT235/2352">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="motante" class="form-label">Motante Caixa</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('motante') is-invalid @enderror" name="motante" id="montante" value="{{ old('motante') }}" placeholder="Informe o Motante">
                                    </div>
                                </div>
                                
                                
                                <div class="col-12 col-md-4">
                                    <label for="motante_banco" class="form-label" @disabled(false)>Motante Conta Bancária</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="number" class="form-control  @error('motante_banco') is-invalid @enderror" name="motante_banco" id="montante" value="{{ old('motante_banco') }}" placeholder="Informe o Motante da Conta Bancária">
                                    </div>
                                </div>
                                
                                
                                <input type="hidden" name="tipo_servico_id" id="tipo_servico_id" value="{{ $requests['tipo'] ?? "" }}">
                                
                                @if ($requests['tipo'] == "receita")
                                     <div class="col-12 col-md-4">
                                        <label for="data_recebimento" class="form-label">Data de Recebimento</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control  @error('data_recebimento') is-invalid @enderror" name="data_recebimento" id="data_recebimento" value="{{ old('data_recebimento') }}" placeholder="Informe a Data">
                                        </div>
                                    </div>    
                                @endif
                                
                                @if ($requests['tipo'] == "dispesa")
                                    <div class="col-12 col-md-4">
                                        <label for="data_pagamento" class="form-label">Data de Pagamento</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="date" class="form-control  @error('data_pagamento') is-invalid @enderror" name="data_pagamento" id="data_pagamento" value="{{ old('data_pagamento') }}" placeholder="Informe a Data">
                                        </div>
                                    </div>    
                                @endif
                                
                                <div class="col-12 col-md-4">
                                    <label for="status_pagamento" class="form-label">Estado Pagamento</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control select2 @error('status_pagamento') is-invalid @enderror" id="status_pagamento" name="status_pagamento">
                                            <option value="pendente">Pendente</option>
                                            <option value="pago">Pago</option>
                                            <option value="atrasado">Atrasado</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-2">
                                    <label for="parcelado" class="form-label">Parcelado</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control select2 @error('parcelado') is-invalid @enderror" id="parcelado" name="parcelado">
                                            <option value="N">NÃO</option>
                                            <option value="Y">SIM</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-2">
                                    <label for="parcelas" class="form-label">Número da Parcela</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('parcelas') is-invalid @enderror" name="parcelas" id="parcelas" value="{{ old('parcelas') }}" placeholder="Informe o número da parcela">
                                    </div>
                                </div>
                                
                                
                                @if ($requests['tipo'] == "receita")
                                    <div class="col-12 col-md-4">
                                        <label for="forma_recebimento_id" class="form-label">Formas de Recebimento</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('forma_recebimento_id') is-invalid @enderror" id="forma_recebimento_id" name="forma_recebimento_id">
                                                @foreach ($formas_pagamentos as $item)
                                                <option value="{{ $item->id }}">{{ $item->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                
                                @if ($requests['tipo'] == "dispesa")
                                    <div class="col-12 col-md-4">
                                        <label for="forma_pagamento_id" class="form-label">Formas de Pagamentos</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('forma_pagamento_id') is-invalid @enderror" id="forma_pagamento_id" name="forma_pagamento_id">
                                                @foreach ($formas_pagamentos as $item)
                                                <option value="{{ $item->id }}">{{ $item->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="col-12 col-md-2">
                                    <label for="caixa_id" class="form-label">Caixas</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control select2 @error('caixa_id') is-invalid @enderror" id="caixa_id" name="caixa_id">
                                            <option value="">Selecione o Caixa de Destino/Origem</option>
                                            @foreach ($caixas as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-2">
                                    <label for="banco_id" class="form-label">Contas Bancárias</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control select2 @error('banco_id') is-invalid @enderror" id="banco_id" name="banco_id">
                                            <option value="">Selecione a Conta Bancária de Destino/Origem</option>
                                            @foreach ($bancos as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                

                                <div class="col-12 col-md-4">
                                    @if ($requests['tipo'] == "receita")
                                    <label for="tipo_id" class="form-label">Receitas</label>
                                    @endif
                                
                                    @if ($requests['tipo'] == "dispesa")
                                    <label for="tipo_id" class="form-label">Dispesas</label>
                                    @endif
                                
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <select type="text" class="form-control select2 @error('tipo_id') is-invalid @enderror" id="tipo_id" name="tipo_id">
                                            @foreach ($tipos as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if ($requests['tipo'] == "receita")
                                    <div class="col-12 col-md-4">
                                        <label for="cliente_id" class="form-label">Clientes</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id">
                                                @foreach ($clientes as $item)
                                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                
                                @if ($requests['tipo'] == "dispesa")
                                    <div class="col-12 col-md-4">
                                        <label for="fornecedor_id" class="form-label">Fornecedores</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control select2 @error('fornecedor_id') is-invalid @enderror" id="fornecedor_id" name="fornecedor_id">
                                                @foreach ($fornecedores as $item)
                                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif


                                <div class="col-12 col-md-4">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="text" class="form-control  @error('descricao') is-invalid @enderror" name="descricao" id="descricao" value="{{ old('descricao') }}" placeholder="Descrição">
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                @if (Auth::user()->can('criar dispesa'))
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                @endif
                                <button type="reset" class="btn btn-danger">Cancelar</button>
                            </div>
                        </form>
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

@section('scripts')
<script>
    $(function() {
        $("#carregar_tabela").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
