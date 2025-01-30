@extends('layouts.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header"> </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid pt-5">
        
            <form action="{{ route('caixa.caixas-create-update') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-1 col-12 col-md-1"></div>
                    
                    <div class="col-lg-10 col-12 col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-12 py-2 text-center">
                                        <label for="form-control">ESCOLHE UMA CAIXA PARA COMERES  A OPERAR </label>
                                    </div>
                                    @if ($caixas)
                                        @foreach ($caixas as $caixa)
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="icheck-primary d-block bg-light p-3">
                                                <input type="radio" id="radioPrimary{{ $caixa->id }}" name="caixa" value="{{ $caixa->id }}" {{ $caixa->active == '1' ? 'checked' : ''
                                                }}>
                                                <label for="radioPrimary{{ $caixa->id }}">
                                                    {{ $caixa->nome }} <br>
                                                    {{ $caixa->loja->nome }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-12">
                                <div class="card">
                                    <button type="submit" class="btn btn-primary btn-flat col-12 col-md-12 p-3 text-center float-right">
                                        <span class="h3 text-white text-uppercase"><i class="fas fa-check"></i> Iniciar </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-lg-1 col-12 col-md-1"></div>
                </div>
                <!-- /.row -->
            </form>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
    $(function() {
        $('#defaultKeypad').keypad();
        $('#inlineKeypad').keypad({
            onClose: function() {
                alert($(this).val());
            }
        });
    });

</script>
@endsection
