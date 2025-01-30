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
          <h1 class="m-0">Detalhe Prova</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('provas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Prova</li>
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
   
            <div class="card-body">
              <div class="row">
      
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      <tr>
                        <th>Designação</th>
                        <td class="text-right">{{ $prova->nome ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>Descrição</th>
                        <td class="text-right">{{ $prova->descricao ?? '-------------' }}</td>
                      </tr>
                     
                      <tr>
                        <th>Nota Maxima</th>
                        <td class="text-right">{{ $prova->nota_maxima ?? '-------------' }} V</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-6">
                  <table class="table text-nowrap">
                    <tbody>
                      <tr>
                        <th>Data</th>
                        <td class="text-right">{{ $prova->data_at ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>Formador</th>
                        <td class="text-right">{{ $prova->formador->nome ?? '-------------' }}</td>
                      </tr>
      
                      <tr>
                        <th>Turma</th>
                        <td class="text-right">{{ $prova->turma->nome ?? '-------------' }}</td>
                      </tr>
      
                    </tbody>
                  </table>
                </div>
                
                <div class="col-12 col-md-12">
                  <table class="table text-nowrap">
                    <tbody>
                      <tr>
                        <th>Nº </th>
                        <th>Questão</th>
                        <th>Opção A</th>
                        <th>Opção B</th>
                        <th>Opção C</th>
                        <th>Opção D</th>
                        <th>Opção E</th>
                        <th class="text-center">Nota</th>
                        <th class="text-center">Opção Correcta</th>
                      </tr>
                      @foreach ($prova->questoes as $item)
                        <tr>
                          <td class="text-left">#</td>
                          <td class="text-left">{{ $item->questao ?? '-------------' }}</td>
                          <td class="text-left">{{ $item->opcao_a ?? '-------------' }}</td>
                          <td class="text-left">{{ $item->opcao_b ?? '-------------' }}</td>
                          <td class="text-left">{{ $item->opcao_c ?? '-------------' }}</td>
                          <td class="text-left">{{ $item->opcao_d ?? '-------------' }}</td>
                          <td class="text-left">{{ $item->opcao_e ?? '-------------' }}</td>
                          <td class="text-center">{{ $item->nota ?? '-------------' }}</td>
                          <td class="text-center">{{ $item->opcao_certa ?? '-------------' }}</td>
                        </tr>
                      @endforeach
      
                    </tbody>
                  </table>
                </div>
      
              </div>
            </div>
            
            <div class="card-footer clearfix d-flex">
              <a href="{{ route('provas.edit', $prova->id) }}" class="btn btn-sm btn-success mx-1">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('provas.destroy', $prova->id ) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger mx-1"
                  onclick="return confirm('Tens Certeza que Desejas excluir esta prova?')">
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