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
          <h1 class="m-0">Detalhe Formador</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('formadores.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Formador</li>
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
              <a href="{{ route('turma-adicionar-formador', $formador->id) }}" class="btn btn-primary btn-sm">Adicionar a Turma</a>
            </div>
            <div class="card-body">
              <div class="row">
      
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      <tr>
                        <th>Nome</th>
                        <td class="text-right">{{ $formador->nome ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>Genero</th>
                        <td class="text-right">{{ $formador->genero ?? '-------------' }}</td>
                      </tr>
                     
                      <tr>
                        <th>Estado Cívil</th>
                        <td class="text-right">{{ $formador->estado_civil ?? '-------------' }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      <tr>
                        <th>Bilhete</th>
                        <td class="text-right">{{ $formador->nif ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>País</th>
                        <td class="text-right">{{ $formador->pais ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>Vencimento</th>
                        <td class="text-right">{{ $formador->vencimento ?? '-------------' }}</td>
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
                        <td>{{ $formador->telefone ?? '-------------' }}</td>
                        <td class="text-right">{{ $formador->telemovel ?? '-------------' }}</td>
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
                        <td>{{ $formador->email ?? '-------------' }}</td>
                        <td class="text-right">{{ $formador->website ?? '-------------' }}</td>
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
                        <td colspan="2">{{ $formador->morada ?? '-------------' }} <br>{{ $formador->codigo_postal ?? '-------------' }}</td>
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
                        <td colspan="2">{{ $formador->observacao ?? '-------------' }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
      
              </div>
            </div>
            
            <div class="card-footer clearfix d-flex">
              <a href="{{ route('formadores.edit', $formador->id) }}" class="btn btn-sm btn-success mx-1">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('formadores.destroy', $formador->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger mx-1"
                  onclick="return confirm('Tens Certeza que Desejas excluir esta formador?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
        
      </div>
  </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection