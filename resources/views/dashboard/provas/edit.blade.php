@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Editar Prova</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('provas.index') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Formador</li>
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
                <form action="{{ route('provas.update', $prova->id) }}" method="post" class="">
                    @csrf
                    @method('put')
                    <div class="card-body">
                    
                        <div class="row">

                            <div class="col-12 col-md-2">
                                <label for="nome" class="form-label">Designação</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('nome') is_invalid @enderror" id="nome" name="nome" value="{{ $prova->nome ?? old('nome') }}" placeholder="Informe">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="descricao" class="form-label">Descrição</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('descricao') is_invalid @enderror" id="descricao" name="descricao" value="{{ $prova->descricao ?? old('descricao') }}" placeholder="Informe">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="nota_maxima" class="form-label">Nota Maxima</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="number" class="form-control @error('nota_maxima') is_invalid @enderror" id="nota_maxima" name="nota_maxima" value="{{ $prova->nota_maxima ??  old('nota_maxima') }}" placeholder="Informe">
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="data_at" class="form-label">Data Prova</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="date" class="form-control @error('data_at') is_invalid @enderror" id="data_at" name="data_at" value="{{ $prova->data_at ?? old('data_at') }}" placeholder="Informe">
                                </div>
                            </div>


                            <div class="col-12 col-md-2">
                                <label for="formador_id" class="form-label">Formadores</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <select type="text" class="form-control @error('record') is_invalid @enderror" id="formador_id" name="formador_id">
                                        @foreach ($formadores as $item)
                                        <option value="{{ $item->id }}" {{ $prova->formador_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="turma_id" class="form-label">Turmas</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <select type="text" class="form-control @error('record') is_invalid @enderror" id="turma_id" name="turma_id">
                                        @foreach ($turmas as $item)
                                        <option value="{{ $item->id }}" {{ $prova->turma_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                        </div>
                        
                        <div class="row">
                          <div class="col-12 col-md-12" id="dynamic-fields">
                              @foreach($prova->questoes as $index => $questao)
                                <div class="field-group">
                                    <div class="row">
  
                                        <div class="col-12 col-md-8">
                                            <label for="questao_1" class="form-label">Questão</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control @error('questao') is-invalid @enderror" value="{{ $questao->questao }}" name="questao[]" id="questao_1" placeholder="Informa a questão">
                                            </div>
                                        </div>
  
                                        <div class="col-12 col-md-2">
                                            <label for="nota_1" class="form-label">Nota</label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control @error('nota') is-invalid @enderror" value="{{ $questao->nota }}" name="nota[]" id="nota_1" placeholder="Informa a Nota">
                                            </div>
                                        </div>
  
                                        <div class="col-12 col-md-2">
                                            <label class="form-label">.</label>
                                            <div class="input-group mb-3">
                                                <button type="button" class="btn btn-danger remove-field"><i class="fas fa-trash"></i> Remover Questão</button>
                                            </div>
                                        </div>
  
                                        <div class="col-12 col-md-2">
                                            <label for="opcao_a_1" class="form-label">Opções A</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control @error('opcao_a') is-invalid @enderror" value="{{ $questao->opcao_a }}" name="opcao_a[]" id="opcao_a_1" placeholder="OPÇÃO A">
                                            </div>
                                        </div>
  
                                        <div class="col-12 col-md-2">
                                            <label for="opcao_b_1" class="form-label">Opções B</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control @error('opcao_b') is-invalid @enderror" value="{{ $questao->opcao_b }}" name="opcao_b[]" id="opcao_b_1" placeholder="OPÇÃO B">
                                            </div>
                                        </div>
  
                                        <div class="col-12 col-md-2">
                                            <label for="opcao_c_1" class="form-label">Opções C</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control @error('opcao_c') is-invalid @enderror" value="{{ $questao->opcao_c }}" name="opcao_c[]" id="opcao_c_1" placeholder="OPÇÃO C">
                                            </div>
                                        </div>
  
                                        <div class="col-12 col-md-2">
                                            <label for="opcao_d_1" class="form-label">Opções D</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control @error('opcao_d') is-invalid @enderror" value="{{ $questao->opcao_d }}" name="opcao_d[]" id="opcao_d_1" placeholder="OPÇÃO D">
                                            </div>
                                        </div>
  
                                        <div class="col-12 col-md-2">
                                            <label for="opcao_e_1" class="form-label">Opções E</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control @error('opcao_e') is-invalid @enderror" value="{{ $questao->opcao_e }}" name="opcao_e[]" id="opcao_e_1" placeholder="OPÇÃO E">
                                            </div>
                                        </div>
  
                                        <div class="col-12 col-md-2">
                                            <label for="opcao_certa_1" class="form-label">Opções Certa</label>
                                            <div class="input-group mb-3">
                                                <select class="form-control @error('opcao_certa') is-invalid @enderror" name="opcao_certa[]" id="opcao_certa_1">
                                                    <option value="a" {{ $questao->opcao_certa == 'a' ? 'selected' : '' }}>OPÇÃO A</option>
                                                    <option value="b" {{ $questao->opcao_certa == 'b' ? 'selected' : '' }}>OPÇÃO B</option>
                                                    <option value="c" {{ $questao->opcao_certa == 'c' ? 'selected' : '' }}>OPÇÃO C</option>
                                                    <option value="d" {{ $questao->opcao_certa == 'd' ? 'selected' : '' }}>OPÇÃO D</option>
                                                    <option value="e" {{ $questao->opcao_certa == 'e' ? 'selected' : '' }}>OPÇÃO E</option>
                                                </select>
                                            </div>
                                        </div>
  
                                    </div>
                                </div>
                              @endforeach
                          </div>
                      </div>
                        
                    </div>

                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Salvar</button>
                      <button type="reset" class="btn btn-danger">Cancelar</button>
                      
                      
                      <button type="button" id="add-field" class="btn btn-success my-4 mx-2 float-right"><i class="fas fa-plus"></i> Adicionar Questões</button>
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


@section('scripts')
<script>
    $(document).ready(function() {
        let maxFields = 10; // Limite de campos dinâmicos
        let fieldCount = 1; // Contador de campos dinâmicos

        // Função para adicionar novo campo
        $('#add-field').click(function() {
            if (fieldCount < maxFields) {
                fieldCount++;
                let newFieldGroup = $('.field-group:first').clone();
                newFieldGroup.find('input, select').each(function() {
                    let currentId = $(this).attr('id');
                    let currentName = $(this).attr('name');
                    let newId = currentId.replace(/_\d+$/, '_' + fieldCount);
                    let newName = currentName.replace(/\[\]$/, '[]');
                    $(this).attr('id', newId);
                    $(this).attr('name', newName);
                    $(this).val(''); // Limpar valores
                });
                newFieldGroup.appendTo('#dynamic-fields');
            } else {
                alert('Você só pode adicionar até 10 campos.');
            }
        });

        // Função para remover campo
        $('#dynamic-fields').on('click', '.remove-field', function() {
            if (fieldCount > 1) {
                $(this).closest('.field-group').remove();
                fieldCount--;
            } else {
                alert('Você deve ter pelo menos um campo.');
            }
        });

    });

</script>
@endsection
