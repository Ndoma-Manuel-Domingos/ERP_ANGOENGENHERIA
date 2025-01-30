@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Aluno</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('alunos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Aluno</li>
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
            <form action="{{ route('alunos.update', $aluno->id) }}" method="post" class="">
              @csrf
              @method('put')
    
              <div class="card-body row">
    
    
                <div class="col-12 col-md-6">
                  <label for="" class="form-label">Nome Completo</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="nome" value="{{ $aluno->nome ?? old('nome') }}"
                      placeholder="Informe Produto">
                  </div>
                  <p class="text-danger">
                    @error('nome')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Bilhete</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="nif" value="{{ $aluno->nif ?? old('nif') }}"
                      placeholder="Informe Bilhete">
                  </div>
                  <p class="text-danger">
                    @error('nif')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
                
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Genero</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control select2" name="genero">
                      <option value="">Selecionar</option>
                      <option value="Masculino" {{ $aluno->genero == "Masculino" ? 'selected': "" }}>Masculino</option>
                      <option value="Femenino" {{ $aluno->genero == "Femenino" ? 'selected': "" }}>Femenino</option>
                    </select>
                  </div>
                  <p class="text-danger">
                    @error('genero')
                    {{ $message }}
                    @enderror
                  </p>     
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Estado Cívil</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control select2" name="estado_civil">
                      <option value="">Selecionar</option>
                      <option value="CASADO(A)" {{ $aluno->estado_civil == "CASADO(A)" ? 'selected': "" }}>CASADO(A)</option>
                      <option value="SOLTEIRO(A)" {{ $aluno->estado_civil == "SOLTEIRO(A)" ? 'selected': "" }}>SOLTEIRO(A)</option>
                      <option value="DIVORCIADO" {{ $aluno->estado_civil == "DIVORCIADO" ? 'selected': "" }}>DIVORCIADO</option>
                    </select>
                  </div>
                  <p class="text-danger">
                    @error('estado_civil')
                    {{ $message }}
                    @enderror
                  </p>     
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Data Nascimento</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="date" class="form-control" name="data_nascimento" value="{{ $aluno->data_nascimento ?? old('data_nascimento') }}"
                      placeholder="Informe data_nascimento">
                  </div>
                  <p class="text-danger">
                    @error('data_nascimento')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Nome do Pai</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="pai" value="{{ $aluno->pai ?? old('pai') }}"
                      placeholder="Informe Nome do Pai">
                  </div>
                  <p class="text-danger">
                    @error('pai')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Nome Mãe</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="mae" value="{{ $aluno->mae ?? old('mae') }}"
                      placeholder="Informe nome Mãe">
                  </div>
                  <p class="text-danger">
                    @error('mae')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
                
    
                <div class="col-12 col-md-6">
                  <label for="" class="form-label">País</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control" name="pais">
                        @include('includes.paises')
                    </select>
                  </div>
                  <p class="text-danger">
                    @error('pais')
                    {{ $message }}
                    @enderror
                  </p>     
                </div>
    
                <div class="col-12 col-md-6">
                  <label for="id_user" class="form-label">Perfil</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control" id="id_user" name="id_user">
                      @foreach ($roles as $item)
                      <option value="{{ $item->id }}" @if(in_array($item->id, $roles_user)) selected @endif>{{ $item->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <p class="text-danger">
                    @error('id_user')
                    {{ $message }}
                    @enderror
                  </p>     
                </div>
    
                <div class="col-12 col-md-6">
                  <label for="" class="form-label">Codigo Postal</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="codigo_postal" value="{{ $aluno->codigo_postal ?? old('codigo_postal') }}"
                      placeholder="Informe codigo Postal">
                  </div>
                  <p class="text-danger">
                    @error('codigo_postal')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Localidade</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="localidade" value="{{ $aluno->localidade ?? old('localidade') }}"
                      placeholder="Informe  Localidade">
                  </div>
                  <p class="text-danger">
                    @error('localidade')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Telefone</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="telefone" value="{{ $aluno->telefone ?? old('telefone') }}"
                      placeholder="Informe Telefone">
                  </div>
                  <p class="text-danger">
                    @error('telefone')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Telemovel</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="telemovel" value="{{ $aluno->telemovel ?? old('telemovel') }}"
                      placeholder="Informe  Telemóvel">
                  </div>
                  <p class="text-danger">
                    @error('telemovel')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">E-mail</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" class="form-control" name="email" value="{{ $aluno->email ?? old('email') }}"
                      placeholder="Informe  E-email">
                  </div>
                  <p class="text-danger">
                    @error('email')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Website</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="website" value="{{ $aluno->website ?? old('website') }}"
                      placeholder="Informe WebSite">
                  </div>
                  <p class="text-danger">
                    @error('website')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Observação</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="observacao" value="{{ $aluno->observacao ?? old('observacao') }}"
                      placeholder="Informe Observação">
                  </div>
                  <p class="text-danger">
                    @error('observacao')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
              </div>
              
              {{-- <div class="card-body row">
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Curso</label>
                
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control select2" name="curso_id">
                      @foreach ($cursos as $item)
                      <option value="{{ $item->id }}" {{ $item->id == $matricula->curso_id ? 'selected' : "" }}>{{ $item->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Turno</label>
                
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control select2" name="turno_id">
                      @foreach ($turnos as $item)
                      <option value="{{ $item->id }}" {{ $item->id == $matricula->turno_id ? 'selected' : "" }}>{{ $item->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Sala</label>
                
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control select2" name="sala_id">
                      @foreach ($salas as $item)
                      <option value="{{ $item->id }}" {{ $item->id == $matricula->sala_id ? 'selected' : "" }}>{{ $item->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Ano Lectivo</label>
                
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <select type="text" class="form-control select2" name="ano_lectivo_id">
                      @foreach ($anos_lectivos as $item)
                      <option value="{{ $item->id }}" {{ $item->id == $matricula->ano_lectivo_id ? 'selected' : "" }}>{{ $item->nome }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                
                <div class="col-12 col-md-3">
                  <label for="" class="form-label">Preço</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="text" class="form-control" name="valor_pagamento" value="{{ $matricula->valor_pagamento ?? old('valor_pagamento') }}" placeholder="Informe o valor do pagamento">
                  </div>
                  <p class="text-danger">
                    @error('valor_pagamento')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
              </div>
    
              <input type="hidden" class="form-control" name="matricula_id" value="{{ $matricula->id ?? old('matricula_id') }}"> --}}
    
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
              </div>
            </form>
          </div>
          <!-- /.row -->
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection