<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Funcionario;
use App\Models\MarcacaoFalta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MarcacaoFaltasController extends Controller
{
    //
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();

        // if(!$user->can('listar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $faltas = MarcacaoFalta::where([
            ['entidade_id', '=', $entidade->empresa->id], 
        ])->orderBy('created_at', 'desc')->get();
        
                    
        $contrato_id = Contrato::where('entidade_id', $entidade->empresa->id)->where('status', 'activo')->pluck('funcionario_id');

        $funcionarios = Funcionario::with(['faltas' => function ($query) use ($request) {
            if ($request->data_inicio && $request->data_final) {
                $query->whereBetween('data_registro', [$request->data_inicio, $request->data_final]);
            }
        }])
        ->when($request->funcionario_id, function($query, $value){
            $query->where('id', $value);
        })
        ->where('entidade_id', $entidade->empresa->id)
        ->whereIn('id', $contrato_id)
        ->orderBy('created_at', 'desc')
        ->get();

        $head = [
            "titulo" => "Marcações de Faltas",
            "descricao" => env('APP_NAME'),
            "faltas" => $faltas,
            "funcionarios" => $funcionarios,
            "requests" => $request->all('data_inicio', 'data_final', 'funcionario_id'),
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.marcacoes-faltas.index', $head);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = auth()->user();
        
        // if(!$user->can('criar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }
        
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $contrato_id = Contrato::where('entidade_id', $entidade->empresa->id)->where('status', 'activo')->pluck('funcionario_id');
 
        $funcionarios = Funcionario::with(['estado_civil', 'seguradora', 'provincia', 'municipio', 'distrito', 'faltas'])
            ->where('entidade_id', '=', $entidade->empresa->id )
            ->whereIn('id', $contrato_id)
            ->get();
            
        $head = [
            "titulo" => "Criar Marcações de Faltas",
            "descricao" => env('APP_NAME'),
            "funcionarios" => $funcionarios,
            "tipo_entidade_logado" => User::with(['empresa.tipo_entidade.modulos'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.marcacoes-faltas.create', $head);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // if(!$user->can('criar subsidio')){
        //     Alert::success("Sucesso!", "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        //     return redirect()->back()->with('danger', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        // }

        $request->validate([
            'funcionario_id' => 'required|string',
            'duracao' => 'required|string',
            'falta_id' => 'required|string',
        ],[
            'funcionario_id.required' => 'O funcionário é um campo obrigatório',
            'duracao.required' => 'A duração é um campo obrigatório',
            'falta_id.required' => 'A falta é um campo obrigatório',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            
            $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
            
            $funcionario = Funcionario::findOrFail($request->funcionario_id);
            
            $contrato = Contrato::where('funcionario_id', $funcionario->id)->where('status', 'activo')->first();
            
            if(!$contrato){
                return response()->json(['error' => 'Existe uma irregularidade neste Funcionário.'], 404);
            }
            
            $datas = $request->input('datas');
            
            foreach ($datas as $data) {
                
                $verificar = MarcacaoFalta::where('funcionario_id', $funcionario->id)->where('data_registro', $data)->where('entidade_id', $entidade->empresa->id)->first();
                
                if(!$verificar){
                    $marcacao = MarcacaoFalta::create([
                        'data_registro' => $data,
                        'funcionario_id' => $funcionario->id,
                        'falta_id' => $request->falta_id,
                        'duracao' => $request->duracao,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
                
            }
            
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->back()->with("success", "Dados Cadastrar com Sucesso!");
      
    }
   
}
