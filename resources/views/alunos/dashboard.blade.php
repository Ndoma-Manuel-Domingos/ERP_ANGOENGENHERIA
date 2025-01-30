@extends('layouts.alunos')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Meu Perfil</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard-alunos') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inicio</li>
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
                <div class="col-12 col-md-6">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr>
                                <th>Nome</th>
                                <td class="text-right">{{ $entidade->aluno->nome ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>Genero</th>
                                <td class="text-right">{{ $entidade->aluno->genero ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>Estado Cívil</th>
                                <td class="text-right">{{ $entidade->aluno->estado_civil ?? '-------------' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12 col-md-6">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr>
                                <th>Bilhete</th>
                                <td class="text-right">{{ $entidade->aluno->nif ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>País</th>
                                <td class="text-right">{{ $entidade->aluno->pais ?? '-------------' }}</td>
                            </tr>

                            <tr>
                                <th>Telefone/E-mail</th>
                                <td class="text-right">{{ $entidade->aluno->telefone ?? '-------------' }} / {{ $entidade->aluno->email ?? '-------------' }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-info" title="Hospedes">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Solicitar Documentos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-documentos.index') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-info" title="Hospedes">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Provas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-provas') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-info" title="Reservas">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Matrículas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-matriculass') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-info" title="Reservas">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">Conteúdos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-videos.conteudo') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="small-box bg-info" title="Reservas">
                        <div class="inner">
                            <h3>:</h3>
                            <p class="text-uppercase">vídeos</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('alunos-videos.videos') }}" class="small-box-footer">Mais informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
            
            <div class="row">
                
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6><strong>Dados da Turma</strong></h6>
                        </div>
                        <div class="card-body">
                            <table class="table text-nowrap">
                                <tbody>
                                    @foreach ($alunoTurmas as $item)
                                    <tr>
                                        <th>Curso</th>
                                        <td class="text-right"><strong>{{ $item->turma->curso->nome ?? '-------------' }} </td>
                                    </tr>

                                    <tr>
                                        <th>Sala</th>
                                        <td class="text-right">{{ $item->turma->sala->nome ?? '-------------' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Turno</th>
                                        <td class="text-right">{{ $item->turma->turno->nome ?? '-------------' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6><strong>Modulos/Disciplinas do Curso</strong></h6>
                        </div>
                        <div class="card-body">
                            <table class="table text-nowrap">
                                <tbody>
                                    @foreach ($alunoTurmas as $item)
                                    @if ($item->turma->curso->modulos && count($item->turma->curso->modulos) != 0)
                                    @foreach ($item->turma->curso->modulos as $i)
                                    <tr>
                                        <td class="text-right">
                                            {{ $i->nome }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6><strong>Dados dos Formadores</strong></h6>
                        </div>
                        <div class="card-body">
                            <table class="table text-nowrap">
                                <tbody>
                                    @foreach ($alunoTurmas as $item)
                                        @if ($item->turma->formadores)
                                            @foreach ($item->turma->formadores as $it)
                                                <tr>
                                                    <th>Nome</th>
                                                    <td class="text-right"><strong>{{ $it->formador->nome ?? '-------------' }} </td>
                                                </tr>
            
                                                <tr>
                                                    <th>Telefone</th>
                                                    <td class="text-right">{{ $it->formador->telefone?? '-------------' }}</td>
                                                </tr>
            
                                                <tr>
                                                    <th>E-mail</th>
                                                    <td class="text-right">{{ $it->formador->email ?? '-------------' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
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
