@extends('layouts.app')

@section('content')

@php
    $meuSaldo = 5000;
@endphp

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe Aluno</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('alunos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Alunos</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      
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
        
      
        <div class="col-12 col-md-12">    
          <div class="card">
            <div class="card-header">
              <a href="{{ route('alunos.create', ['aluno_id' => $aluno->id] ) }}" class="btn btn-primary btn-sm">Nova Matrícula</a>
              <a href="{{ route('turma-adicionar-aluno', $aluno->id) }}" class="btn btn-primary btn-sm">Adicionar a Turma</a>
            </div>
            <div class="card-body">
              <div class="row">
      
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      <tr>
                        <th>Nome</th>
                        <td class="text-right">{{ $aluno->nome ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>Genero</th>
                        <td class="text-right">{{ $aluno->genero ?? '-------------' }}</td>
                      </tr>
                     
                      <tr>
                        <th>Estado Cívil</th>
                        <td class="text-right">{{ $aluno->estado_civil ?? '-------------' }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      <tr>
                        <th>Bilhete</th>
                        <td class="text-right">{{ $aluno->nif ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>País</th>
                        <td class="text-right">{{ $aluno->pais ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>Vencimento</th>
                        <td class="text-right">{{ $aluno->vencimento ?? '-------------' }}</td>
                      </tr>
      
                    </tbody>
                  </table>
                </div>
      
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                   
                      {{-- -------------------------------------------- --}}
                      <tr>
                        <th colspan="2">Contactos</th>
                      </tr>
                      <tr>
                        <td>Telefone</td>
                        <td class="text-right">Telemóvel</td>
                      </tr>
                      <tr>
                        <td>{{ $aluno->telefone ?? '-------------' }}</td>
                        <td class="text-right">{{ $aluno->telemovel ?? '-------------' }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                
                      {{-- -------------------------------------------- --}}
                      <tr>
                        <th colspan="2">Contactos</th>
                      </tr>
                      <tr>
                        <td>E-mail</td>
                        <td class="text-right">Website</td>
                      </tr>
                      <tr>
                        <td>{{ $aluno->email ?? '-------------' }}</td>
                        <td class="text-right">{{ $aluno->website ?? '-------------' }}</td>
                      </tr>
      
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      
                      <tr>
                        <th colspan="2">Morada</th>
                      </tr>
                      <tr>
                        <td colspan="2">{{ $aluno->morada ?? '-------------' }} <br>{{ $aluno->codigo_postal ?? '-------------' }}</td>
                      </tr>
                    
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                    
                      <tr>
                        <th colspan="2">Observação</th>
                      </tr>
      
                      <tr>
                        <td colspan="2">{{ $aluno->observacao ?? '-------------' }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
      
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-md-12">
          @foreach ($matriculas as $item)
          <div class="card">
            <div class="card-header">
              <h6>
                <strong>Matrícula: {{ $item->numero }}</strong> 
                @if ($item->status == 'DESACTIVO')
                <a href="{{ route('alunos-matriculas-status', $item->id) }}" class="btn btn-sm btn-success float-right">Activar Matrícula</a>    
                @endif
                @if ($item->status == 'ACTIVO')
                <a href="{{ route('alunos-matriculas-status', $item->id) }}" class="btn btn-sm btn-danger float-right">Desactivar Matrícula</a>    
                @endif
              </h6>
            </div>
            <div class="card-body">
              <div class="row">
      
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      <tr>
                        <th>Curso</th>
                        <td class="text-right">{{ $item->curso->nome ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>Sala</th>
                        <td class="text-right">{{ $item->sala->nome ?? '-------------' }}</td>
                      </tr>
                      
                      <tr>
                        <th>Operador</th>
                        <td class="text-right">{{ $item->user->name ?? "" ?? '-------------' }}</td>
                      </tr>
                
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      
                      <tr>
                        <th>Estado</th>
                        <td class="text-right">{{ $item->status ?? '-------------' }}</td>
                      </tr>
                    
                      <tr>
                        <th>Turno</th>
                        <td class="text-right">{{ $item->turno->nome ?? '-------------' }}</td>
                      </tr>
                      
                      <tr>
                        <th>Ano Lectivo</th>
                        <td class="text-right">{{ $item->ano_lectivo->nome ?? '-------------' }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
              </div>
            </div>
            <div class="card-footer"></div>
          </div>
          @endforeach
        </div>
        
      </div>
  </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection