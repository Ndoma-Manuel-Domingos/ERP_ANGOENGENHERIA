<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\AnoLectivo;
use App\Models\Caixa;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\ConfiguracaoEmpressora;
use App\Models\Consulta;
use App\Models\ContaCliente;
use App\Models\ContaFornecedore;
use App\Models\ControloSistema;
use App\Models\Curso;
use App\Models\EncomendaFornecedore;
use App\Models\Enfermeiro;
use App\Models\Entidade;
use App\Models\Estoque;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\FacturaOriginal;
use App\Models\FichaConsulta;
use App\Models\FichaTriagem;
use App\Models\Fornecedore;
use App\Models\ItemFacturaOriginal;
use App\Models\ItemNotaCredito;
use App\Models\ItemRecibo;
use App\Models\Itens_venda;
use App\Models\ItensEncomenda;
use App\Models\Loja;
use App\Models\lojaProduto;
use App\Models\Lote;
use App\Models\Marca;
use App\Models\Matricula;
use App\Models\Medico;
use App\Models\Mesa;
use App\Models\Movimento;
use App\Models\MovimentoCaixa;
use App\Models\NotaCredito;
use App\Models\Pauta;
use App\Models\Pin;
use App\Models\Produto;
use App\Models\Recibo;
use App\Models\Registro;
use App\Models\Sala;
use App\Models\Seguradora;
use App\Models\TipoPagamento;
use App\Models\Turma;
use App\Models\TurmaAluno;
use App\Models\TurmaFormador;
use App\Models\Turno;
use App\Models\User;
use App\Models\Variacao;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $user = auth()->user();
    
        if($user->level  == 2){
            $empresas = Entidade::with(['tipo_entidade', 'controle'])
                ->whereIn('level', [2])
                ->where('id', '!=', Auth::user()->entidade_id)
                ->orderBy('nome', 'asc')
                ->get();
        }else if($user->level == 3){
            $empresas = Entidade::with(['tipo_entidade', 'controle'])
                ->whereIn('level', [1, 2, 3])
                ->where('id', '!=', Auth::user()->entidade_id)
                ->orderBy('nome', 'asc')
                ->get();
        }

        $head = [
            "titulo" => "Empresas",
            "descricao" => env('APP_NAME'),
            "empresas" => $empresas,
            "user" => $user,
        ];

        return view('admin.empresas.index', $head);
    }
    
    public function nosso_empresas_pdf()
    {
        
        $user = auth()->user();
    
        if($user->level  == 2){
            $empresas = Entidade::with(['tipo_entidade', 'controle'])
                ->whereIn('level', [2])
                ->where('id', '!=', Auth::user()->entidade_id)
                ->orderBy('nome', 'asc')
                ->get();
        }else if($user->level == 3){
            $empresas = Entidade::with(['tipo_entidade', 'controle'])
                ->whereIn('level', [1, 2, 3])
                ->where('id', '!=', Auth::user()->entidade_id)
                ->orderBy('nome', 'asc')
                ->get();
        }

        $head = [
            "titulo" => "Nossas Empresas | Clientes",
            "descricao" => env('APP_NAME'),
            "empresas" => $empresas,
            "user" => $user,
        ];
        
        $pdf = PDF::loadView('admin.empresas.nossas-empresas', $head);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $user = auth()->user();
        
        $users = User::with([
            'company' => function ($query) use ($user) {
                if ($user->level == 2) {
                    $query->where('level', 2); // Apenas o nível 2
                } elseif ($user->level == 3) {
                    $query->whereIn('level', [1, 2, 3]); // Níveis 1, 2 e 3
                }
            },
            'company.tipo_entidade' // Relacionamento da empresa com tipo_entidade
        ])
        ->where('level', 1) // Filtro principal para usuários
        ->get();
        
        
        $head = [
            "titulo" => "Utilizadores",
            "descricao" => env('APP_NAME'),
            "users" => $users,
            "user" => $user,
        ];

        return view('admin.empresas.home', $head);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function nosso_utilizadores_pdf()
    {
        $user = auth()->user();
        
        $users = User::with([
            'company' => function ($query) use ($user) {
                if ($user->level == 2) {
                    $query->where('level', 2); // Apenas o nível 2
                } elseif ($user->level == 3) {
                    $query->whereIn('level', [1, 2, 3]); // Níveis 1, 2 e 3
                }
            },
            'company.tipo_entidade' // Relacionamento da empresa com tipo_entidade
        ])
        ->where('level', 1) // Filtro principal para usuários
        ->get();
        
        $head = [
            "titulo" => "Utilizadores",
            "descricao" => env('APP_NAME'),
            "users" => $users,
            "user" => $user,
        ];
        
        $pdf = PDF::loadView('admin.empresas.nossos-utilizadores', $head);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();

    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $empresas = Entidade::with(['tipo_entidade', 'controle'])->get();

        $head = [
            "titulo" => "Criar Empresa",
            "descricao" => env('APP_NAME'),
            "empresas" => $empresas,
        ];

        return view('admin.empresas.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $empresa = Entidade::with(['tipo_entidade', 'controle'])->findOrFail($request->empresa_id);
        
        if(!$empresa){
            $controle = ControloSistema::create([
                "inicio" => $request->inicio,
                "final" => $request->final,
                "empresa_id" => $request->empresa_id,
                "user" => Auth::user()->id,
            ]);
        
            return redirect()->route('empresas.index')->with("success", "Dados Actualizados com Sucesso!");
        }
     
        return redirect()->route('empresas.create')->with("warning", "Já existe informações de controle de Licença para essa empresa, actualiza simplesmente os dados!");
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Entidade::with(['tipo_entidade', 'controle'])->findOrFail($id);

        $head = [
            "titulo" => "Configurar Empresa",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
        ];

        return view('admin.empresas.show', $head);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function controlo($id)
    {
        $empresa = Entidade::findOrFail($id);
        $empresa->level = 3;
        $empresa->update();
        
        return redirect()->route('empresas.index')->with("success", "Controlo do Ndoma!");
    
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $empresa = Entidade::findOrFail($id);
        $empresa->status = 'desactivo';
        $empresa->update();
        
        if($empresa->update()){
            return redirect()->route('empresas.index')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('empresas.edit')->with("warning", "Erro ao tentar Actualizar Empresa");
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actvar($id)
    {
        $empresa = Entidade::findOrFail($id);
        $empresa->status = 'activo';
        $empresa->update();
        
        if($empresa->update()){
            return redirect()->route('empresas.index')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('empresas.edit')->with("warning", "Erro ao tentar Actualizar Empresa");
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empresa = Entidade::with(['tipo_entidade', 'controle'])->findOrFail($id);

        $head = [
            "titulo" => "Configurar Empresa",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
        ];

        return view('admin.empresas.edit', $head);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $controle = ControloSistema::findOrFail($id);
        
        $controle->inicio = $request->inicio;
        $controle->final = $request->final;
        
        if($controle->update()){
            return redirect()->route('empresas.index')->with("success", "Dados Actualizados com Sucesso!");
        }else{
            return redirect()->route('empresas.edit')->with("warning", "Erro ao tentar Actualizar Empresa");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empresa = Entidade::findOrFail($id);
        
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
                   
            $movimentos_caixas = MovimentoCaixa::where('entidade_id', $empresa->id)->get();
            if(count($movimentos_caixas) != 0){
                foreach ($movimentos_caixas as $value) {
                    $item = MovimentoCaixa::findOrFail($value->id);
                    $item->delete();
                }
            }
                   
            $movimentos_caixas = Movimento::where('entidade_id', $empresa->id)->get();
            if(count($movimentos_caixas) != 0){
                foreach ($movimentos_caixas as $value) {
                    $item = Movimento::findOrFail($value->id);
                    $item->delete();
                }
            }
                    
            $items_vendas = Itens_venda::where('entidade_id', $empresa->id)->get();
            if(count($items_vendas) != 0){
                foreach ($items_vendas as $value) {
                    $item = Itens_venda::findOrFail($value->id);
                    $item->delete();
                }
            }
             
            $vendas = Venda::where('entidade_id', $empresa->id)->get();
            if(count($vendas) != 0){
                foreach ($vendas as $value) {
                    $item = Venda::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            $recibos_item = ItemRecibo::where('entidade_id', $empresa->id)->get();
            if(count($recibos_item) != 0){
                foreach ($recibos_item as $value) {
                    $item = ItemRecibo::findOrFail($value->id);
                    $item->delete();
                }
            }
               
                 
            $recibos = Recibo::where('entidade_id', $empresa->id)->get();
            if(count($recibos) != 0){
                foreach ($recibos as $value) {
                    $item = Recibo::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            
            $notas_creditos_item = ItemNotaCredito::where('entidade_id', $empresa->id)->get();
            if(count($notas_creditos_item) != 0){
                foreach ($notas_creditos_item as $value) {
                    $item = ItemNotaCredito::findOrFail($value->id);
                    $item->delete();
                }
            }
                    
            $notas_creditos = NotaCredito::where('entidade_id', $empresa->id)->get();
            if(count($notas_creditos) != 0){
                foreach ($notas_creditos as $value) {
                    $item = NotaCredito::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            $item_factura_original = ItemFacturaOriginal::where('entidade_id', $empresa->id)->get();
            if(count($item_factura_original) != 0){
                foreach ($item_factura_original as $value) {
                    $item = ItemFacturaOriginal::findOrFail($value->id);
                    $item->delete();
                }
            }
                    
            $factura_original = FacturaOriginal::where('entidade_id', $empresa->id)->get();
            if(count($factura_original) != 0){
                foreach ($factura_original as $value) {
                    $item = FacturaOriginal::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            
            $configuracao = ConfiguracaoEmpressora::where('entidade_id', $empresa->id)->get();
            if(count($configuracao) != 0){
                foreach ($configuracao as $value) {
                    $item = ConfiguracaoEmpressora::findOrFail($value->id);
                    $item->delete();
                }
            }
            

            $registros = Registro::where('entidade_id', $empresa->id)->get();
            if(count($registros) != 0){
                foreach ($registros as $value) {
                    $item = Registro::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            $stocks = Estoque::where('entidade_id', $empresa->id)->get();
            if(count($stocks) != 0){
                foreach ($stocks as $value) {
                    $item = Estoque::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            
            $contas_fornecedores = ContaFornecedore::where('entidade_id', $empresa->id)->get();
            if(count($contas_fornecedores) != 0){
                foreach ($contas_fornecedores as $value) {
                    $item = ContaFornecedore::findOrFail($value->id);
                    $item->delete();
                }
            }
                
                
            // APAGAR FORNECEDORES
            $fornecedores = Fornecedore::where('entidade_id', $empresa->id)->get();
            if(count($fornecedores) != 0){
                foreach ($fornecedores as $value) {
                    $item = Fornecedore::findOrFail($value->id);
                    $item->delete();
                }
            }
    

            $encomendas_item = ItensEncomenda::where('entidade_id', $empresa->id)->get();
            if(count($encomendas_item) != 0){
                foreach ($encomendas_item as $value) {
                    $item = ItensEncomenda::findOrFail($value->id);
                    $item->delete();
                }
            }
    
            $facturas_encomendas = FacturaEncomendaFornecedor::where('entidade_id', $empresa->id)->get();
            if(count($facturas_encomendas) != 0){
                foreach ($facturas_encomendas as $value) {
                    $item = FacturaEncomendaFornecedor::findOrFail($value->id);
                    $item->delete();
                }
            }
                    
            $encomendas = EncomendaFornecedore::where('entidade_id', $empresa->id)->get();
            if(count($encomendas) != 0){
                foreach ($encomendas as $value) {
                    $item = EncomendaFornecedore::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            
            $contas_clientes = ContaCliente::where('entidade_id', $empresa->id)->get();
            if(count($contas_clientes) != 0){
                foreach ($contas_clientes as $value) {
                    $item = ContaCliente::findOrFail($value->id);
                    $item->delete();
                }
            }
    
            // APAGAR CLIENTES
            $clientes = Cliente::where('entidade_id', $empresa->id)->get();
            if(count($clientes) != 0){
                foreach ($clientes as $value) {
                    $item = Cliente::findOrFail($value->id);
                    $item->delete();
                }
            }
            

            
            $lotes = Lote::where('entidade_id', $empresa->id)->get();
            if(count($lotes) != 0){
                foreach ($lotes as $value) {
                    $item = Lote::findOrFail($value->id);
                    $item->delete();
                }
            }
       
            $produtos_lojas = lojaProduto::where('entidade_id', $empresa->id)->get();
            if(count($produtos_lojas) != 0){
                foreach ($produtos_lojas as $value) {
                    $item = lojaProduto::findOrFail($value->id);
                    $item->delete();
                }
            }
       
            $produtos = Produto::where('entidade_id', $empresa->id)->get();
            if(count($produtos) != 0){
                foreach ($produtos as $value) {
                    $item = Produto::findOrFail($value->id);
                    $item->delete();
                }
            }

              
            // APAGAR MESAS
            $mesas = Mesa::where('entidade_id', $empresa->id)->get();
            if(count($mesas) != 0){
                foreach ($mesas as $value) {
                    $item = Mesa::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CATEGORIAS
            $categorias = Categoria::where('entidade_id', $empresa->id)->get();
            if(count($categorias) != 0){
                foreach ($categorias as $value) {
                    $item = Categoria::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CAIXA
            $marcas = Marca::where('entidade_id', $empresa->id)->get();
            if(count($marcas) != 0){
                foreach ($marcas as $value) {
                    $item = Marca::findOrFail($value->id);
                    $item->delete();
                }
            }
      
            // APAGAR CAIXA
            $caixas = Caixa::where('entidade_id', $empresa->id)->get();
            if(count($caixas) != 0){
                foreach ($caixas as $value) {
                    $item = Caixa::findOrFail($value->id);
                    $item->delete();
                }
            }
      
            // APAGAR CLIENTES
            $turma_alunos = TurmaAluno::where('entidade_id', $empresa->id)->get();
            if(count($turma_alunos) != 0){
                foreach ($turma_alunos as $value) {
                    $item = TurmaAluno::findOrFail($value->id);
                    $item->delete();
                }
            }
                               
      
            // APAGAR CLIENTES
            $turma_formador = TurmaFormador::where('entidade_id', $empresa->id)->get();
            if(count($turma_formador) != 0){
                foreach ($turma_formador as $value) {
                    $item = TurmaFormador::findOrFail($value->id);
                    $item->delete();
                }
            }
                               
    
            // APAGAR CLIENTES
            $matriculas = Matricula::where('entidade_id', $empresa->id)->get();
            if(count($matriculas) != 0){
                foreach ($matriculas as $value) {
                    $item = Matricula::findOrFail($value->id);
                    $item->delete();
                }
            }
            

            // APAGAR CLIENTES
            $turmas = Turma::where('entidade_id', $empresa->id)->get();
            if(count($turmas) != 0){
                foreach ($turmas as $value) {
                    $item = Turma::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CLIENTES
            $pautas = Pauta::where('entidade_id', $empresa->id)->get();
            if(count($pautas) != 0){
                foreach ($pautas as $value) {
                    $item = Pauta::findOrFail($value->id);
                    $item->delete();
                }
            }
            // APAGAR CLIENTES
            $pins = Pin::where('entidade_id', $empresa->id)->get();
            if(count($pins) != 0){
                foreach ($pins as $value) {
                    $item = Pin::findOrFail($value->id);
                    $item->delete();
                }
            }

            // APAGAR CLIENTES
            $salas = Sala::where('entidade_id', $empresa->id)->get();
            if(count($salas) != 0){
                foreach ($salas as $value) {
                    $item = Sala::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CLIENTES
            $seguradora = Seguradora::where('entidade_id', $empresa->id)->get();
            if(count($seguradora) != 0){
                foreach ($seguradora as $value) {
                    $item = Seguradora::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CLIENTES
            $ano_lectivos = AnoLectivo::where('entidade_id', $empresa->id)->get();
            if(count($ano_lectivos) != 0){
                foreach ($ano_lectivos as $value) {
                    $item = AnoLectivo::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CLIENTES
            $cursos = Curso::where('entidade_id', $empresa->id)->get();
            if(count($cursos) != 0){
                foreach ($cursos as $value) {
                    $item = Curso::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CLIENTES
            $turnos = Turno::where('entidade_id', $empresa->id)->get();
            if(count($turnos) != 0){
                foreach ($turnos as $value) {
                    $item = Turno::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            
            // APAGAR CLIENTES
            $fichas_consultas = FichaConsulta::where('entidade_id', $empresa->id)->get();
            if(count($fichas_consultas) != 0){
                foreach ($fichas_consultas as $value) {
                    $item = FichaConsulta::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CLIENTES
            $fichas_triagens = FichaTriagem::where('entidade_id', $empresa->id)->get();
            if(count($fichas_triagens) != 0){
                foreach ($fichas_triagens as $value) {
                    $item = FichaTriagem::findOrFail($value->id);
                    $item->delete();
                }
            }

            // APAGAR CLIENTES
            $consultas = Consulta::where('entidade_id', $empresa->id)->get();
            if(count($consultas) != 0){
                foreach ($consultas as $value) {
                    $item = Consulta::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            // APAGAR CLIENTES
            $enfermeiros = Enfermeiro::where('entidade_id', $empresa->id)->get();
            if(count($enfermeiros) != 0){
                foreach ($enfermeiros as $value) {
                    $item = Enfermeiro::findOrFail($value->id);
                    $item->delete();
                }
            }
                        
            
            // APAGAR CLIENTES
            $medicos = Medico::where('entidade_id', $empresa->id)->get();
            if(count($medicos) != 0){
                foreach ($medicos as $value) {
                    $item = Medico::findOrFail($value->id);
                    $item->delete();
                }
            }
                        
            // APAGAR CAIXA
            $lojas = Loja::where('entidade_id', $empresa->id)->get();
            if(count($lojas) != 0){
                foreach ($lojas as $value) {
                    $item = Loja::findOrFail($value->id);
                    $item->delete();
                }
            }
        
            
            // APAGAR USUARIOS
            $alunos = Aluno::where('entidade_id', $empresa->id)->get();
            if(count($alunos) != 0){
                foreach ($alunos as $value) {
                    $item = Aluno::findOrFail($value->id);
                    $item->delete();
                }
            }
            
            
            // APAGAR USUARIOS
            $variacaoes = Variacao::where('entidade_id', $empresa->id)->get();
            if(count($variacaoes) != 0){
                foreach ($variacaoes as $value) {
                    $item = Variacao::findOrFail($value->id);
                    $item->delete();
                }
            }
    
            $controles = ControloSistema::where('entidade_id', $empresa->id)->get();
            if(count($controles) != 0){
                foreach ($controles as $value) {
                    $item = ControloSistema::findOrFail($value->id);
                    $item->delete();
                }
            }
            
                
            // APAGAR USUARIOS
            $usuarios = User::where('entidade_id', $empresa->id)->get();
           
            if(count($usuarios) != 0){
                foreach ($usuarios as $value) {
                    $item = User::findOrFail($value->id);
                    $item->delete();
                }
            }
        

            $empresa->delete();
            
        
        

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        

        return redirect()->route('empresas.index')->with("success", "Empresa Eliminada com sucesso!");
 
    }
}
