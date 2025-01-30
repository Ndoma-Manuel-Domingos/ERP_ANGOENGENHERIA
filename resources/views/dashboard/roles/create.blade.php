@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1 class="m-0">Cadastrar Perfil</h1>
        </div><!-- /.col -->
        <div class="col-md-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Perfil</li>
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
            
            <form action="{{ route('roles.store') }}" method="post" class="">
              @csrf
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-12">
                    <label for="">Designação</label>
                    <input type="text" class="form-control" name="role" value="{{ old('role') }}" placeholder="Informe a Perfil">
                    <p class="text-danger">
                      @error('role')
                      {{ $message }}
                      @enderror
                    </p>
                  </div>
                  
                  <div class="col-12 col-md-12">
                    <h6 class="bg-light p-2 mb-4"><strong>Conceder Permissões</strong></h6>
                  </div>
                  
                  <div class="col-12">
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="select_all" />
                            <label for="select_all">
                                Selecionar Todos
                            </label>
                        </div>
                    </div>
                </div>
                  
                  
                  @foreach ($permissions as $permission)
                  <div class="col-12 col-md-2">
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                          <input type="checkbox" id="permissions{{ $permission->id }}" value="{{ $permission->id }}" name="permissions[]">
                          <label for="permissions{{ $permission->id }}">
                            {{ $permission->name }}
                          </label>
                        </div>
                    </div>
                  </div>     
                  @endforeach
                </div>
            
              </div>
    
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
              </div>
            </form>
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
    document.getElementById('select_all').addEventListener('click', function(event) {
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = event.target.checked;
        });
    });

</script>
@endsection
