@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Perfils</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Perfils</li>
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
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">Novo Perfil</a>
              </h3>

              <div class="card-tools">
                <a class="btn btn-sm btn-danger" href="#"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="btn btn-sm btn-success" href="#"><i class="fas fa-file-excel"></i> EXCEL</a>
              </div>
            </div>

            @if ($roles)
            
            <div class="card-body">
              @foreach ($roles as $role)
              <div class="row">
                <div class="col-12 col-md-12 my-3">
                  <div class="bg-dark text-white p-3">{{ $role->name }}   <a class="float-right" href="{{ route('roles.edit', $role->id) }}"><i class="fas fa-edit text-success"></i> Actualizar permissões</a></div>
                </div>
                
                @foreach ($role->permissions as $permission)
                <div class="col-12 col-md-2">
                  <div class="form-group clearfix">
                      <div class="icheck-primary d-inline">
                        <input type="checkbox" id="permissions{{ $permission->id }}" value="{{ $permission->id }}" name="permissions[]" @if(in_array($permission->id, $permissions)) checked @endif>
                        <label for="permissions{{ $permission->id }}">
                          {{ $permission->name }}
                        </label>
                      </div>
                  </div>
                </div>     
                @endforeach
              
              </div>
              @endforeach
  
            </div>
     
            @endif

          </div>
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