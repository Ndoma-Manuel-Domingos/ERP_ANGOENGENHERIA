<?php

use App\Http\Controllers\AGTController;
use App\Http\Controllers\app\AgendamentoController;
use App\Http\Controllers\app\AlunoController;
use App\Http\Controllers\app\AlunoDocumentoController;
use App\Http\Controllers\app\AnoLectivoController;
use App\Http\Controllers\app\app\CaixaController as AppCaixaController;
use App\Http\Controllers\app\app\FacturasController as AppFacturasController;
use App\Http\Controllers\app\app\VendaController;
use App\Http\Controllers\app\AppController;
use App\Http\Controllers\app\ContaBancariaController;
use App\Http\Controllers\app\TipoCreditoController;
use App\Http\Controllers\app\CaixaController;
use App\Http\Controllers\app\CargoController;
use App\Http\Controllers\app\CategoriaCargoController;
use App\Http\Controllers\app\CategoriaController;
use App\Http\Controllers\app\ClienteController;
use App\Http\Controllers\app\ContaClienteController;
use App\Http\Controllers\app\ContratoController;
use App\Http\Controllers\app\CursoController;
use App\Http\Controllers\app\DepartamentoController;
use App\Http\Controllers\app\DistritoController;
use App\Http\Controllers\app\EmpresaController;
use App\Http\Controllers\app\EnfermeiroController;
use App\Http\Controllers\app\EstoqueController;
use App\Http\Controllers\app\ExercicioController;
use App\Http\Controllers\app\TipoRendimentoController;
use App\Http\Controllers\app\PeriodoRendimentoController;
use App\Http\Controllers\app\FacturaEncomendaFornecedorController;
use App\Http\Controllers\app\FacturasController;
use App\Http\Controllers\app\FormadorController;
use App\Http\Controllers\app\FuncionarioController;
use App\Http\Controllers\app\FornecedorController;
use App\Http\Controllers\app\LojaController;
use App\Http\Controllers\app\LoteController;
use App\Http\Controllers\app\MarcacaoAusenciaController;
use App\Http\Controllers\app\MarcacaoFaltasController;
use App\Http\Controllers\app\MarcacaoFeriaController;
use App\Http\Controllers\app\MarcaController;
use App\Http\Controllers\app\MedicoController;
use App\Http\Controllers\app\MesaController;
use App\Http\Controllers\app\ModuloEntidadeController;
use App\Http\Controllers\app\MotivoAusenciaController;
use App\Http\Controllers\app\MotivoSaidaController;
use App\Http\Controllers\app\MovimentoEstoqueController;
use App\Http\Controllers\app\MunicipioController;
use App\Http\Controllers\app\PacoteSalarialController;
use App\Http\Controllers\app\PeriodoController;
use App\Http\Controllers\app\ContaController;
use App\Http\Controllers\app\PermissionController;
use App\Http\Controllers\app\PinController;
use App\Http\Controllers\app\ProcessamentoController;
use App\Http\Controllers\app\ProdutoCompraController;
use App\Http\Controllers\app\ProdutoController;
use App\Http\Controllers\app\ProvinciaController;
use App\Http\Controllers\app\RegistroCompraProdutoController;
use App\Http\Controllers\app\RenovacaoContratoController;
use App\Http\Controllers\app\RoleController;
use App\Http\Controllers\app\SalaController;
use App\Http\Controllers\app\SeguradoraController;
use App\Http\Controllers\app\SubsidioController;
use App\Http\Controllers\app\DescontoController;
use App\Http\Controllers\app\ProvaController;
use App\Http\Controllers\app\QuartoController;
use App\Http\Controllers\app\AndarController;
use App\Http\Controllers\app\AnuncioAdminController;
use App\Http\Controllers\app\AnuncioController;
use App\Http\Controllers\app\ClasseController;
use App\Http\Controllers\app\ConfiguracaoHRController;
use App\Http\Controllers\app\ContrapartidaController;
use App\Http\Controllers\app\DispesaController;
use App\Http\Controllers\app\EquipamentoActivoController;
use App\Http\Controllers\app\ModuloCursoController;
use App\Http\Controllers\app\OperacaoFinanceiraController;
use App\Http\Controllers\app\ProvaFormadorController;
use App\Http\Controllers\app\ReceitaController;
use App\Http\Controllers\app\ReservaController;
use App\Http\Controllers\app\TarefarioController;
use App\Http\Controllers\app\SubcontaController;
use App\Http\Controllers\app\TabelaTaxaReintegracaoAmortizacaoController;
use App\Http\Controllers\app\TipoQuartoController;
use App\Http\Controllers\app\TipoContratoController;
use App\Http\Controllers\app\TipoEntidadeController;
use App\Http\Controllers\app\TipoPagamentoController;
use App\Http\Controllers\app\TipoProcessamentoController;
use App\Http\Controllers\app\TurmaController;
use App\Http\Controllers\app\TurnoController;
use App\Http\Controllers\app\VariacaoController;
use App\Http\Controllers\app\VideoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\config\CategoriaController as ConfigCategoriaController;
use App\Http\Controllers\config\ConfiguracaoEmpressoraController;
use App\Http\Controllers\config\DadosEmpresaController;
use App\Http\Controllers\config\IdentidadeEmpresaController;
use App\Http\Controllers\config\PersonalizarEmpressoraController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ContabilidadeController;
use App\Http\Controllers\EncomendaForncedorController;
use App\Http\Controllers\ExameController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\HomeAlunoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeFormadorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\pdf\FacturasFacturacaoController;
use App\Http\Controllers\pdf\FacturasInformativoController;
use App\Http\Controllers\pdf\FacturasSemPagamentoController;
use App\Http\Controllers\pdf\PDFController;
use App\Http\Controllers\PlanoGeralContaController;
use App\Http\Controllers\RequisacaoController;
use App\Http\Controllers\ScreenLockController;
use App\Http\Controllers\SegurancaController;
use App\Http\Controllers\TriagemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Session;


// dd(Hash::make("123456"));

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth::routes();
Route::get('/', [AppController::class, 'login'])->name('login_');
  
    Route::get('/login', [AppController::class, 'login'])->name('login');
    Route::post('/login', [AppController::class, 'check'])->name('check');
    Route::get('/register', [AppController::class, 'register'])->name('register');
    Route::post('/register', [AppController::class, 'create'])->name('create');
    Route::get('redefinir-minha-senha', [AppController::class, 'definir_senha'])->name('update_pass');
    Route::post('/redefinir-minha-senha', [AppController::class, 'definir_senha_check'])->name('definir_senha_check');
        
    Route::get('/congelamento', [SegurancaController::class, 'pin'])->name('congelamento-pin');
    Route::post('/congelamento-post', [SegurancaController::class, 'pin_post'])->name('congelamento-pin-post');
    Route::get('/crair-congelamento', [SegurancaController::class, 'create'])->name('congelamento-pin-create');
    Route::post('/crair-congelamento', [SegurancaController::class, 'store'])->name('congelamento-pin-store');
    Route::get('/licenca-activa', [SegurancaController::class, 'licenca'])->name('licenca-activa');
    Route::post('/licenca-activa-post', [SegurancaController::class, 'licenca_post'])->name('licenca-activa-post');
    
    Route::get('/screen-locked', [ScreenLockController::class, 'show'])->name('screen.locked');
    Route::post('/unlock-screen', [ScreenLockController::class, 'unlock'])->name('screen.unlock');

    Route::group(["middleware" => ['auth', 'check.pin.active', 'check.licenca.active', 'check.inactivity']], function () {
        
        Route::get('/administrador', [HomeController::class, 'admin'])->name('dashboard-admin');
        Route::get('/gerar-licenca-administrador', [HomeController::class, 'gerar_licenca_configuracao'])->name('gerar-licenca-configuracao-admin');
        Route::post('/gerar-licenca-administrador', [HomeController::class, 'gerar_licenca_configuracao_post'])->name('gerar-licenca-configuracao-admin-post');
        Route::get('/configuracao-administrador', [HomeController::class, 'configuracao'])->name('configuracao-admin');
        Route::post('/configuracao-administrador', [HomeController::class, 'configuracao_post'])->name('configuracao-admin-post');
        Route::resource('/empresas', EmpresaController::class);
        Route::get('/nossos-utilizadores', [EmpresaController::class, 'home'])->name('nossos-utilizadores');
        Route::get('/nossos-utilizadores-pdf', [EmpresaController::class, 'nosso_utilizadores_pdf'])->name('nosso-utilizadores-pdf');
        Route::get('/nossas-empresas-pdf', [EmpresaController::class, 'nosso_empresas_pdf'])->name('nossa-empresas-pdf');
        
        Route::get('/empresas/{id}/mudar-controlo', [EmpresaController::class, 'controlo'])->name('empresas.controlo');
        Route::get('/empresas/{id}/desactivar', [EmpresaController::class, 'desactivar'])->name('empresas.desactivar');
        Route::get('/empresas/{id}/actvar', [EmpresaController::class, 'actvar'])->name('empresas.actvar');
        Route::get('/empresas/{id}/destroy', [EmpresaController::class, 'destroy'])->name('empresas.destroys');
        
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

        
        // Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/dashboard/configuracao', [HomeController::class, 'configuracao_operacoes'])->name('dashboard.configuracao');
        Route::get('/dashboard/configuracao-inicializacao', [HomeController::class, 'configuracao_inicializacao'])->name('dashboard.configuracao-inicializacao');
        Route::get('/dashboard/configuracao-finalizacao', [HomeController::class, 'configuracao_finalizacao'])->name('dashboard.configuracao-finalizacao');
        Route::get('/taxas-irt', [HomeController::class, 'taxa_irt'])->name('taxa_irt');

        Route::get('/dashboard/venda', [VendaController::class, 'vendas'])->name('vendas');
        Route::get('/dashboard/venda-produtos', [VendaController::class, 'vendas_produtos'])->name('vendas_produtos');
        Route::get('/dashboard/venda-por-produtos', [VendaController::class, 'vendas_por_produtos'])->name('vendas_por_produtos');
        Route::get('/dashboard/venda-por-artigo', [VendaController::class, 'vendas_por_artigo'])->name('vendas_por_artigo');
        Route::get('/dashboard/venda-por-artigo-anterior', [VendaController::class, 'vendas_por_artigo_anterior'])->name('vendas_por_artigo_anterior');
        Route::get('/dashboard/pronto-venda', [VendaController::class, 'pronto_vendas'])->name('pronto-venda');
        Route::get('/dashboard/pronto-venda-mesas', [VendaController::class, 'pronto_vendas_mesas'])->name('pronto-venda-mesas');
        Route::get('/dashboard/pronto-venda-mesas/{id}', [VendaController::class, 'pronto_vendas_mesas_pedidos'])->name('pronto-venda-mesas-pedidos');
        Route::get('/dashboard/pronto-venda-quartos', [VendaController::class, 'pronto_vendas_quatros'])->name('pronto-venda-quartos');
        Route::get('/dashboard/pronto-venda-quartos/{id}', [VendaController::class, 'pronto_vendas_mesas_quartos'])->name('pronto-venda-mesas-quartos');
        Route::get('/dashboard/buscar-produto', [VendaController::class, 'buscar_produto'])->name('buscar-produto-venda');
        Route::post('/dashboard/buscar-produto-codigo-barra', [VendaController::class, 'buscar_produto_codigo_barra'])->name('buscar-produto-venda-codigo-barra');
        Route::get('/dashboard/actualizar-venda/{id}/{back?}', [VendaController::class, 'actualizar_vendas'])->name('actualizar-venda');
        Route::put('/dashboard/actualizar-venda/{id}/{back?}', [VendaController::class, 'actualizar_vendas_update'])->name('actualizar-venda-update');
        
        Route::get('/dashboard/actualizar-venda-factura/{id}', [VendaController::class, 'actualizar_vendas_factura'])->name('actualizar-venda-factura');
        Route::put('/dashboard/actualizar-venda-factura/{id}', [VendaController::class, 'actualizar_vendas_factura_update'])->name('actualizar-venda-factura-update');
        
        Route::get('/dashboard/factura-recibos-pos-venda', [VendaController::class, 'factura_recibo_pos_venda'])->name('factura-recibo-pos-venda');

        ########
        // retificar factura
        Route::get('/dashboard/retificar-venda/{id}', [VendaController::class, 'retificar_vendas'])->name('retificar-venda');
        Route::put('/dashboard/retificar-venda/{id}', [VendaController::class, 'retificar_vendas_update'])->name('retificar-venda-update');
        Route::get('/dashboard/retificar-venda-romover-produto/{id}', [VendaController::class, 'retificar_venda_remover_produto'])->name('retificar-venda-remover-produto');
        Route::get('/dashboard/retifcar-venda-adicionar-produto/{id}/{codigo}', [VendaController::class, 'retificar_venda_adicionar_produto'])->name('retificar-venda-adicionar-produto');
        ###########

      
        #####################endregion
        #### pronto Venda
        Route::get('/dashboard/pronto-venda/facturas', [AppFacturasController::class, 'facturaTodas'])->name('pronto-venda.facturas-todas');
        Route::get('/dashboard/pronto-venda/facturas-sem-pagamentos', [AppFacturasController::class, 'facturaSemPagamentos'])->name('pronto-venda.facturas-sem-pagamento');
        Route::get('/dashboard/pronto-venda/facturas-facturacao', [AppFacturasController::class, 'facturaFacturacao'])->name('pronto-venda.facturas-facturacao');
        Route::get('/dashboard/pronto-venda/facturas-informativo', [AppFacturasController::class, 'facturaInformativo'])->name('pronto-venda.facturas-informativo');
        
        Route::get('/dashboard/pronto-venda/facturas-operacaoes', [AppFacturasController::class, 'facturaOperacaoes'])->name('pronto-venda.facturas-operacaoes');
        Route::get('/dashboard/pronto-venda/facturas-trocar-itens', [AppFacturasController::class, 'facturaTrocarItens'])->name('pronto-venda.facturas-trocarItens');
        Route::post('/dashboard/pronto-venda/facturas-trocar-itens-create', [AppFacturasController::class, 'facturaTrocarItensCreate'])->name('pronto-venda.facturas-trocarItens-create');
        Route::get('/dashboard/pronto-venda/facturas-devolucao-itens', [AppFacturasController::class, 'facturaDevolucaoItens'])->name('pronto-venda.facturas-devolucao');
        Route::get('/dashboard/pronto-venda/facturas-anulacao', [AppFacturasController::class, 'facturaAnulacao'])->name('pronto-venda.facturas-anulacao');
        Route::get('/dashboard/pronto-venda/facturas-buscar', [AppFacturasController::class, 'facturaBuscar'])->name('pronto-venda.facturas-buscar');
        Route::get('/dashboard/pronto-venda/facturas-visualizar/{id}', [AppFacturasController::class, 'facturaVisualizar'])->name('pronto-venda.facturas-visualizar');
        Route::get('/dashboard/pronto-venda/facturas-documento/{id}/{visualizar?}', [AppFacturasController::class, 'facturaDocumento'])->name('pronto-venda.facturas-documento');

        ############## PDF
        Route::get('/dashboard/pdf-facturas-sem-pagamentos/{tipo_documento?}/{factura?}', [FacturasSemPagamentoController::class, 'pdfFacturaSemPagamentos'])->name('pdf-facturas-sem-pagamento');
        Route::get('/dashboard/pdf-facturas-facturacao/{factura?}', [FacturasFacturacaoController::class, 'pdfFacturaFacturacao'])->name('pdf-facturas-facturacao');
        Route::get('/dashboard/pdf-facturas-informativo/{factura?}', [FacturasInformativoController::class, 'pdfFacturaInformativo'])->name('pdf-facturas-informativo');
        Route::get('/dashboard/pdf-notas-creditos', [FacturasInformativoController::class, 'pdfNotaCredito'])->name('pdf-notas-creditos');
        Route::get('/dashboard/pdf-recibos', [FacturasInformativoController::class, 'pdfRecibos'])->name('pdf-recibos');
        ################

        Route::get('/dashboard/print-antes-venda', [VendaController::class, 'printAnteVenda'])->name('print-antes-venda');
        //carrinho
        Route::get('/dashboard/adicionar-produto/{id?}/{mesa_caixa?}', [VendaController::class, 'adicionar_produto'])->name('adicionar-produto');
        Route::get('/dashboard/remover-produto/{id}/{back?}', [VendaController::class, 'remover_produto'])->name('remover-produto');
        Route::get('/dashboard/finalizar-venda', [VendaController::class, 'finalizar_vendas'])->name('finalizar-venda');
        Route::get('/dashboard/finalizar-venda-pedido/{id}', [VendaController::class, 'finalizar_vendas_pedido'])->name('finalizar-venda-pedido');
        Route::post('/dashboard/finalizar-venda', [VendaController::class, 'finalizar_vendas_create'])->name('finalizar-venda-create');
        Route::get('/dashboard/cancelar-venda', [VendaController::class, 'cancelar_vendas'])->name('cancelar-venda');

        Route::get('/dashboard-principal', [HomeController::class, 'dashboardPrincipal'])->name('dashboard-principal');
  
        Route::post('/logout', [AppController::class, 'logout'])->name('logout');
        
        Route::resource('/anuncios', AnuncioController::class);
        Route::resource('/anuncios-admin', AnuncioAdminController::class);
        Route::get('/resumo-relatorio', [EstoqueController::class, 'resumoRelatorio'])->name('resumo-relatorio');
        Route::get('/estoques-produtos', [EstoqueController::class, 'estoqueProduto'])->name('estoques-produtos');
        Route::get('/imprimir-resumo-relatorio', [EstoqueController::class, 'imprimirResumoRelatorio'])->name('imprimir-resumo-relatorio');
        Route::get('/imprimir/estoques-produtos', [EstoqueController::class, 'imprimirEstoqueProduto'])->name('imprimir-estoques-produtos');
        Route::resource('/cursos', CursoController::class);
        Route::get('/modulos-cursos/{id}', [CursoController::class, 'modulos'])->name('cursos-modulos');
        
        Route::get('/modulos-cursos', [ModuloCursoController::class, 'index'])->name('modulos-cursos.index');
        Route::post('/modulos-cursos', [ModuloCursoController::class, 'store'])->name('modulos-cursos.store');
        Route::put('/modulos-cursos/{id}', [ModuloCursoController::class, 'update'])->name('modulos-cursos.update');
        Route::delete('/modulos-cursos/{id}', [ModuloCursoController::class, 'destroy'])->name('modulos-cursos.destroy');
        
        Route::resource('/turnos', TurnoController::class);
        Route::resource('/provincias', ProvinciaController::class);
        Route::resource('/municipios', MunicipioController::class);
        Route::resource('/distritos', DistritoController::class);
        Route::resource('/seguradoras', SeguradoraController::class);
        Route::resource('/anos-lectivos', AnoLectivoController::class);
        Route::get('/anos-lectivos/mudar-status/{id}', [AnoLectivoController::class, 'mudar_status'])->name('anos-lectivos.mudar-status');
        Route::resource('/turmas', TurmaController::class);
        Route::get('/turma-adicionar-alunos/{id}', [TurmaController::class, 'turma_adicionar_aluno'])->name('turma-adicionar-aluno');
        Route::post('/turma-adicionar-alunos', [TurmaController::class, 'turma_adicionar_aluno_store'])->name('turma-adicionar-aluno-store');
        Route::get('/turma-adicionar-formadores/{id}', [TurmaController::class, 'turma_adicionar_formador'])->name('turma-adicionar-formador');
        Route::post('/turma-adicionar-formadores', [TurmaController::class, 'turma_adicionar_formador_store'])->name('turma-adicionar-formador-store');
        Route::get('/turma-distribuir-pautas/{id}', [TurmaController::class, 'turma_distribuir_pautas'])->name('turma-distribuir-pautas');
        Route::get('/turma-visualizar-pautas/{id}', [TurmaController::class, 'turma_visualizar_pautas'])->name('turma-visualizar-pautas');
        Route::get('/turma-lancamento-pautas/{id}', [TurmaController::class, 'turma_lancamento_pautas'])->name('turma-lancamento-pautas');
        Route::post('/turma-lancamento-pautas', [TurmaController::class, 'turma_lancamento_pautas_store'])->name('turma-lancamento-pautas-store');
        Route::resource('/lojas', LojaController::class);
        Route::get('/gestao-lojas-armazem', [LojaController::class, 'gestao_lojas_armazem'])->name('gestao-lojas-armazem');
        Route::get('/gestao-lojas-armazem/{id}', [LojaController::class, 'gestao_lojas_armazem_detalhe'])->name('gestao-lojas-armazem-detalhe');
        Route::get('/transferencia-lojas-armazem', [LojaController::class, 'transferencia_lojas_armazem'])->name('transferencia-lojas-armazem');
        Route::get('/transferencia-lojas-armazem_item/{id}', [LojaController::class, 'transferencia_lojas_armazem_item'])->name('transferencia-lojas-armazem-item');
        Route::get('/transferencia-lojas-armazem_remover_item/{id}', [LojaController::class, 'transferencia_lojas_armazem_remover_item'])->name('transferencia-lojas-armazem-remover-item');
        Route::post('/transferencia-lojas-armazem', [LojaController::class, 'transferencia_lojas_armazem_store'])->name('transferencia-lojas-armazem-store');
        Route::resource('/salas', SalaController::class);
        Route::resource('/mesas', MesaController::class);
        Route::get('/mesas-status/{id}', [MesaController::class, 'mudar_status_mesa'])->name('mudar-status-mesa');
        Route::resource('/produtos-compras', ProdutoCompraController::class);
        Route::resource('/registros-compras-produtos', RegistroCompraProdutoController::class);
        Route::get('/pdf-registros-compras-produtos', [RegistroCompraProdutoController::class, 'imprimir'])->name('pdf-registros-compras-produtos');
        
        
        Route::resource('/reservas', ReservaController::class);
        Route::get('/reservas-anulacao/{id}', [ReservaController::class, 'anulacao'])->name('reservas-anulacao');
        Route::get('/reservas-fazer-pagamento/{id}', [ReservaController::class, 'pagamento'])->name('reservas-fazer-pagamento');
        Route::post('/reservas-fazer-pagamento', [ReservaController::class, 'pagamento_store'])->name('reservas-fazer-pagamento-store');
        Route::get('/reservas/{id}/check-in', [ReservaController::class, 'check_in'])->name('reservas.check_in');
        Route::get('/reservas/{id}/check-out', [ReservaController::class, 'check_out'])->name('reservas.check_out');
        Route::get('/painel-de-escolha', [HomeController::class, 'painel_escolha'])->name('painel.escolha');
        Route::get('/reservas-saidas-diarias', [ReservaController::class, 'diario_check'])->name('reservas.check_out_diario');
        Route::get('/reservas-entradas-diarias', [ReservaController::class, 'diario_check_in'])->name('reservas.check_in_diario');
    
        
        Route::resource('/agendamentos', AgendamentoController::class);
        Route::get('/imprimir/agendamentos/{id}', [AgendamentoController::class, 'imprimir'])->name('agendamentos.imprimir');
        Route::get('/pdf-agendamentos', [AgendamentoController::class, 'pdf_agendamentos'])->name('pdf-agendamentos');
        Route::resource('/tipo-pagamentos', TipoPagamentoController::class);
   
     
        
   
        Route::resource('/alunos', AlunoController::class);
		Route::resource('/funcionarios', FuncionarioController::class);
        Route::get('/funcionario/importar-excel', [FuncionarioController::class, 'create_import'])->name('create_import.funcionarios');
        Route::post('/funcionario/importar-excel', [FuncionarioController::class, 'store_import'])->name('store_import.funcionarios');

        
        Route::get('/alunos-matriculas', [AlunoController::class, 'matriculas'])->name('alunos-matriculas');
        Route::delete('/alunos-matriculas/{id}', [AlunoController::class, 'matriculas_excluir'])->name('alunos-matriculas-excluir');
        Route::get('/alunos-matriculas/{id}', [AlunoController::class, 'matriculas_editar'])->name('alunos-matriculas-editar');
        Route::get('/alunos-matriculas-status/{id}', [AlunoController::class, 'matriculas_status'])->name('alunos-matriculas-status');
        Route::resource('/formadores', FormadorController::class);
        Route::resource('/provas', ProvaController::class);
        Route::resource('/conta-clientes', ContaClienteController::class);
        Route::get('/clientes/actualizar-conta/{id}', [ContaClienteController::class, 'actualizarConta'])->name('clientes-actualizar-conta');
        Route::get('/clientes/movimentos-conta/{id}', [ContaClienteController::class, 'movimentosConta'])->name('clientes-movimentos-conta');
        Route::get('/clientes/liquidar-factura/{id}', [ContaClienteController::class, 'liquidarfactura'])->name('clientes-liquidar-factura');
        Route::get('/clientes/extrato-conta/{id}', [ContaClienteController::class, 'extratoConta'])->name('clientes-extrato-conta');
        // Route::get('/videos/index', [VideoController::class, 'index'])->name('videos.indexs');
        Route::get('/videos/home', [VideoController::class, 'home'])->name('videos.home');
        Route::get('/videos/conteudo', [VideoController::class, 'conteudo'])->name('videos.conteudo');
        Route::get('/videos/conteudo-create', [VideoController::class, 'conteudo_create'])->name('videos.conteudo-create');
        Route::post('/videos/conteudo-store', [VideoController::class, 'conteudo_store'])->name('videos.conteudo-store');
        Route::get('/videos/conteudo-eliminar/{id}', [VideoController::class, 'conteudo_eliminar'])->name('videos.conteudo-eliminar');
        Route::get('/videos/videos', [VideoController::class, 'index'])->name('videos.videos');
        Route::get('/videos/videos-create', [VideoController::class, 'create'])->name('videos.videos-create');
        
        // Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
        Route::resource('/videos', VideoController::class);
        
        Route::resource('/fornecedores', FornecedorController::class);
        Route::get('/fornecedores/nova-encomenda/{id}', [FornecedorController::class, 'novaEncomanda'])->name('fornecedores-nova-encomenda');
        Route::get('/fornecedores/items-nova-encomenda/{id}/{fornecedor}', [FornecedorController::class, 'itemsNovaEncomanda'])->name('fornecedores-items-nova-encomenda');
        Route::get('/fornecedores/items-nova-encomenda-actualizar/{id}/{fornecedor}', [FornecedorController::class, 'itemsNovaEncomandaActualizar'])->name('fornecedores-items-nova-encomenda-actualizar');
        Route::get('/fornecedores/items-nova-encomenda-remover/{id}/{fornecedor}', [FornecedorController::class, 'itemsNovaEncomandaRemover'])->name('fornecedores-items-nova-encomenda-remover');
        Route::get('/fornecedores/nova-factura/{id}', [FornecedorController::class, 'novaFactura'])->name('fornecedores-nova-factura');
        Route::get('/fornecedores/movimentos/{id}', [FornecedorController::class, 'movimentos'])->name('fornecedores-movimentos');
 
        Route::resource('/requisacoes', RequisacaoController::class);
        Route::get('/requisacoes/adicionar-produto/{id}', [RequisacaoController::class, 'adicionarProduto'])->name('requisacoes.adicionar-produto');
        Route::get('/requisacoes/remover-produto/{id}', [RequisacaoController::class, 'removerProduto'])->name('requisacoes.remover-produto');
        Route::get('/requisacoes/rejeitar/{id}', [RequisacaoController::class, 'rejeitar'])->name('requisacoes.rejeitar');
        Route::get('/requisacoes/rascunho/{id}', [RequisacaoController::class, 'rascunho'])->name('requisacoes.rascunho');
        Route::get('/requisacoes/aprovada/{id}', [RequisacaoController::class, 'aprovada'])->name('requisacoes.aprovada');
        Route::post('/requisacoes/aprovada', [RequisacaoController::class, 'aprovadaStore'])->name('requisacoes.aprovada.store');
        Route::get('/requisacoes/imprimir/{code}', [RequisacaoController::class, 'imprimir'])->name('requisacoes-imprimir');
        Route::get('/requisacoes/editar-produto/{id}/{encomenda}', [RequisacaoController::class, 'editarProduto'])->name('requisacoes.editar-produto');
        
        Route::get('/requisacoes/imprimir-individual/{id}', [RequisacaoController::class, 'imprimir_individual'])->name('imprimir-requisicao-individual');
        Route::get('/requisacoes-imprimir-colectivas', [RequisacaoController::class, 'imprimir_colectiva'])->name('imprimir-requisicao-colectivas');
        
        Route::resource('/fornecedores-encomendas', EncomendaForncedorController::class);
        Route::get('/fornecedores/items-nova-encomenda-sem-fornecedor/{id}', [EncomendaForncedorController::class, 'itemsNovaEncomandaSFornecedor'])->name('items-nova-encomenda-sem-fornecedora-ctualizar');
        Route::get('/fornecedores/items-nova-encomenda-sem-fornecedor-editar/{id}/{encomenda}', [EncomendaForncedorController::class, 'itemsNovaEncomandaSFornecedorEdit'])->name('items-nova-encomenda-sem-fornecedora-editar');
        Route::get('/fornecedores/items-nova-encomenda-sem-fornecedor-remover/{id}', [EncomendaForncedorController::class, 'itemsNovaEncomandaRemoverSFornecedor'])->name('items-nova-encomenda-remover-sem-fornecedora-ctualizar');
        Route::get('/fornecedores-encomendas/marcar-como-entregue/{id}', [EncomendaForncedorController::class, 'marcarComoEntregue'])->name('encomenda-marcar-como-entregue');
        Route::get('/fornecedores-encomendas/marcar-como-cancelada/{id}', [EncomendaForncedorController::class, 'marcarComoCancelada'])->name('encomenda-marcar-como-cancelada');
        Route::get('/fornecedores-encomendas/receber-produtos/{id}', [EncomendaForncedorController::class, 'receberProduto'])->name('encomenda-receber-produto');
        Route::post('/fornecedores-encomendas/receber-produtos-store', [EncomendaForncedorController::class, 'receberProdutoStore'])->name('encomenda-receber-produto-store');
        Route::get('/fornecedores-encomendas/imprimir/{id}', [EncomendaForncedorController::class, 'imprimir'])->name('imprimir-encomenda');
        Route::get('/fornecedores-encomendas-todas-imprimir', [EncomendaForncedorController::class, 'imprimir_todas'])->name('imprimir-encomenda-todas');
        
        
        Route::resource('/fornecedores-facturas-encomendas', FacturaEncomendaFornecedorController::class);
        Route::get('/fornecedores-encomendas/criar-factura-compra/{id}', [FacturaEncomendaFornecedorController::class, 'criarFacturaCompra'])->name('encomenda-criar-factura-compra');
        Route::get('/fornecedores-encomendas/liquidar-factura-compra/{id}', [FacturaEncomendaFornecedorController::class, 'liquidarFacturaCompra'])->name('encomenda-liquidar-factura-compra');
        Route::post('/fornecedores-encomendas/liquidar-factura-compra-store', [FacturaEncomendaFornecedorController::class, 'liquidarFacturaCompraStore'])->name('encomenda-liquidar-factura-compra-store');
        Route::get('/fornecedores-encomendas/duplicar-factura/{id}', [FacturaEncomendaFornecedorController::class, 'duplicarFacturaCompra'])->name('encomenda-duplicar-factura');
        Route::post('/fornecedores-encomendas/duplicar-factura', [FacturaEncomendaFornecedorController::class, 'duplicarFacturaCompraStore'])->name('encomenda-duplicar-factura-store');
       
        Route::get('/fornecedor/importar-excel', [FornecedorController::class, 'create_import'])->name('create_import.fornecedores');
        Route::post('/fornecedor/importar-excel', [FornecedorController::class, 'store_import'])->name('store_import.fornecedores');

        Route::get('/facturas/factura-proforma/{code}', [FacturasController::class, 'FacturaProforma'])->name('factura-proforma');
        Route::get('/facturas/factura-factura/{code}', [FacturasController::class, 'FacturaFactura'])->name('factura-factura');
        Route::get('/facturas/factura-recibo/{code}', [FacturasController::class, 'FacturaRecibo'])->name('factura-recibo');
        Route::get('/facturas/factura-recibo-recibo/{code}', [FacturasController::class, 'FacturaReciboRecibo'])->name('factura-recibo-recibo');
        Route::get('/facturas/factura-nota-credito/{code}', [FacturasController::class, 'FacturaNotaCredito'])->name('factura-nota-credito');
        Route::get('/nota-de-movimento/{code}', [FacturasController::class, 'MovimentoPDF'])->name('nota-de-movimento');

        
        Route::prefix('prds')->group(function () {
            Route::resource('/produtos', ProdutoController::class);
            Route::resource('/estoques', EstoqueController::class);
            Route::resource('/movimento-estoques', MovimentoEstoqueController::class);
            Route::resource('/categorias', CategoriaController::class);
            Route::resource('/marcas', MarcaController::class);
            Route::resource('/variacoes', VariacaoController::class);
        });
        
        Route::prefix('rgst')->group(function () {
            Route::get('/contabilidade/inventario', [ContabilidadeController::class, 'inventario'])->name('contabilidade-inventario');
            Route::get('/contabilidade/inventario-exportar-pdf', [ContabilidadeController::class, 'inventarioExportarPdf'])->name('contabilidade-inventario-exportar-pdf');
            Route::get('/contabilidade/diarios', [ContabilidadeController::class, 'diarios'])->name('contabilidade-diarios');
            Route::get('/contabilidade/diarios-pdf', [ContabilidadeController::class, 'diariosPDF'])->name('contabilidade-diarios-pdf');
            Route::get('/contabilidade/diarios/{id}', [ContabilidadeController::class, 'diariosDetalhe'])->name('contabilidade-diarios-detalhe');
            Route::get('/contabilidade/facturacao', [ContabilidadeController::class, 'facturacao'])->name('contabilidade-facturacao');
            Route::get('/relatorio/cliente-pdf', [ContabilidadeController::class, 'diariosPDF'])->name('contabilidade-diarios-pdfs');
        });
        
        Route::get('/inventarios/equipamentos-activos', [InventarioController::class, 'equipamentos'])->name('inventarios.equipamentos-activos');
        Route::get('/inventarios/existencias', [InventarioController::class, 'existencias'])->name('inventarios.existencias');
        Route::get('/inventarios/inicial-geral', [InventarioController::class, 'inicial'])->name('inventarios.inicial-geral');
        Route::resource('/inventarios', InventarioController::class);
        
        Route::prefix('flcai')->group(function () {
            Route::get('/dashboard/caixas', [AppCaixaController::class, 'caixas'])->name('caixa.caixas');
            Route::post('/dashboard/caixas', [AppCaixaController::class, 'caixasCreateUpdate'])->name('caixa.caixas-create-update');
            Route::get('/dashboard/caixas/{id}/detalhe', [AppCaixaController::class, 'caixasDetalhe'])->name('caixa.caixas-detalhe');
            Route::get('/dashboard/caixas/{id}/desactivar', [AppCaixaController::class, 'caixaDesactivar'])->name('caixas.desactivar');
    
            Route::get('/dashboard/abertura-caixas', [AppCaixaController::class, 'abertura_caixa'])->name('caixa.abertura_caixa');
            Route::post('/dashboard/abertura-caixas', [AppCaixaController::class, 'abertura_caixa_create'])->name('caixa.abertura_caixa_create');
    
            Route::get('/dashboard/entrada-dinheiro-caixas', [AppCaixaController::class, 'entrada_dinheiro_caixa'])->name('caixa.entrada_dinheiro_caixa');
            Route::post('/dashboard/entrada-dinheiro-caixas', [AppCaixaController::class, 'entrada_dinheiro_caixa_create'])->name('caixa.entrada_dinheiro_caixa_create');
    
            Route::get('/dashboard/saida-dinheiro-caixas', [AppCaixaController::class, 'saida_dinheiro_caixa'])->name('caixa.saida_dinheiro_caixa');
            Route::post('/dashboard/saida-dinheiro-caixas', [AppCaixaController::class, 'saida_dinheiro_caixa_create'])->name('caixa.saida_dinheiro_caixa_create');
            
            Route::get('/dashboard/movimentos-caixas', [AppCaixaController::class, 'movimentos_caixa'])->name('caixa.movimentos_caixa');
            Route::get('/dashboard/movimentos-caixas-imprimir', [AppCaixaController::class, 'movimentos_imprimir'])->name('caixa.movimentos_caixa_imprimir');
    
            Route::get('/dashboard/fechamento-caixas/{id?}', [AppCaixaController::class, 'fechamento_caixa'])->name('caixa.fechamento_caixa');
            Route::post('/dashboard/fechamento-caixas', [AppCaixaController::class, 'fechamento_caixa_create'])->name('caixa.fechamento_caixa_create');
            Route::post('/dashboard/continuar-com-caixas', [AppCaixaController::class, 'continuar_caixa_create'])->name('caixa.continuar_caixa_create');
            
            Route::get('/dashboard/relatorio-fechamento-caixa/{data_inicio}/{data_final}/{caixa_id}', [AppCaixaController::class, 'relatorio_fechamento_caixa'])->name('relatorio-fechamento-caixa');
            
            Route::resource('/caixas', CaixaController::class);
    
        });
        
        
        Route::prefix('financ')->group(function () {
            Route::get('/dashboard-financeiro', [HomeController::class, 'dashboardFinanceiro'])->name('dashboard-financeiro');
            
            Route::resource('/receitas', ReceitaController::class);
            Route::get('/activar/{id}/receitas', [ReceitaController::class, 'activar'])->name('activar-receitas');
            Route::get('/desactivar/{id}/receitas', [ReceitaController::class, 'desactivar'])->name('desactivar-receitas');
            Route::resource('/dispesas', DispesaController::class);
            Route::get('/activar/{id}/dispesas', [DispesaController::class, 'activar'])->name('activar-dispesas');
            Route::get('/desactivar/{id}/dispesas', [DispesaController::class, 'desactivar'])->name('desactivar-dispesas');
            Route::get('/operacaoes-financeiras/imprimir/{id}', [OperacaoFinanceiraController::class, 'imprimir'])->name('operacaoes-financeiras.imprimir');
            Route::get('/operacaoes-financeiras/grafico-anual', [OperacaoFinanceiraController::class, 'graficoAnual'])->name('operacaoes-financeiras.grafico-anual');
            Route::get('/operacaoes-financeiras/grafico-receitas', [OperacaoFinanceiraController::class, 'graficoReceitas'])->name('operacaoes-financeiras.grafico-receitas');
            Route::get('/operacaoes-financeiras/grafico-despesas', [OperacaoFinanceiraController::class, 'graficoDespesas'])->name('operacaoes-financeiras.grafico-despesas');
            Route::get('/operacaoes-financeiras/grafico-saldos', [OperacaoFinanceiraController::class, 'graficoSaldos'])->name('operacaoes-financeiras.grafico-saldos');
            Route::get('/operacaoes-financeiras/exportar', [OperacaoFinanceiraController::class, 'exportar'])->name('operacaoes-financeiras.exportar');
            Route::get('/operacaoes-financeiras/lixeira', [OperacaoFinanceiraController::class, 'lixeira'])->name('operacaoes-financeiras.lixeira');
            Route::get('/operacaoes-financeiras/lixeira-recuperar/{id}', [OperacaoFinanceiraController::class, 'recuperar'])->name('operacaoes-financeiras.lixeira-recuperar');
            Route::resource('/operacaoes-financeiras', OperacaoFinanceiraController::class);
            Route::get('/transacoes-financeiras', [OperacaoFinanceiraController::class, 'transacoes'])->name('transacoes-financeiras');
        });
               
               
        Route::prefix('agts')->group(function () {
            Route::get('/agt/exportar', [AGTController::class, 'exportar'])->name('agt-exportar');
            Route::get('/agt/refazer', [AGTController::class, 'refazer'])->name('agt-refazer');
            Route::resource('/agt', AGTController::class);
        });
        
        
        Route::prefix('facturs')->group(function () {
            Route::resource('/facturas', FacturasController::class);
            Route::get('/pdf-facturas', [FacturasController::class, 'pdf'])->name('pdf-facturas');
            
            ##visualizar Facturas ################
            ############## VIEW 
            Route::get('/dashboard/facturas-sem-pagamentos', [FacturasController::class, 'facturaSemPagamentos'])->name('facturas-sem-pagamento');
            Route::get('/dashboard/facturas-facturacao', [FacturasController::class, 'facturaFacturacao'])->name('facturas-facturacao');
            Route::get('/dashboard/facturas-informativo', [FacturasController::class, 'facturaInformativo'])->name('facturas-informativo');
            Route::get('/dashboard/recibos', [FacturasController::class, 'recibos'])->name('recibos');
            Route::get('/dashboard/notas-creditos', [FacturasController::class, 'NotaCreditos'])->name('notas-creditos');
            
        });
        
        Route::prefix('clients')->group(function () {
            Route::resource('/clientes', ClienteController::class);
            Route::get('/cliente/importar-excel', [ClienteController::class, 'create_import'])->name('create_import.clientes');
            Route::post('/cliente/importar-excel', [ClienteController::class, 'store_import'])->name('store_import.clientes');
            Route::get('/compras-cliente/{id}', [ClienteController::class, 'compras_clientes'])->name('compras.clientes');
            Route::get('/compras-cliente-imprimir', [ClienteController::class, 'compras_pdf'])->name('compras_pdf.clientes');
        });
            
        Route::prefix('enferms')->group(function () {
            Route::resource('/enfermeiros', EnfermeiroController::class);   
        });
        
        Route::prefix('medics')->group(function () {
            Route::resource('/medicos', MedicoController::class);
        });
        
        
        Route::resource('/consultas', ConsultaController::class);
        Route::resource('/exames', ExameController::class);
        Route::get('/consultas/cancelar/{id}', [ConsultaController::class, 'cancelar_consulta'])->name('cancelar_consulta');
        Route::get('/marcar_exame/{id}', [ExameController::class, 'marcar_exame'])->name('marcar_exame');
        
        Route::prefix('triangs')->group(function () {
            Route::resource('/triagens', TriagemController::class);
        });
        
        Route::prefix('contabils')->group(function () {
        
            Route::resource('/equipamentos-activos', EquipamentoActivoController::class);
            Route::get('/activar/{id}/equipamento-activo', [EquipamentoActivoController::class, 'activar'])->name('activar-equipamentos-activos');
            Route::get('/desactivar/{id}/equipamento-activo', [EquipamentoActivoController::class, 'desactivar'])->name('desactivar-equipamentos-activos');
            Route::resource('/exercicios', ExercicioController::class);
            Route::get('/activar/{id}/exercicios', [ExercicioController::class, 'activar'])->name('activar-exercicios');
            Route::get('/desactivar/{id}/exercicios', [ExercicioController::class, 'desactivar'])->name('desactivar-exercicios');
            Route::resource('/plano-geral-contas', PlanoGeralContaController::class);
            Route::resource('/periodos', PeriodoController::class);
            Route::resource('/classes', ClasseController::class);
            Route::resource('/contas', ContaController::class);
            Route::resource('/subcontas', SubcontaController::class);
            
            Route::get('/contabilidade/balancete', [ContabilidadeController::class, 'balancete'])->name('contabilidade-balancete');
            Route::get('/contabilidade/balanco-inicial', [ContabilidadeController::class, 'balanco_inicial'])->name('contabilidade-balanco-inicial');
            Route::get('/contabilidade/balanco-inicial/novo', [ContabilidadeController::class, 'balanco_inicial_create'])->name('contabilidade-balanco-inicial-novo');
            Route::post('/contabilidade/balanco-inicial/novo', [ContabilidadeController::class, 'balanco_inicial_store'])->name('contabilidade-balanco-inicial-novo-store');
        
        });
        
        Route::prefix('reshums')->group(function () {
          
            Route::get('/dashboard-recurso-humanos', [HomeController::class, 'dashboardRecursoHumano'])->name('dashboard-recurso-humanos');
            Route::get('/configuracao-recurso-humanos', [HomeController::class, 'configuracaoRecursoHumano'])->name('configuracao-recurso-humanos');
            
            Route::resource('/configuracao-rh', ConfiguracaoHRController::class);
                
            Route::resource('/categorias-cargos', CategoriaCargoController::class);
            Route::resource('/tipos-rendimentos', TipoRendimentoController::class);
            Route::resource('/periodos-rendimentos', PeriodoRendimentoController::class);
        
            Route::resource('/departamentos', DepartamentoController::class);
            Route::resource('/motivos-saidas', MotivoSaidaController::class);
            Route::resource('/motivos-ausencias', MotivoAusenciaController::class);
            Route::resource('/tipos-contratos', TipoContratoController::class);
            Route::resource('/tipos-processamentos', TipoProcessamentoController::class);
            Route::resource('/pacotes-salarial', PacoteSalarialController::class);
            Route::resource('/cargos', CargoController::class);
            Route::resource('/contratos', ContratoController::class);
            Route::resource('/renovacoes-contratos', RenovacaoContratoController::class);
            Route::resource('/subsidios', SubsidioController::class);
            Route::resource('/descontos', DescontoController::class);
            Route::resource('/marcacoes-faltas', MarcacaoFaltasController::class);
            Route::resource('/marcacoes-ausencias', MarcacaoAusenciaController::class);
            Route::resource('/marcacoes-ferias', MarcacaoFeriaController::class);
            Route::resource('/processamentos', ProcessamentoController::class);
            Route::get('/pagamentos-processamentos', [ProcessamentoController::class, 'pagamentos'])->name('pagamentos-processamentos');
            Route::post('/pagamentos-processamentos', [ProcessamentoController::class, 'pagamentos_store'])->name('pagamentos-processamentos-store');
            Route::get('/anulacao-processamentos', [ProcessamentoController::class, 'anulacao'])->name('anulacao-processamentos');
            Route::post('/anulacao-processamentos', [ProcessamentoController::class, 'anulacao_store'])->name('anulacao-processamentos-store');
            Route::get('/recibo-processamentos/{id}', [ProcessamentoController::class, 'recibo'])->name('recibo-processamentos');
            Route::get('/emissao-recibos', [ProcessamentoController::class, 'emissao_recibo'])->name('emissao-recibo-processamentos');
            
        });
        
        Route::get('/produto/importar-excel', [ProdutoController::class, 'create_import'])->name('create_import.produtos');
        Route::post('/produto/importar-excel', [ProdutoController::class, 'store_import'])->name('store_import.produtos');
        
        Route::delete('/delete/grupos-precos/{id}', [ProdutoController::class, 'grupos_preco_delete'])->name('grupos_preco.delete');
        Route::get('/produto/grupos-precos/{id}', [ProdutoController::class, 'grupos_preco'])->name('grupos_preco.produtos');
        Route::put('/produto/grupos-precos/{id}', [ProdutoController::class, 'grupos_preco_put'])->name('grupos_preco.produtos.put');
        Route::get('/produto/definir-preco/{id}', [ProdutoController::class, 'definir_preco'])->name('definir_preco.produtos');
        Route::get('/produto/definir-preco-venda/{grupo}/{movimento}', [ProdutoController::class, 'definir_preco_venda'])->name('definir_preco_venda.produtos');
        Route::get('/produto/definir-preco-factura/{grupo}/{movimento}', [ProdutoController::class, 'definir_preco_factura'])->name('definir_preco_factura.produtos');
        
        Route::resource('/tipos-creditos', TipoCreditoController::class);
        Route::resource('/contrapartidas', ContrapartidaController::class);
        Route::resource('/taxas-reintegracao-amortizacoes', TabelaTaxaReintegracaoAmortizacaoController::class);
        
        Route::resource('/contas-bancarias', ContaBancariaController::class);
        Route::get('/dashboard/bancos/{id}/desactivar', [ContaBancariaController::class, 'bancoDesactivar'])->name('contas-bancarias.desactivar');
        Route::get('/dashboard/movimentos-bancos', [ContaBancariaController::class, 'movimentos_banco'])->name('contas-bancarias.movimentos_banco');
        Route::get('/dashboard/movimentos-bancos-imprimir', [ContaBancariaController::class, 'movimentos_imprimir'])->name('contas-bancarias.movimentos_bancos_imprimir');
        Route::get('/dashboard/bancos/{id}/detalhe', [ContaBancariaController::class, 'bancoDetalhe'])->name('contas-bancarias.detalhe');
        Route::get('/dashboard/abertura-bancos', [ContaBancariaController::class, 'abertura_bancos'])->name('contas-bancarias.abertura');
        Route::post('/dashboard/abertura-bancos', [ContaBancariaController::class, 'abertura_bancos_create'])->name('contas-bancarias.abertura_create');
        Route::get('/dashboard/fechamento-bancos/{id?}', [ContaBancariaController::class, 'fechamento_bancos'])->name('contas-bancarias.fechamento');
        Route::post('/dashboard/fechamento-bancos', [ContaBancariaController::class, 'fechamento_bancos_create'])->name('contas-bancarias.fechamento_create');
        Route::get('/dashboard/contas-bancarias.relatorio-fechamento/{code}', [ContaBancariaController::class, 'relatorio_fechamento_bancos'])->name('contas-bancarias.relatorio-fechamento');
        
        
        Route::resource('/tipo-quartos', TipoQuartoController::class);
        Route::resource('/andares', AndarController::class);
        
        Route::get('/desassociar-tarefarios/{code}', [TarefarioController::class, 'desassociar'])->name('tarefarios.desassociar_tarefario');
        Route::get('/associar-tarefarios/{code}', [TarefarioController::class, 'associar'])->name('tarefarios.associar_tarefario');
        Route::post('/associar-tarefarios-store', [TarefarioController::class, 'associar_store'])->name('tarefarios.associar_tarefario_store');
        Route::get('/associar-quartos/{code}', [QuartoController::class, 'associar'])->name('quartos.associar_tarefario');
        Route::post('/associar-quartos-store', [QuartoController::class, 'associar_store'])->name('quartos.associar_tarefario_store');
        Route::resource('/tarefarios', TarefarioController::class);
        
        Route::resource('/pins', PinController::class);
        Route::resource('/lotes', LoteController::class);
        Route::resource('/identidade-empresa', IdentidadeEmpresaController::class);
        Route::resource('/dados-empresa', DadosEmpresaController::class);
        Route::resource('/configurar-empressora', ConfiguracaoEmpressoraController::class);
        Route::resource('/personalizar-empressora', PersonalizarEmpressoraController::class);
        Route::resource('/categorias-empresa', ConfigCategoriaController::class);
        Route::resource('/quartos', QuartoController::class);
        
        Route::resource('/tipos-entidade', TipoEntidadeController::class);
        Route::resource('/modulos-entidade', ModuloEntidadeController::class);
        
        Route::resource('/roles', RoleController::class);
        Route::resource('/permissoes', PermissionController::class);
        Route::resource('/utilizadores', UserController::class);
        Route::get('/privacidade', [UserController::class, 'privacidade'])->name('privacidade');
        Route::post('/privacidade-senha', [UserController::class, 'privacidade_store'])->name('privacidade-store');
       
        Route::get('converter/factura/{id}', [FacturasController::class, 'converter_factura'])->name('converter_factura');
        Route::put('converter/factura/{id}', [FacturasController::class, 'converter_factura_put'])->name('converter_factura_put');
        Route::post('factura/emitir-recibo', [FacturasController::class, 'emitir_recibo'])->name('emitir_recibo');
        
        Route::get('/carregar-lotes/{id}', [ProdutoController::class, 'getLotes'])->name('get-lotes');


        // pdf
        Route::get('/pdf-produto', [PDFController::class, 'pdfProduto'])->name('pdf-produto');
        Route::get('/pdf-reservas', [PDFController::class, 'pdfReserva'])->name('pdf-reservas');
        Route::get('/pdf-clientes', [PDFController::class, 'pdfClientes'])->name('pdf-clientes');
        Route::get('/pdf-vendas', [PDFController::class, 'pdfVendas'])->name('pdf-vendas');
        Route::get('/pdf-stock-artigos', [PDFController::class, 'pdfStockArtigo'])->name('pdf-stock-artigos');
        Route::get('/imprimir-pdf-vendas', [PDFController::class, 'imprimirPdfVendas'])->name('imprimir-pdf-vendas');
        Route::get('/pdf-movimentos-estoque', [PDFController::class, 'pdfMovimentoEstoque'])->name('pdf-movimento-estoque');
        Route::get('/pdf-movimentos-estoque-loja/{id}', [PDFController::class, 'pdfMovimentoEstoqueLoja'])->name('pdf-movimento-estoque-loja');
        Route::get('/pdf-movimentos-estoque-produto/{id}', [PDFController::class, 'pdfMovimentoEstoqueProduto'])->name('pdf-movimento-estoque-produto');

        Route::get('/imprimir-factura', [PDFController::class, 'imprimirFactura'])->name('imprimir-factura');
        Route::get('/imprimir-factura-recibo/{id}', [PDFController::class, 'imprimirFacturaRecibo'])->name('imprimir-factura-recibo');
        Route::get('/imprimir-processamentos', [PDFController::class, 'imprimirProcessamentos'])->name('imprimir-processamentos');
        Route::get('/imprimir-recibos-processamentos', [PDFController::class, 'imprimirRecibosProcessamentos'])->name('imprimir-recibos-processamentos');

        // Route::get('/excel-produto', [PDFController::class, 'exportProdutoExcel'])->name('excel-produto');
        // Route::get('/csv-produto', [PDFController::class, 'exportProdutoCsv'])->name('csv-produto');

        //facturação
        Route::get('/facturas/adicionar-produto/{id}', [FacturasController::class, 'factura_adicionar_produto'])->name('factura-adicionar-produto');
 
        Route::get('/relatorios/cliente-pdf', [PDFController::class, 'cliente_pdf'])->name('relatorio-cliente-pdf');
        Route::get('/relatorios/cliente-pdf-imprimir', [PDFController::class, 'cliente_pdf_imprimir'])->name('cliente-pdf-imprimir');
        
        // Route::get('/facturas/imprimir-factura/{id}', [FacturasController::class, 'imprimirFactura'])->name('imprimir-factura');
        Route::get('/facturas/anular-factura/{id}', [FacturasController::class, 'anularFactura'])->name('anular-factura');
        Route::get('/facturas/retificar-factura/{id}', [FacturasController::class, 'retificarFactura'])->name('retificar-factura');
        Route::get('/mais-detalhes-do-tarefarios/{id}', [HelperController::class, 'getDetalheTarefario'])->name('web.mais-detalhe-tarefarios');
        Route::get('/carregar-tarefarios-quarto/{id}', [HelperController::class, 'getTarefariosQuarto'])->name('web.carregar-tarefarios-quarto');
       
        Route::get('/carregar-categorias-cargo/{id}', [HelperController::class, 'getCategoriaCargos'])->name('web.carregar-categorias-cargos');
        Route::get('/carregar-periodos/{id}', [HelperController::class, 'getPeriodos'])->name('web.carregar-periodos');
        
        Route::get('/carregar-cargos/{id}', [HelperController::class, 'getCargos'])->name('web.carregar-cargos');
        Route::get('/carregar-salario-cargo/{id}', [HelperController::class, 'getSalarioCargo'])->name('web.carregar-salario-cargo');
        Route::get('/carregar-contrapartidas/{id}', [HelperController::class, 'getContrapartidas'])->name('web.carregar-contrapartidas');
                
        Route::get('/carregar-municipios/{id}', [HelperController::class, 'getMunicipio'])->name('web.carregar-municipios');
        Route::get('/carregar-distritos/{id}', [HelperController::class, 'getDistritos'])->name('web.carregar-distritos');
        
        
        // GESTÃO DE CARRINHO
        Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
        Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
        Route::delete('/carrinho/remover', [CarrinhoController::class, 'remover'])->name('carrinho.remover');
        Route::post('/carrinho/adicionar-codigo-barra', [CarrinhoController::class, 'codigo_barra'])->name('carrinho.adicionar-codigo-barra');
        Route::post('/carrinho/pagamento', [CarrinhoController::class, 'pagamento'])->name('carrinho.pagamento');
        // MESAS
        Route::post('/carrinho/adicionar-mesa', [CarrinhoController::class, 'adicionar_mesa'])->name('carrinho.adicionar-mesa');
        Route::delete('/carrinho/remover-mesa', [CarrinhoController::class, 'remover_mesa'])->name('carrinho.remover-mesa');
        Route::get('/carrinho/carregar-pedidos-mesa', [CarrinhoController::class, 'carregar_vendas_mesas'])->name('carrinho.carregar-pedidos-mesa');
        Route::get('/carrinho/carregar-pedidos-quarto', [CarrinhoController::class, 'carregar_vendas_quartos'])->name('carrinho.carregar-pedidos-quarto');
        Route::post('/carrinho/adicionar-quarto', [CarrinhoController::class, 'adicionar_quarto'])->name('carrinho.adicionar-quarto');
        Route::delete('/carrinho/remover-quarto', [CarrinhoController::class, 'remover_quarto'])->name('carrinho.remover-quarto');
        
        
        
        Route::get('/dashboard-alunos', [HomeAlunoController::class, 'dashboard'])->name('dashboard-alunos');
        Route::get('/alunos-privacidade', [HomeAlunoController::class, 'privacidade'])->name('alunos-privacidade');
        Route::post('/alunos-privacidade-senha', [HomeAlunoController::class, 'privacidade_store'])->name('alunos-privacidade-store');
        Route::get('/alunos-dados/{id}', [HomeAlunoController::class, 'dados'])->name('alunos-dados');
        Route::put('/alunos-dados-actualizar/{id}', [HomeAlunoController::class, 'dados_update'])->name('alunos-dados-update');
        Route::get('/alunos-videos/conteudo', [HomeAlunoController::class, 'index_conteudo'])->name('alunos-videos.conteudo');
        Route::get('/alunos-videos/videos', [HomeAlunoController::class, 'index_video'])->name('alunos-videos.videos');
        Route::get('/alunos-provas', [HomeAlunoController::class, 'provas'])->name('alunos-provas');
        Route::get('/alunos-provas-detalhes/{id}', [HomeAlunoController::class, 'provas_detalhe'])->name('alunos-provas-detalhes');
        Route::get('/alunos-matriculass', [HomeAlunoController::class, 'matriculas'])->name('alunos-matriculass');
        Route::resource('/alunos-documentos', AlunoDocumentoController::class);
        Route::get('/solicitacoes-documentos', [AlunoDocumentoController::class, 'home'])->name('solicitacoes-documentos');
        Route::get('/activar/{id}/solicitacoes-documentos', [AlunoDocumentoController::class, 'activar'])->name('activar-solicitacoes-documentos');
        Route::get('/desactivar/{id}/solicitacoes-documentos', [AlunoDocumentoController::class, 'desactivar'])->name('desactivar-solicitacoes-documentos');
        
        Route::get('/dashboard-formador', [HomeFormadorController::class, 'dashboard'])->name('dashboard-formadores');
        Route::get('/formadores-privacidade', [HomeFormadorController::class, 'privacidade'])->name('formadores-privacidade');
        Route::post('/formadores-privacidade-senha', [HomeFormadorController::class, 'privacidade_store'])->name('formadores-privacidade-store'); 
        Route::get('/formadores-dados/{id}', [HomeFormadorController::class, 'dados'])->name('formadores-dados');
        Route::put('/formadores-dados-actualizar/{id}', [HomeFormadorController::class, 'dados_update'])->name('formadores-dados-update');
        Route::resource('/formadores-provas', ProvaFormadorController::class);
        
        Route::get('/formadores-turmas', [HomeFormadorController::class, 'turmas'])->name('formadores-turmas');
        Route::get('/formadores-turmas-detalhes/{id}', [HomeFormadorController::class, 'turmas_detalhes'])->name('formadores-turmas-detalhes');
        Route::get('/formadores-turma-visualizar-pautas/{id}', [HomeFormadorController::class, 'turma_visualizar_pautas'])->name('formadores-turma-visualizar-pautas');
        Route::get('/formadores-turma-lancamento-pautas/{id}', [HomeFormadorController::class, 'turma_lancamento_pautas'])->name('formadores-turma-lancamento-pautas');
        Route::post('/formadores-turma-lancamento-pautas', [HomeFormadorController::class, 'turma_lancamento_pautas_store'])->name('formadores-turma-lancamento-pautas-store');
        
        Route::get('/formadores-videos/conteudo', [ProvaFormadorController::class, 'index_conteudo'])->name('formadores-videos.conteudo');
        Route::get('/formadores-videos/create', [ProvaFormadorController::class, 'create_conteudo'])->name('formadores-videos.create-conteudo');
        Route::post('/formadores-videos/create', [ProvaFormadorController::class, 'store_conteudo'])->name('formadores-videos.store-conteudo');
        
        Route::get('/formadores-videos/videos', [ProvaFormadorController::class, 'index_video'])->name('formadores-videos.videos');
        Route::get('/formadores-create-videos', [ProvaFormadorController::class, 'create_video'])->name('formadores-videos.create-video');
        
    });
