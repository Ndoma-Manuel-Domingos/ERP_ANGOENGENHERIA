@extends('layouts.app')

@section('content')

<style>
    .fc-toolbar h2 {
        text-transform: capitalize;
    }

    .fc-day-header {
        text-transform: capitalize;
    }

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Criar Marcações de Ferias</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('marcacoes-ferias.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Marcações de Faltas</li>
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
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12 ">
                                    <div class="row">
                                    
                                        <div class="col-12 col-md-6">
                                          <label for="funcionario_id" class="form-label">Funcioários</label>
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control @error('funcionario_id') is-invalid @enderror" id="funcionario_id" name="funcionario_id">
                                              @foreach ($funcionarios as $item)
                                              <option value="{{ $item->id }}">{{ $item->numero_mecanografico }} - {{ $item->nome }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                        </div>
                                    
                                        <div class="col-12 col-md-3">
                                          <label for="exercicio_id" class="form-label">Exercícios</label>
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control @error('exercicio_id') is-invalid @enderror" id="exercicio_id" name="exercicio_id">
                                              @foreach ($exercicios as $item)
                                              <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                        </div>
                                    
                                        <div class="col-12 col-md-3">
                                          <label for="periodo_id" class="form-label">Períodos</label>
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <select type="text" class="form-control @error('periodo_id') is-invalid @enderror" id="periodo_id" name="periodo_id">
                                              @foreach ($periodos as $item)
                                              <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                        </div>
                                 
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div id="calendar"></div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            {{-- @if (Auth::user()->can('criar subsidio')) --}}
                            <button id="saveDates" class="btn btn-primary" style="margin-top: 10px; display: none;">Salvar Dados</button>
                            {{-- @endif --}}
                        </div>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var saveButton = document.getElementById('saveDates');
        var selectedDates = [];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth'
            , hiddenDays: [0, 6]
            , height: 600
            , locale: 'pt-br'
            , selectable: true
            , unselectAuto: false
            , select: function(info) {
                if (!selectedDates.includes(info.startStr)) {
                    selectedDates.push(info.startStr);
                } else {
                    selectedDates = selectedDates.filter(date => date !== info.startStr);
                }

                calendar.addEvent({
                    title: 'Selecionado'
                    , start: info.startStr
                    , allDay: true
                    , backgroundColor: '#FF0000'
                });

                if (selectedDates.length > 0) {
                    saveButton.style.display = 'block';
                } else {
                    saveButton.style.display = 'none';
                }
            }
        });

        calendar.render();

        saveButton.addEventListener('click', function() {
            var funcionarioId = $('#funcionario_id').val();
            var exercicio_id = $('#exercicio_id').val();
            var periodo_id = $('#periodo_id').val();

            if (funcionarioId === "") {
                alert('Por favor, selecione um funcionário.');
                return;
            }

            $.ajax({
                url: '/marcacoes-ferias'
                , method: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}', // Laravel CSRF token
                    funcionario_id: funcionarioId
                    , exercicio_id: exercicio_id
                    , periodo_id: periodo_id
                    , datas: selectedDates
                }, 
                success: function(response) {
                    // alert('ferias salvas com sucesso!');
                    selectedDates = [];
                    saveButton.style.display = 'none';
                    calendar.refetchEvents();
                }, 
                error: function(xhr, status, error) {
                    // Verifica se a resposta contém JSON
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        console.log(xhr.responseJSON.error);
                        alert(xhr.responseJSON.error);
                    } else {
                        console.log(error);
                        alert('Erro desconhecido: ' + error);
                    }
                }
            });
        });
    });

</script>
@endsection
