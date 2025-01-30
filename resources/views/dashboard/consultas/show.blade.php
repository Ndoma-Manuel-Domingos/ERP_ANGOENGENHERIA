@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detalhe da consulta</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('consultas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Consultas</li>
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
            <div class="card-body">
              <div class="row">
              
                <div class="col-12 col-md-4">
                  <table class="table text-nowrap">
                    <tbody>
        
                      <tr>
                        <th>Consulta Nº</th>
                        <td class="text-right">{{ $consulta->id }}</td>
                      </tr>
        
                      <tr>
                        <th>Tipo Consulta</th>
                        <td class="text-right">{{ $consulta->produto->nome ?? '-------------' }}</td>
                      </tr>
                      
                      <tr>
                        <th>Data/Hora</th>
                        <td class="text-right">{{ $consulta->data_consulta }} as {{ $consulta->hora_consulta }}</td>
                      </tr>
        
                    </tbody>
                  </table>
                </div>
              
                <div class="col-12 col-md-4">
                  <table class="table text-nowrap">
                    <tbody>
        
                      <tr>
                        <th>Nome Paciente</th>
                        <td class="text-right">{{ $consulta->paciente->nome }}</td>
                      </tr>
        
                      <tr>
                        <th>Estado Cívil</th>
                        <td class="text-right">{{ $consulta->paciente->genero ?? '-------------' }}</td>
                      </tr>
                      
                      <tr>
                        <th>Idade</th>
                        <td class="text-right">{{  $consulta->paciente->idade($consulta->paciente->data_nascimento) }} Anos</td>
                      </tr>
        
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-4">
                  <table class="table text-nowrap">
                    <tbody>
                    
                      <tr>
                        <th>Nome do Médico</th>
                        <td class="text-right">{{ $consulta->medico->nome ?? '-------------' }}</td>
                      </tr>
                      
                      <tr>
                        <th>Nome</th>
                        <td class="text-right">{{ $consulta->medico->genero ?? '-------------' }}</td>
                      </tr>
                    
                      <tr>
                        <th>Gênero</th>
                        <td class="text-right">{{ $consulta->medico->idade($consulta->medico->data_nascimento) }} Anos</td>
                      </tr>
        
                    </tbody>
                  </table>
                </div>
                
              </div>
            </div>
            
            <div class="card-footer d-flex">
              <a class="btn btn-sm btn-success mr-2" href="{{ route('consultas.edit', $consulta->id) }}"><i class="fas fa-edit"></i> Editar</a>
              <form action="{{ route('consultas.destroy', $consulta->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tens Certeza que Desejas excluir esta Curso?')">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
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