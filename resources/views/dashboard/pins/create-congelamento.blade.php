@extends('layouts.pim')

@section('content')
<div class="login-box">
            
    <div class="row">
        <div class="col-12 col-md-12">
            @if (Session::has('success'))
            <div class="alert alert-success text-center">
                {{ Session::get('success') }}
            </div>
            @endif
    
            @if (Session::has('danger'))
            <div class="alert alert-danger text-center">
                {{ Session::get('danger') }}
            </div>
            @endif
    
            @if (Session::has('warning'))
            <div class="alert alert-warning text-center">
                {{ Session::get('warning') }}
            </div>
            @endif
        </div>
    </div>
    
    <div class="card card-outline card-info">
        <div class="card-header text-center">
            <a href="{{ route('dashboard') }}" class="h1"><b>EA-</b>VIEGAS</a>
        </div>
        <div class="card-body pb-5">
            <p class="login-box-msg text-uppercase">Informe um codigo para congelar a sua conta.</p>
            <form action="{{ route('congelamento-pin-store') }}" method="post">
                @csrf
                <div class="my-3">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="password" id="codigo" value="{{ old('codigo') }}" name="codigo" class="form-control form-control-lg mb-2" placeholder="Codigo">
                    @error('codigo')
                    <p class="text-danger text-center text-uppercase">{{ $message }}</p>    
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn-lg btn-info btn-block">Confirmar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
