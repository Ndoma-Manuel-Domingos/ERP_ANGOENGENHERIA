@extends('layouts.formadores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Lancamento de Notas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('formadores-turma-visualizar-pautas', $pauta->turma->id) }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Pautas</li>
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
                        <form action="{{ route('turma-lancamento-pautas-store') }}" method="post">
                            @csrf
                            <div class="card-body row">
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">P1</label>
                                  
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                      </div>
                                      <input type="text" name="prova_1" value="{{ $pauta->prova_1 ?? old('prova_1') }}" class="form-control" placeholder="Informe a primeira nota">
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">P2</label>
                                  
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                      </div>
                                      <input type="text" name="prova_2" value="{{ $pauta->prova_2 ?? old('prova_2') }}" class="form-control" placeholder="Informe a segunda nota">
                                    </div>
                                </div>
                                
                                
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">P3</label>
                                  
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                      </div>
                                      <input type="text" name="prova_3" value="{{ $pauta->prova_3 ?? old('prova_3') }}" class="form-control" placeholder="Informe a terceira nota">
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Exame</label>
                                  
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-file"></i></span>
                                      </div>
                                      <input type="text" value="{{ $pauta->exame ?? old('exame') }}" name="exame" class="form-control" placeholder="Informe a nota do exame">
                                    </div>
                                </div>
                                
                                <input type="hidden" value="{{ $pauta->id }}" name="pauta_id">
                                
                                <div class="col-12 col-md-3 pt-3">
                                    <label for="" class="form-label">Média: {{ $pauta->media }}</label> <br>
                                    
                                    @if ($pauta->resultado == "Nao Definido")
                                    <label class="form-label">Resultado Final: <span class="text-uppercase text-warning">{{ $pauta->resultado }}</span> </label>    
                                    @endif
                                    
                                    @if ($pauta->resultado == "Aprovado")
                                    <label class="form-label">Resultado Final: <span class="text-uppercase text-success">{{ $pauta->resultado }}</span> </label>    
                                    @endif
                                    
                                    @if ($pauta->resultado == "Reprovado")
                                    <label class="form-label">Resultado Final: <span class="text-uppercase text-danger">{{ $pauta->resultado }}</span> </label>    
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary ">Salvar</button>
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
