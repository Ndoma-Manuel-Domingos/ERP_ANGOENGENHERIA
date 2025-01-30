@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Criar Marcações de Ausências</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('marcacoes-ausencias.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Marcações de Ausências</li>
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
                <div class="col-12 col-md-12">
                  <form action="{{ route('marcacoes-ausencias.update', $ausencia->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-body">
                          <div class="row">
                          
                            <div class="col-12 col-md-6">
                              <label for="funcionario_id" class="form-label">Funcionários</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select type="text" class="form-control @error('funcionario_id') is-invalid @enderror" id="funcionario_id" name="funcionario_id">
                                  <option value="">Selecione</option>
                                  @foreach ($funcionarios as $item)
                                  <option value="{{ $item->id }}" {{  $ausencia->funcionario_id == $item->id ? 'selected' : '' }}>{{ $item->numero_mecanografico }} - {{ $item->nome }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            
                            <div class="col-12 col-md-3">
                              <label for="data_inicio" class="form-label">Data de Inicio</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="date" class="form-control @error('data_inicio') is-invalid @enderror" id="data_inicio" value="{{ $ausencia->data_inicio }}" name="data_inicio"  placeholder="Informe a data inicio">
                              </div>
                            </div>
                            
                            <div class="col-12 col-md-3">
                              <label for="data_final" class="form-label">Data Final</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="date" class="form-control @error('data_final') is-invalid @enderror" id="data_final" value="{{ $ausencia->data_final }}" name="data_final"  placeholder="Informe a data final">
                              </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                              <label for="data_referenciada" class="form-label">Data Referênciada</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="date" class="form-control @error('data_referenciada') is-invalid @enderror" id="data_referenciada" value="{{ $ausencia->data_referenciada }}" name="data_referenciada"  placeholder="Informe a data Referências">
                              </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                              <label for="ausencia_id" class="form-label">Motivos</label>
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select type="text" class="form-control @error('ausencia_id') is-invalid @enderror" id="ausencia_id" name="ausencia_id">
                                  <option value="">Selecione</option>
                                  @foreach ($motivos as $item)
                                  <option value="{{ $item->id }}" {{  $ausencia->ausencia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                                                        
                          </div>
                        </div>
                        
                        <div class="card-footer">
                            {{-- @if (Auth::user()->can('criar subsidio')) --}}
                            <button type="submit" class="btn btn-primary">Salvar Ausências</button>
                            {{-- @endif --}}
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
