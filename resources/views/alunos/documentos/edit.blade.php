@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Documento</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('alunos-documentos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Documento</li>
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
          <div class="card">
            <form action="{{ route('alunos-documentos.update', $documento->id) }}" method="post" class="">
              @csrf
              @method('put')
              <div class="card-body row">
                
                <div class="col-12 col-md-12">
                  <label for="tipo_documento_id" class="form-label">Tipo Documento</label>
                  <div class="input-group mb-3">
                    <select type="text" class="form-control @error('tipo_documento_id') is-invalid @enderror" id="tipo_documento_id" name="tipo_documento_id">
                      <option value="cerfificado" {{ $documento->tipo_documento_id == "cerfificado" ? "selected" :  "" }}>CERTIFICADO</option>
                      <option value="declaracao" {{ $documento->tipo_documento_id == "declaracao" ? "selected" :  "" }}>DECLARAÇÃO</option>
                      <option value="transferencia" {{ $documento->tipo_documento_id == "transferencia" ? "selected" :  "" }}>TRANSFERÊNCIA</option>
                    </select>
                  </div>
                </div>
                
                <div class="col-12 col-md-12">
                  <label for="descricao" class="form-label">Descrição</label>
                  <div class="input-group mb-3">
                    <textarea name="descricao" class="form-control" id="descricao" cols="30" rows="5" placeholder="Informe uma descrição ou observação">{{ $documento->descricao ?? old('descricao') }}</textarea>
                  </div>
                </div>
         
              </div>
    
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
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