@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><span class="text-uppercase">{{ $caixa->nome }}</span> - Movimentos de Caixa
          </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('caixas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Caixa</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">


      <!-- /.row -->
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

        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 col-sm-8">
                  <div class="row">
                    <div class="col-sm-12 col-md-4">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Data </span>
                        </div>
                        <input type="date" class="form-control" name="nome" value="{{ old('nome') }}"
                          placeholder="Informe o nome do caixa">
                        <div class="input-group-prepend">
                          <span class="input-group-text"> Até </span>
                        </div>
                        <input type="date" class="form-control" name="nome" value="{{ old('nome') }}"
                          placeholder="Informe o nome do caixa">
                        <button type="submit" class="btn btn-primary ml-2"> <i class="fas fa-search"></i>
                          Filtar</button>
                      </div>
                    </div>

                    <div class="col-sm-12 col-md-8">
                      <a href="" class="float-right btn btn-primary">Exportar</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="col-12">
          @if ($caixa)
          <!-- /.card-header -->
          @if ($movimentos)
          @foreach ($movimentos as $item)
          <div class="card">
            <div class="card-body table-responsive mb-4">
              <table class="table text-nowrap">
                <tbody>
                  <tr>
                    <td rowspan="2" class="text-center">
                      <span>Quinta-feira </span><br>
                      <small>6 de outubro 2022</small> <br>
                      <span>0,00 Kz</span>
                    </td>
                    <td class="text-right">Abertura</td>
                    <td class="text-left"><strong>{{ $item->hora_abertura ?? "" }}</strong></td>
                    <td class="text-right">Valor</td>
                    <td class="text-left"><strong>{{ number_format($item->valor_abertura??0, 2, ',', '.')  }} {{ $dados->empresa->moeda }}</strong></td>
                    <td class="text-right">Utilizador</td>
                    @if (!empty($item->user_id))
                      <td class="text-left"><strong>
                        {{ $item->user->name ?? "" }}
                      </strong>
                    </td>
                    @else
                      <td class="text-left"><strong>N/A</strong></td>
                    @endif
                    <td rowspan="2" class="text-center">
                      <br>
                      <a href="{{ route('caixa.caixas-detalhe', $item->id) }}" class="btn btn-sm btn-outline-primary float-right mr-2">Detalhe</a>
                      <a href="" class="btn btn-sm btn-outline-primary float-right mr-2">Exportar</a>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-right">Fecho</td>
                    <td class="text-left"><strong>{{ $item->hora_fecho }}</strong></td>
                    <td class="text-right">Valor</td>
                    <td class="text-left"><strong>{{ number_format($item->valor_valor_fecho??0, 2, ',', '.')  }} {{ $dados->empresa->moeda }}</strong></td>
                    <td class="text-right">Utilizador</td>
                    @if (!empty($item->user_fecho))
                      <td class="text-left"><strong>
                        {{ $item->user->name ?? "" }}
                      </strong>
                    </td>
                    @else
                      <td class="text-left"><strong>N/A</strong></td>
                    @endif
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          @endforeach
          @endif
          @endif
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection