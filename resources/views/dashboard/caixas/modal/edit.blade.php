<form action="{{ route('caixas.update', $caixa->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="modal fade" id="modalUpdateCaixa{{ $caixa->id }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __("Editar Caixa") }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nome" value="{{ $caixa->nome }}"
                                    placeholder="Informe a Marca">
                            </div>
                            <p class="text-danger">
                                @error('nome')
                                {{ $message }}
                                @enderror
                            </p>
                        </div>

                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <select type="text" class="form-control" name="status">
                                    <option value="aberto" {{ $caixa->status == "aberto" ? 'selected' : '' }}>Activo
                                    </option>
                                    <option value="fechado" {{ $caixa->status == "fechado" ? 'selected' : ''
                                        }}>Desactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</form>