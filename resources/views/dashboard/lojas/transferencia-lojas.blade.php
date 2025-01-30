@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transferência Produtos para Loja/Armazém</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Gestão</li>
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
            </div>
        

            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('transferencia-lojas-armazem-store') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                
                                    <div class="col-12 col-md-3">
                                        <label for="loja_origem_id" class="form-label">Loja Origem</label>
                                        
                                        <select id="" class="form-control select2" name="loja_origem_id" >
                                            <option value="">Selecione</option>
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id }}" {{ old('loja_origem_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <p class="text-danger">
                                            @error('loja_origem_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                
                                    <div class="col-12 col-md-3">
                                        <label for="loja_destino_id" class="form-label">Loja Destino</label>
                                        
                                        <select id="" class="form-control select2" name="loja_destino_id" >
                                            <option value="">Selecione</option>
                                            @foreach ($lojas as $item)
                                            <option value="{{ $item->id }}" {{ old('loja_destino_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <p class="text-danger">
                                            @error('loja_destino_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                
                                    <div class="col-12 col-md-5">
                                        <label for="produto_id" class="form-label">Produtos</label>
                                        
                                        <select id="produto_id" class="form-control select2" name="produto_id" >
                                            <option value="">Selecione</option>
                                            @foreach ($produtos as $item)
                                            <option value="{{ $item->id }}" {{ old('produto_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <p class="text-danger">
                                            @error('produto_id')
                                            {{ $message }}
                                            @enderror
                                        </p>
                                    </div>
                                    
                                    <div class="col-12 col-md-1">
                                        <label for="produto" class="col-form-label text-right"></label> <br>
                                        <a href="" class="btn btn-primary mt-2" id="salvarItem">Confirmar</a>
                                    </div>
                                    
                                    @if ($items)
                                    <div class="col-12 col-md-12 mt-5">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5px"></th>
                                                    <th>Nome do Produto</th>
                                                    <th>Quantidade</th>
                                                    <th>Quantidade no Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $item)
                                                <tr>
                                                    <td class="bg-light">
                                                        <a href="{{ route('transferencia-lojas-armazem-remover-item', $item->id) }}" id="remover_id" class="text-danger bg-danger p-1 img-circle"><i class="fas fa-close text-white"></i></a>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control produto_id" value="{{ $item->produto->nome ?? '' }}" name="produto_id{{ $item->id }}" id="{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control quantidade quantidade{{ $item->id }}" value="{{ $item->quantidade ?? 0 }}" name="quantidade{{ $item->id }}" id="{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        {{ $item->produto->total_produto($item->produto->id) }}
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
                                <button type="submit" class="btn-sm btn-primary">Transferir</button>
                            </div>
                        </div>
                    </form>
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

        $("#salvarItem").on('click', function(e) {
            e.preventDefault();
            
            // Supondo que o valor do produto está em um campo com id 'produto'
            const produtoId = $("#produto_id").val();
              
            if(produtoId != ""){
                // Gerar a URL usando o Laravel Blade
                const url = `{{ route('transferencia-lojas-armazem-item', ':produto_id') }}`.replace(':produto_id', produtoId);
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
