@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Balancete do razão geral</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Starter Page</li>
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
                        <div class="card-body table-responsive">
                            <table class="table table-hover text-nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th rowspan="2">Código</th>
                                        <th rowspan="2">Descrição</th>
                                        <th>Movimentos à débito</th>
                                        <th>Movimentos à crédito</th>
                                        <th>Saldos</th>
                                        <th>Saldos</th>
                                    </tr>
                                    
                                    <tr>
                                        <th>Débito</th>
                                        <th>Crédito</th>
                                        <th>Devedor</th>
                                        <th>Credor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div>
                                        <tr>
                                            <th colspan="6" class="">ACTIVO</td>
                                        </tr>
                                        
                                        @for ($i = 0; $i < 3; $i++)
                                            <tr>
                                                <td>4325</td>
                                                <td>#Prod</td>
                                                
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                            </tr>
                                        @endfor
                                        <tr>
                                            <th colspan="2">Total de movimentos</th>
                                            
                                            <th>2.000,00</th>
                                            <th>2.000,00</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        
                                        <tr>
                                            <th colspan="2">Total de saldos</th>
                                            
                                            <th></th>
                                            <th></th>
                                            <th>2.000,00</th>
                                            <th>2.000,00</th>
                                        </tr>
                                    </div>
                                    
                                    <div>
                                        <tr>
                                            <th colspan="6" class="">PASSIVO</td>
                                        </tr>
                                        
                                        @for ($i = 0; $i < 3; $i++)
                                            <tr>
                                                <td>4325</td>
                                                <td>#Prod</td>
                                                
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                            </tr>
                                        @endfor
                                        <tr>
                                            <th colspan="2">Total de movimentos</th>
                                            
                                            <th>2.000,00</th>
                                            <th>2.000,00</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        
                                        <tr>
                                            <th colspan="2">Total de saldos</th>
                                            
                                            <th></th>
                                            <th></th>
                                            <th>2.000,00</th>
                                            <th>2.000,00</th>
                                        </tr>
                                    </div>
                                    
                                    <div>
                                        <tr>
                                            <th colspan="6" class="">PROVEITOS</td>
                                        </tr>
                                        
                                        @for ($i = 0; $i < 3; $i++)
                                            <tr>
                                                <td>4325</td>
                                                <td>#Prod</td>
                                                
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                            </tr>
                                        @endfor
                                        <tr>
                                            <th colspan="2">Total de movimentos</th>
                                            
                                            <th>2.000,00</th>
                                            <th>2.000,00</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        
                                        <tr>
                                            <th colspan="2">Total de saldos</th>
                                            
                                            <th></th>
                                            <th></th>
                                            <th>2.000,00</th>
                                            <th>2.000,00</th>
                                        </tr>
                                    </div>
                                    
                                    <div>
                                        <tr>
                                            <th colspan="6" class="">CUSTOS</td>
                                        </tr>
                                        
                                        @for ($i = 0; $i < 3; $i++)
                                            <tr>
                                                <td>4325</td>
                                                <td>#Prod</td>
                                                
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                                <td>2.000,00</td>
                                            </tr>
                                        @endfor
                                        <tr>
                                            <th colspan="2">Total de movimentos</th>
                                            
                                            <th>2.000,00</th>
                                            <th>2.000,00</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        
                                        <tr>
                                            <th colspan="2">Total de saldos</th>
                                            
                                            <th></th>
                                            <th></th>
                                            <th>2.000,00</th>
                                            <th>2.000,00</th>
                                        </tr>
                                    </div>
                                    
                                    <tr>
                                        <th colspan="2">Total de movimentos</th>
                                        
                                        <th>2.000,00</th>
                                        <th>2.000,00</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    
                                    <tr>
                                        <th colspan="2">Total de saldos</th>
                                        
                                        <th></th>
                                        <th></th>
                                        <th>2.000,00</th>
                                        <th>2.000,00</th>
                                    </tr>
                                    
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th>2.000,00</th>
                                        <th>2.000,00</th>
                                        <th>2.000,00</th>
                                        <th>2.000,00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('dashboard.config.modal.dados-empresa')
@endsection
