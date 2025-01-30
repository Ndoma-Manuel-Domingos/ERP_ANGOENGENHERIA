@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Médicos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('medicos.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Médicos</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
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
            <div class="card">
                <form action="{{ route('medicos.update', $medico->id) }}" method="post" class="">
                    @csrf
                    @method('put')

                    <div class="card-body row">

                        <div class="col-12 col-md-3">
                            <label for="" class="form-label">Nome:</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"> </i></span>
                                </div>
                                <input type="text" class="form-control" name="nome" value="{{ $medico->nome }}" placeholder="Informe Nome">
                            </div>
                            <p class="text-danger">
                                @error('nome')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>
                        
                        
                        <div class="col-12 col-md-3">
                          <label for="" class="form-label">Nome Pai (Opcional):</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                              </div>
                              <input type="text" class="form-control" name="nome_do_pai" value="{{ $medico->nome_do_pai ?? old('nome_do_pai') }}" placeholder="Informe Nome do Pai">
                          </div>
                          <p class="text-danger">
                              @error('nome_do_pai')
                              {{ $message }}
                              @enderror
                          </p>
                      </div>

                      <div class="col-12 col-md-3">
                          <label for="" class="form-label">Nome Mãe (Opcional):</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                              </div>
                              <input type="text" class="form-control" name="nome_da_mae" value="{{ $medico->nome_da_mae ?? old('nome_da_mae') }}" placeholder="Informe Nome mãe">
                          </div>
                          <p class="text-danger">
                              @error('nome_da_mae')
                              {{ $message }}
                              @enderror
                          </p>
                      </div>

                      <div class="col-12 col-md-3">
                          <label for="" class="form-label">Data Nascimento:</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                              </div>
                              <input type="date" class="form-control" name="data_nascimento" value="{{ $medico->data_nascimento ?? old('data_nascimento') }}" placeholder="Data Nascimento">
                          </div>
                          <p class="text-danger">
                              @error('data_nascimento')
                              {{ $message }}
                              @enderror
                          </p>
                      </div>

                      <div class="col-12 col-md-3">
                          <label for="" class="form-label">Gênero:</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                              </div>
                              <select type="text" class="form-control select2" name="genero">
                                  <option value="">Selecionar</option>
                                  <option value="Masculino" {{ $medico->genero == "Masculino" ? 'selected' : '' }}>Masculino</option>
                                  <option value="Femenino" {{ $medico->genero == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                  <option value="Personalizado" {{ $medico->genero == "Personalizado" ? 'selected' : '' }}>Personalizado</option>
                              </select>
                          </div>
                          <p class="text-danger">
                              @error('genero')
                              {{ $message }}
                              @enderror
                          </p>
                      </div>

                      <div class="col-12 col-md-3">
                          <label for="" class="form-label">Estado Cívil:</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                              </div>
                              <select type="text" class="form-control select2" name="estado_civil_id">
                                  <option value="">Selecionar</option>
                                  @foreach ($estados_civils as $item)
                                  <option value="{{ $item->id }}" {{ $medico->estado_civil_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                  @endforeach
                              </select>
                          </div>
                          <p class="text-danger">
                              @error('estado_civil_id')
                              {{ $message }}
                              @enderror
                          </p>
                      </div>

                      <div class="col-12 col-md-3">
                        <label for="" class="form-label">NIF/BI:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" name="nif" value="{{ $medico->nif ?? old('nif') }}" placeholder="Informe NIF">
                        </div>
                        <p class="text-danger">
                            @error('nif')
                            {{ $message }}
                            @enderror
                        </p>
                      </div>

                      <div class="col-12 col-md-3">
                        <label for="" class="form-label">Seguradora:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <select type="text" class="form-control select2" name="seguradora_id">
                                <option value="">Selecionar</option>
                                @foreach ($seguradores as $item)
                                <option value="{{ $item->id }}" {{ $medico->seguradora_id  == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-danger">
                            @error('seguradora_id')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Província:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <select type="text" class="form-control select2" name="provincia_id">
                                <option value="">Selecionar</option>
                                @foreach ($provincias as $item)
                                <option value="{{ $item->id }}" {{ $medico->provincia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-danger">
                            @error('provincia_id')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Município:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <select type="text" class="form-control select2" name="municipio_id">
                                <option value="">Selecionar</option>
                                @foreach ($municipios as $item)
                                <option value="{{ $item->id }}" {{ $medico->municipio_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-danger">
                            @error('municipio_id')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Distritos:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <select type="text" class="form-control select2" name="distrito_id">
                                <option value="">Selecionar</option>
                                @foreach ($distritos as $item)
                                <option value="{{ $item->id }}" {{ $medico->distrito_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-danger">
                            @error('distrito_id')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">País:</label>
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

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Código Postal:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" name="codigo_postal" value="{{ $medico->codigo_postal ?? old('codigo_postal') }}" placeholder="Informe codigo Postal">
                        </div>
                        <p class="text-danger">
                            @error('codigo_postal')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Localidade:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" name="localidade" value="{{ $medico->localidade ?? old('localidade') }}" placeholder="Informe  Localidade">
                        </div>
                        <p class="text-danger">
                            @error('localidade')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Telefone:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" name="telefone" value="{{ $medico->telefone ?? old('telefone') }}" placeholder="Informe Telefone">
                        </div>
                        <p class="text-danger">
                            @error('telefone')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Telemóvel:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" name="telemovel" value="{{ $medico->telemovel ?? old('telemovel') }}" placeholder="Informe  Telemóvel">
                        </div>
                        <p class="text-danger">
                            @error('telemovel')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">E-mail:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" name="email" value="{{ $medico->email ?? old('email') }}" placeholder="Informe  E-email">
                        </div>
                        <p class="text-danger">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Website:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" name="website" value="{{ $medico->website ?? old('website') }}" placeholder="Informe WebSite">
                        </div>
                        <p class="text-danger">
                            @error('website')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>


                    <div class="col-12 col-md-3">
                        <label for="" class="form-label">Observação:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control" name="observacao" value="{{ $medico->observacao ?? old('observacao') }}" placeholder="Informe Observação">
                        </div>
                        <p class="text-danger">
                            @error('observacao')
                            {{ $message }}
                            @enderror
                        </p>
                    </div>

                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-danger">Cancelar</button>
                    </div>
                </form>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
