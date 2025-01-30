@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Caixa</h1>
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
            <form action="{{ route('caixas.update', $caixa->id) }}" method="post" class="">
              @csrf
              @method('put')
              <div class="card-body row">
                <div class="col-12 col-md-12 mb-3">
                  <label for="" class="form-label">Nome</label>
                  <input type="text" class="form-control" name="nome" value="{{ $caixa->nome }}" placeholder="Informe a Marca">
                  <p class="text-danger">
                    @error('nome')
                    {{ $message }}
                    @enderror
                  </p>
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Estado</label>
                  <select type="text" class="form-control" name="status">
                      <option value="aberto" {{ $caixa->status == "aberto" ? 'selected' : '' }}>Activo</option>
                      <option value="fechado" {{ $caixa->status == "fechado" ? 'selected' : '' }}>Desactivo</option>
                  </select>
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Tipo do caixa <span class="text-secondary">(Opcional)</span></label>
                    <select name="tipo_caixa" id="tipo_caixa" class="form-control">
                    <option value="">Informe o tipo do caixa</option>
                    <option value="auto" {{ $caixa->tipo_caixa == "auto" ? 'selected' : '' }} >Automático</option>
                    <option value="pos" {{ $caixa->tipo_caixa == "pos" ? 'selected' : '' }}>Normal (Venda Produtos/Serviços)</option>
                    <option value="rest" {{ $caixa->tipo_caixa == "rest" ? 'selected' : '' }}>Restaurante (Gestão de Salas/Mesas)</option>
                    <option value="api" {{ $caixa->tipo_caixa == "api" ? 'selected' : '' }}>API (Integração Programática)</option>
                    <option value="office" {{ $caixa->tipo_caixa == "office" ? 'selected' : '' }}>Office</option>
                    <option value="rest_terminal" {{ $caixa->tipo_caixa == "rest_terminal" ? 'selected' : '' }}>Terminal de Pedidos (Pedidos à Mesa/Cozinha)</option>
                  </select>
                  <p class="text-danger">
                    @error('tipo_caixa')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Vencimento <span class="text-secondary">(Opcional)</span></label>
                  <select name="vencimento" class="form-control" id="vencimento">
                    <option value="">Vencimento</option>
                    <option value="now" {{ $caixa->vencimento == "now" ? 'selected' : '' }}>A Pronto</option>
                    <option value="15" {{ $caixa->vencimento == "15" ? 'selected' : '' }}>A Prazo de 15 Dias</option>
                    <option value="30" {{ $caixa->vencimento == "30" ? 'selected' : '' }}>A Prazo de 30 Dias</option>
                    <option value="60" {{ $caixa->vencimento == "60" ? 'selected' : '' }}>A Prazo de 60 Dias</option>
                    <option value="90" {{ $caixa->vencimento == "90" ? 'selected' : '' }}>A Prazo de 90 Dias</option>
                  </select>
                  <p class="text-danger">
                    @error('vencimento')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Documento Predefinido <span class="text-secondary">(Opcional)</span></label>
                  <select name="documento_predefinido" class="form-control" id="documento_predefinido" aria-placeholder="Documento Predefinido">
                    <option value="FT" {{ $caixa->documento_predefinido == "FT" ? 'selected' : '' }}>Factura</option>
                    <option value="FG" {{ $caixa->documento_predefinido == "FG" ? 'selected' : '' }}>Factura Global</option>
                    <option value="FR" {{ $caixa->documento_predefinido == "FR" ? 'selected' : '' }}>Factura Recibo</option>
                    <option value="NC" {{ $caixa->documento_predefinido == "NC" ? 'selected' : '' }}>Nota de Crédito</option>
                    <option value="PP" {{ $caixa->documento_predefinido == "PP" ? 'selected' : '' }}>Factura Pró-Forma</option>
                    <option value="OT" {{ $caixa->documento_predefinido == "OT" ? 'selected' : '' }}>Orçamento</option>
                    <option value="EC" {{ $caixa->documento_predefinido == "EC" ? 'selected' : '' }}>Encomenda</option>
                    <option value="GT" {{ $caixa->documento_predefinido == "GT" ? 'selected' : '' }}>Guia de Transporte</option>
                    <option value="GR" {{ $caixa->documento_predefinido == "GT" ? 'selected' : '' }}>Guia de Remessa</option>
                  </select>
                  <p class="text-danger">
                    @error('documento_predefinido')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Aspecto <span class="text-secondary">(Opcional)</span></label>
                  <select name="aspecto" class="form-control" id="aspecto" aria-placeholder="Documento Predefinido">
                    <option value="">Aspecto</option>
                    <option value="">Automático</option>
                    <option value="-1" {{ $caixa->aspecto == "-1" ? 'selected' : '' }}>Automático - Listagem</option>
                    <option value="107613683" {{ $caixa->aspecto == "107613683" ? 'selected' : '' }}>Personalizado - Layout01</option>
                  </select>
                  <p class="text-danger">
                    @error('aspecto')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Metódo de Impressão <span class="text-secondary">(Opcional)</span></label>
                  <select name="metodo_impressao" class="form-control" id="metodo_impressao" aria-placeholder="Documento Predefinido">
                    <option value="">Metódo de Impressão</option>
                    <option value="browser" {{ $caixa->metodo_impressao == "browser" ? 'selected' : '' }}>Impressão pelo Navegador</option>
                    <option value="desktop" {{ $caixa->metodo_impressao == "desktop" ? 'selected' : '' }}>Aplicação de Impressão</option>
                  </select>
                  <p class="text-danger">
                    @error('metodo_impressao')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Modelo <span class="text-secondary">(Opcional)</span></label>
                  <select name="modelo" class="form-control" id="modelo" aria-placeholder="Documento Predefinido">
                    <option value="">Modelo</option>
                    <option value="auto" {{ $caixa->status == "auto" ? 'selected' : '' }}>Automático</option>
                    <option value="107611658" {{ $caixa->modelo == "107611658" ? 'selected' : '' }}>Documento A4</option>
                    <option value="107611659" {{ $caixa->modelo == "107611659" ? 'selected' : '' }}>Recibo principal</option>
                  </select>
                <p class="text-danger">
                    @error('modelo')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Impressão em Papel <span class="text-secondary">(Opcional)</span></label>
                  <select name="impressao_papel" class="form-control" id="impressao_papel" aria-placeholder="Documento Predefinido">
                    <option value="sim" {{ $caixa->impressao_papel == "sim" ? 'selected' : '' }}>Sim</option>
                    <option value="nao" {{ $caixa->impressao_papel == "nao" ? 'selected' : '' }}>Não</option>
                  </select>
                  <p class="text-danger">
                    @error('impressao_papel')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Modelo de E-mail <span class="text-secondary">(Opcional)</span></label>
                  <select name="modelo_email" class="form-control" id="modelo_email" aria-placeholder="Documento Predefinido">
                    <option value="auto" {{ $caixa->modelo_email == "auto" ? 'selected' : '' }}>Automático</option>
                    <option value="107611658" {{ $caixa->modelo_email == "107611658" ? 'selected' : '' }}>Documento A4</option>
                  </select>
                  <p class="text-danger">
                    @error('modelo_email')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Finalização Avançado <span class="text-secondary">(Opcional)</span></label>
                  <select name="finalizar_avancado" class="form-control" id="finalizar_avancado" aria-placeholder="Documento Predefinido">
                    <option value="sim" {{ $caixa->finalizar_avancado == "sim" ? 'selected' : '' }}>Sim</option>
                    <option value="nao" {{ $caixa->finalizar_avancado == "nao" ? 'selected' : '' }}>Não</option>
                  </select>
                  <p class="text-danger">
                    @error('finalizar_avancado')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Referências de Produtos <span class="text-secondary">(Opcional)</span></label>
                  <select name="referencia_produtos" class="form-control" id="referencia_produtos" aria-placeholder="Documento Predefinido">
                    <option value="nao" {{ $caixa->referencia_produtos == "nao" ? 'selected' : '' }}>Omitir Referência</option>
                    <option value="sim" {{ $caixa->referencia_produtos == "sim" ? 'selected' : '' }}>Mostrar Referência</option>
                  </select>
                  <p class="text-danger">
                    @error('referencia_produtos')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Preços de Produtos <span class="text-secondary">(Opcional)</span></label>
                  <select name="precos_produtos" class="form-control" id="precos_produtos" aria-placeholder="Documento Predefinido">
                    <option value="sim" {{ $caixa->precos_produtos == "sim" ? 'selected' : '' }}>Preços com IVA</option>
                    <option value="nao" {{ $caixa->precos_produtos == "nao" ? 'selected' : '' }}>Preços sem IVA</option>
                  </select>          
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Modo de Funcionamento <span class="text-secondary">(Opcional)</span></label>
                  <select name="modo_funcionamento" class="form-control" id="modo_funcionamento" aria-placeholder="Documento Predefinido">
                    <option value="normal" {{ $caixa->modo_funcionamento == "normal" ? 'selected' : '' }}>Normal</option>
                    <option value="tests" {{ $caixa->modo_funcionamento == "tests" ? 'selected' : '' }}>Formação/Testes</option>
                  </select>
                  <p class="text-danger">
                    @error('modo_funcionamento')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Listar Produtos <span class="text-secondary">(Opcional)</span></label>
                    <select name="listar_produtos" class="form-control" id="listar_produtos" aria-placeholder="Listar Produtos">
                    <option value="all" {{ $caixa->listar_produtos == "all" ? 'selected' : '' }}>Todos</option>
                    <option value="without_barcode" {{ $caixa->listar_produtos == "without_barcode" ? 'selected' : '' }}>Sem código de barras</option>
                    <option value="none" {{ $caixa->listar_produtos == "none" ? 'selected' : '' }}>Não (usar apenas pesquisa ou código de barras)</option>
                  </select>
                  <p class="text-danger">
                    @error('listar_produtos')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Grupo de Preços <span class="text-secondary">(Opcional)</span></label>
                  <select name="grupo_precos" class="form-control" id="grupo_precos" aria-placeholder="Listar Produtos">
                    <option value="0">-- Selecione --</option>
                    <option value="107611646" {{ $caixa->grupo_precos == "107611646" ? 'selected' : '' }}>Normal</option>
                    <option value="107766080" {{ $caixa->grupo_precos == "107766080" ? 'selected' : '' }}>Outro</option>
                  </select>
                  <p class="text-danger">
                    @error('grupo_precos')
                    {{ $message }}
                    @enderror
                  </p>              
                </div>
    
                <div class="col-12 col-md-3 mb-3">
                  <label for="" class="form-label">Numeração Pedidos de Mesa <span class="text-secondary">(Opcional)</span></label>
                  <select name="numeracao_pedidos_mesa" class="form-control" id="numeracao_pedidos_mesa" aria-placeholder="Listar Produtos">
                    <option value="">Numeração Pedidos de Mesa:</option>
                    <option value="sim" {{ $caixa->numeracao_pedidos_mesa == "sim" ? 'selected' : '' }}>Sim</option>
                    <option value="nao" {{ $caixa->numeracao_pedidos_mesa == "nao" ? 'selected' : '' }}>Não</option>
                  </select>
                  <p class="text-danger">
                    @error('numeracao_pedidos_mesa')
                    {{ $message }}
                    @enderror
                  </p>              
              </div>
    
              </div>
    
              <div class="card-footer">
                @if (Auth::user()->can('editar caixa'))
                <button type="submit" class="btn btn-primary">Salvar</button>
                @endif
                <button type="reset" class="btn btn-danger">Cancelar</button>
              </div>
            </form>
          </div>
          <!-- /.row -->
        </div>
      </div>
      
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection