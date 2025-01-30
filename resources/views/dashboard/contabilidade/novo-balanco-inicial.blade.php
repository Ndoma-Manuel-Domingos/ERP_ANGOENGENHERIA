@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Novo Balanço Inicial</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Voltar</a></li>
                            <li class="breadcrumb-item active">Balanço Inicial</li>
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
                        <form action="{{ route('contabilidade-balanco-inicial-novo-store') }}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-body row">
                                    
                                    <div class="col-12 col-md-3">
                                        <label for="exercicio_id" class="form-label">Exercícios</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select class="form-control select2 @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                                                <option value="">Exercícios</option>
                                                <option value="{{ $exercicio->id }}" selected>{{ $exercicio->nome }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                        <label for="periodo_id" class="form-label">Períodos</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select class="form-control select2 @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id">
                                                @foreach ($periodos as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-3">
                                        <label for="subconta_id" class="form-label">Contas</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select class="form-control select2 @error('subconta_id') is-invalid @enderror" id="subconta_id" name="subconta_id">
                                                <option value="">Escolher a conta</option>
                                                @foreach ($subcontas as $item)
                                                    <option value="{{ $item->id }}">{{ $item->numero }} - {{ $item->nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-12 col-md-3">
                                        <label for="saldo" class="form-label">Saldo</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="number" class="form-control @error('saldo') is-invalid @enderror"
                                                name="saldo" id="saldo" value="{{ old('saldo') }}"
                                                placeholder="Informe o saldo">
                                        </div>
                                    </div>
    
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">CONFIRMAR O LANÇAMENTO</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-body table-responsive">
                                <table class="table table-hover text-nowrap" id="carregar_tabela" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Contas</th>
                                            <th class="text-danger text-right">Crédito</th>
                                            <th class="text-info text-right">Debito</th>
                                            <th class="text-info text-right">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($movimentos as $item)
                                            <tr>
                                                <td>#</td>
                                                <td>{{ $item->subconta->numero }} - {{ $item->subconta->nome }}</td>
                                                <td class="text-danger text-right">{{ number_format($item->credito, 2, ',', '.') }}</td>
                                                <td class="text-info text-right">{{ number_format($item->debito, 2, ',', '.') }}</td>
                                                @if ($item->credito > $item->debito)
                                                    @if (($item->credito - $item->debito) == 0)
                                                    <td class="text-danger text-right">-</td>
                                                    @else    
                                                    <td class="text-info text-right">{{ number_format($item->credito - $item->debito, 2, ',', '.') }}</td>
                                                    @endif
                                                @else
                                                    @if ($item->debito > $item->credito)
                                                        @if (($item->debito - $item->credito) == 0)
                                                        <td class="text-danger text-right">-</td>
                                                        @else
                                                        <td class="text-info text-right">{{ number_format($item->debito - $item->credito, 2, ',', '.') }}</td>
                                                        @endif
                                                    @else
                                                    <td class="text-danger text-right">{{ number_format(0, 2, ',', '.') }}</td>
                                                    @endif
                                                @endif 
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
