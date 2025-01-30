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
          <h1 class="m-0">Detalhe Médico</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('medicos.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Médico</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">

    <div class="card">
      <div class="card-body">
        <div class="row">

          <div class="col-12 col-md-4">
            <table class="table text-nowrap">
              <tbody>
                <tr>
                  <th>Nome</th>
                  <td class="text-right">{{ $medico->nome ?? '-------------' }}</td>
                </tr>
              
                <tr>
                  <th>Gênero</th>
                  <td class="text-right">{{ $medico->genero ?? '-------------' }}</td>
                </tr>

                <tr>
                  <th>Data Nascimento</th>
                  <td class="text-right">{{ $medico->data_nascimento ?? '-------------' }}</td>
                </tr>
                
              </tbody>
            </table>
          </div>
          
          <div class="col-12 col-md-4">
            <table class="table text-nowrap">
              <tbody>

                <tr>
                  <th>País</th>
                  <td class="text-right">{{ $medico->pais ?? '-------------' }}</td>
                </tr>

                <tr>
                  <th>Estado Cívil</th>
                  <td class="text-right">{{ $medico->estado_civil->nome ?? '-------------' }}</td>
                </tr>
                
                <tr>
                  <th>NIF/Bilhete</th>
                  <td class="text-right">{{ $medico->nif ?? '-------------' }}</td>
                </tr>

              </tbody>
            </table>
          </div>
          
          <div class="col-12 col-md-4">
            <table class="table text-nowrap">
              <tbody>

                <tr>
                  <th>Nome do Pai</th>
                  <td class="text-right">{{ $medico->nome_do_pai ?? '-------------' }}</td>
                </tr>

                <tr>
                  <th>Nome da Mãe</th>
                  <td class="text-right">{{ $medico->nome_da_mae ?? '-------------' }}</td>
                </tr>
                
                <tr>
                  <th>Seguradora</th>
                  <td class="text-right">{{ $medico->seguradora->nome ?? '-------------' }}</td>
                </tr>

              </tbody>
            </table>
          </div>

          <div class="col-12 col-md-12">
            <table class="table text-nowrap">
              <tbody>
                <tr>
                  <th>Morada</th>
                  <th>Províncias</th>
                  <th>Município</th>
                  <th>Distrito</th>
                </tr>
                <tr>
                  <td>{{ $medico->morada ?? '-------------' }} <br>{{ $medico->codigo_postal ?? '-------------' }}</td>
                  <td>{{ $medico->provincia->nome ?? '-------------' }}</td>
                  <td>{{ $medico->municipio->nome ?? '-------------' }}</td>
                  <td>{{ $medico->distrito->nome ?? '-------------' }}</td>
                </tr>
                {{-- -------------------------------------------- --}}
                <tr>
                  <th colspan="4">Contactos</th>
                </tr>
                <tr>
                  <td colspan="2">Telefone</td>
                  <td colspan="2">Telemóvel</td>
                </tr>
                <tr>
                  <td colspan="2">{{ $medico->telefone ?? '-------------' }}</td>
                  <td colspan="2">{{ $medico->telemovel ?? '-------------' }}</td>
                </tr>
                {{-- -------------------------------------------- --}}
                <tr>
                  <th colspan="4">Contactos</th>
                </tr>
                <tr>
                  <td colspan="2">E-mail</td>
                  <td colspan="2">Website</td>
                </tr>
                <tr>
                  <td colspan="2">{{ $medico->email ?? '-------------' }}</td>
                  <td colspan="2">{{ $medico->website ?? '-------------' }}</td>
                </tr>

                <tr>
                  <th colspan="4">Observação</th>
                </tr>

                <tr>
                  <td colspan="4">{{ $medico->observacao ?? '-------------' }}</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection