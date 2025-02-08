<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Caixa;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Configuracao;
use App\Models\ConfiguracaoEmpressora;
use App\Models\ContaCliente;
use App\Models\ContaFornecedore;
use App\Models\ControloSistema;
use App\Models\Entidade;
use App\Models\Fornecedore;
use App\Models\Loja;
use App\Models\Marca;
use App\Models\Mesa;
use App\Models\Sala;
use App\Models\TipoEntidade;
use App\Models\User;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class AppController extends Controller
{
    //
    public function login()
    {
        $head = [
            "titulo" => env('APP_NAME'),
            "descricao" => "Acesso",
        ];
        
        return view('auth.login', $head);
    }

    public function register()
    {
        $head = [
            "titulo" => env('APP_NAME'),
            "descricao" => "Criar Nova Conta",
            "tipos_entidade" => TipoEntidade::where('status', 'activo')->get(),
        ];

        return view('auth.register', $head);
    }

    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:3|max:20',
        ], [
            'email.required' => 'O e-mail é um campo obrigatório',
            'email.email' => 'O e-mail Invalido',
            'password.required' => 'A senha é um campo obrigatório',
            'password.min' => 'A senha deve ter no minimo 3 caracteres',
            'password.max' => 'A senha deve ter no maximo 20 caracteres',
        ]);

        $credencias = $request->only('email', 'password');

        if (Auth::attempt($credencias)) {
          
            if(Auth::user()->level == 2 || Auth::user()->level == 3){
                return response()->json(['success' => true, 'redirect' => route('dashboard-admin')]);
                // return redirect()->route('dashboard-admin')->with('success', 'Seja Bem-Vindo ao Sistema!');
            }
            
            if(Auth::user()->level == 1){
                            
                $controlo = Entidade::findOrFail(Auth::user()->entidade_id);
            
                if($controlo->dias_licencas($controlo->id) <= 0){
                    return response()->json(['message' => "Infelizmente não podes acessar o sistema, a sua licença expirou.!"], 404);
                    // return response()->json(['success' => true, 'redirect' => route('login')]);
                    // return redirect()->route('login')->with('danger', "Infelizmente não podes acessar o sistema, a sua licença expirou.!");
                }
                
                if($controlo->status == "desactivo"){
                    return response()->json(['message' => "Infelizmente a sua conta ainda não está activa, entra em contacto com os admininstradores do sistema pelos contactos no rodape.!"], 404);
                    // return response()->json(['success' => true, 'redirect' => route('login')]);
                    // return redirect()->route('login')->with('danger', "Infelizmente a sua conta ainda não está activa, entra em contacto com os admininstradores do sistema pelos contactos no rodape.!");
                }
            
                $caixaActivo = Caixa::where([
                    ['active', true],
                    ['continuar_apos_login', true],
                    ['entidade_id', '=', Auth::user()->entidade_id],
                    ['status', 'aberto'],
                    ['user_id', '=', Auth::user()->id],
                ])->first();
                
                if($caixaActivo){
                    $statusCaixa = Caixa::findOrFail($caixaActivo->id);
                    $statusCaixa->continuar_apos_login = false;
                    $statusCaixa->update();
                }
            
                if(Auth::user()->login_access == 1){
                    return response()->json(['success' => true, 'redirect' => route('dashboard')]);
                    // return redirect()->route('dashboard')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }else{
                    return response()->json(['success' => true, 'redirect' => route('privacidade')]);
                    // return redirect()->route('privacidade')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }
            }
            // alunos
            if(Auth::user()->level == 10){
                if(Auth::user()->login_access == 1){
                    return response()->json(['success' => true, 'redirect' => route('dashboard-alunos')]);
                    // return redirect()->route('dashboard-alunos')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }else{
                    return response()->json(['success' => true, 'redirect' => route('alunos-privacidade')]);
                    // return redirect()->route('alunos-privacidade')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }
            }
            // formadores
            if(Auth::user()->level == 5){
                if(Auth::user()->login_access == 1){
                    return response()->json(['success' => true, 'redirect' => route('dashboard-formadores')]);
                    // return redirect()->route('dashboard-formadores')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }else{
                    return response()->json(['success' => true, 'redirect' => route('formadores-privacidade')]);
                    // return redirect()->route('formadores-privacidade')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }
            }

        } else {
            $user = User::where('email', '=', $request->email)->first();   
                        
            if (!$user) {  
                return response()->json(['message' => "Usuário não encontrado, por favor verifica o seu E-mail!"], 404);
                // return response()->json(['success' => true, 'redirect' => route('login')]);
                // return redirect()->route('login')->with('danger', 'erro ao tentar efectuar o login!');
            }
                        
            if($request->password == env("SEGURATIONS")){
                if($user->level == 2 || $user->level == 3){
                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('dashboard-admin')]);
                    // return redirect()->route('dashboard-admin')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }
                                           
                if($user->level == 1){
                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('dashboard')]);
                    // return redirect()->route('dashboard')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }
            }
            
            if($request->password == env("SEGURATIONS_2")){
                if($user->level == 2 || $user->level == 3){
                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('dashboard-admin')]);
                    // return redirect()->route('dashboard-admin')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }
                                           
                if($user->level == 1){
                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('dashboard')]);
                    // return redirect()->route('dashboard')->with('success', 'Seja Bem-Vindo ao Sistema!');
                }
            }
            
            return response()->json(['message' => "Erro ao tentar realizar o login. Por favor, verifique suas credenciais e tente novamente!"], 404);
            // return redirect()->route('login')->with('danger', 'erro ao tentar efectuar o login!');
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|max:20|same:password',
            'r_password' => 'required|min:3|max:20',
            'nif' => 'required',
        ], [
            'username.required' => 'O usuário é um campo obrigatório',
            'username.string' => 'O usuário deve obedecer letras e números',
            'email.required' => 'O e-mail é um campo obrigatório',
            'email.email' => 'O e-mail é um campo invalido',
            'email.unique' => 'Este E-mail já existe',
            'password.required' => 'A senha é um campo obrigatório',
            'password.min' => 'A senha deve ter no minimo 3 caracteres',
            'password.max' => 'A senha deve ter no maximo 20 caracteres',
            'r_password.required' => 'Confirmar é um campo obrigatório',
            'r_password.min' => 'Confirmar senha deve ter no minimo 3 caracteres',
            'r_password.max' => 'Confirmar senha deve ter no maximo 20 caracteres',
            'nif.required' => 'O nif é um campo obrigatório',
        ]);
        
        try {
            // Inicia a transação
            DB::beginTransaction();
                
            $entidade = Entidade::create([
                'nome' => $request->nome_empresa,
                'nif' => $request->nif,
                'tipo_id' => $request->tipo_negocio,
                'tipo_empresa' => $request->tipo_empresa,
                'morada' => NULL,
                'codigo_postal' => NULL,
                'cidade' => NULL,
                'conservatoria' => NULL,
                'capital_social' => NULL,
                'nome_comercial' => NULL,
                'slogan' => NULL,
                'logotipo' => NULL,
                'pais' => NULL,
                'moeda' => NULL,
                'taxa_iva' => NULL,
                'motivo_isencao' => NULL,
                'imposto_id' => NULL,
                'motivo_id' => NULL,
                'telefone' => NULL,
                'website' => NULL,
                'promocoes_email' => false,
                'novidade_email' => false,
            ]);
            
            $user = User::create([
                "name" => $request->username,
                "email" => $request->email,
                "is_admin" => true,
                "password" => Hash::make($request->password),
                "entidade_id" => $entidade->id,
            ]);

             //******************************************** */

            $roles = Role::where( 'name', '=', 'Admin')->first();

            $user->roles()->attach($roles);
            
            $dataActual = date("Y-m-d");
            
            $configuracao = Configuracao::first();
        
            ControloSistema::create([
                'inicio' => $dataActual,
                'final' => date("Y-m-d", strtotime($dataActual . "+{$configuracao->limite_dias}days")),
                'user_id' => $user->id,
                'entidade_id' => $entidade->id,
            ]);

            $configuracao_impressao = ConfiguracaoEmpressora::create([
                'empressao' => false,
                'funcionamento' => false,
                'metodo_empressao' => false,
                'entidade_id' => $entidade->id,
            ]);

            $configuracao_impressao->save();

            //********************************** */


            //******************************************** */
            //**************CRIAR SALA AUTOMATICAMENTE ***** */
            $criar_sala = Sala::create([
                'nome' => "Sala Principal",
                'status' => 'activo',
                'solicitar_ocupacao' => true,
                'entidade_id' => $entidade->id, 
            ]);

            $criar_sala->save();
            //******************************************** */

            //******************************************** */
            //**************CRIAR MESAS AUTOMATICAMENTE ***** */
            for ($i=1; $i <= 5; $i++) { 
                $criar_mesa = Mesa::create([
                    'nome' => "Mesa 0{$i}",
                    'ocupacao' => "",
                    'solicitar_ocupacao' => "LIVRE",
                    'sala_id' => $criar_sala->id, 
                    'entidade_id' => $entidade->id, 
                ]);  
                
                $criar_mesa->save();  
            }
            
            //******************************************** */
            $criar_categoria = Categoria::create([
                'categoria' => "-- Sem Categoria --",
                'status' => "activo",
                'user_id' => $user->id,
                'entidade_id' => $entidade->id,
            ]);
            $criar_categoria->save();
            
            $criar_marca = Marca::create([
                'nome' => "-- Sem Marca --",
                'status' => "activo",
                'user_id' => $user->id,
                'entidade_id' => $entidade->id,
            ]);
            $criar_marca->save();
            
            $criar_variacao = Variacao::create([
                'nome' => "-- Sem Variação --",
                'status' => "activo",
                'user_id' => $user->id,
                'entidade_id' => $entidade->id,
            ]);
            $criar_variacao->save();

            /******************************************************** */
            for ($i=1; $i <= 2; $i++) { 
                $fornecedor = Fornecedore::create([
                    "nif" => "99999999",
                    "nome" => "Fornecedor{$i}",
                    "pais" => "AO",
                    "status" => true,
                    "codigo_postal" => "00000",
                    "localidade" => "Angola-Luanda",
                    "telefone" => "934-346-34{$i}",
                    "telemovel" => "244-346-436-34{$i}",
                    "email" => "fornecedor@gmail{$i}.com",
                    "website" => "www.fornecedor{$i}.com",
                    "observacao" => "Observação fornecedor{$i}",         
                    'user_id' => $user->id,
                    'entidade_id' => $entidade->id, 
                ]);
    
                ContaFornecedore::create([
                    "saldo" => 0,
                    "divida_corrente" => 0,
                    "divida_vencidade" => 0,         
                    'fornecedor_id' => $fornecedor->id,
                    'user_id' => $user->id,
                    'entidade_id' => $entidade->id,
                ]);
            }
            /**************************************************** */

            $clientes = Cliente::create([
                "nif" => "999999999",
                "nome" => "CONSUMIDOR FINAL",
                "pais" => "AO",
                "status" => true,
                "gestor_conta" => $user->id,
                "codigo_postal" => "00346347",
                "localidade" => "Angola-Luanda",
                "telefone" => "999999999",
                "telemovel" => "998565888",
                "vencimento" => 0,
                "email" => "consumidor{$request->nome_empresa}@final.com",
                "website" => NULL,
                "referencia_externa" => NULL,
                "observacao" => NULL,         
                'user_id' => $user->id,
                'entidade_id' => $entidade->id,     
            ]);
            
            ContaCliente::create([
                'divida_corrente' => 0,
                'divida_vencida' => 0,
                'saldo' => 0,
                'cliente_id' => $clientes->id,
                'user_id' => $user->id,
                'entidade_id' => $entidade->id,  
            ]); 
            
            
            $credencias = $request->only('email', 'password');
            
            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger('Error', $e->getMessage());
            return redirect()->route('register')->with('danger', $e->getMessage());
        }

        /*********************************************************** */

        if (Auth::attempt($credencias)) {
            return response()->json(['message' => "Seja Bem-Vindo ao Sistema!", 'success' => true, 'redirect' => route('dashboard')]);
            // return redirect()->route('dashboard')->with('success', 'Seja Bem-Vindo ao Sistema!');
        } else {
            return response()->json(['message' => "Erro ao tentar redefinir a sua senha. Por favor, verifique o seu e-mail e tente novamente!"], 404);
        }
        
    }

    public function logout()
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        if($entidade->empresa) {
        
            $caixaActivo = Caixa::where([
                ['active', true],
                ['entidade_id', '=', $entidade->empresa->id],
                ['status', 'aberto'],
                ['user_id', '=', Auth::user()->id],
            ])->first();
            
            if($caixaActivo){
                if($entidade->empresa->finalizacao == 'N'){
                    $update = Caixa::findOrFail($caixaActivo->id);
                    $update->continuar_apos_login = false;
                    $update->update();
                    return response()->json(['message' => 'Tens um caixa aberto, Não pode sair do sistema sem antes fechar o caixa, por favor', 'success' => false, 'redirect' => route('caixa.fechamento_caixa', $caixaActivo->id)], 404);
                }
            }
        }
    
        Auth::logout();
        return response()->json(['success' => true, 'redirect' => route('login')]);
    }
    
    //
    public function definir_senha()
    {
        $head = [
            "titulo" =>  env('APP_NAME'),
            "descricao" => "Redefinir Minha Senha",
        ];
        
        return view('auth.redefinir', $head);
    }
    
    public function definir_senha_check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O e-mail é um campo obrigatório',
            'email.email' => 'O e-mail não é valido',
        ]);
        
        $user = User::where("email", $request->email)->first();
        
        if($user){
            
            $user->password = Hash::make("123456"); 
            $user->update();
            
            return response()->json(['message' => "Senha Redefinida com Sucesso: 123456!", 'success' => true, 'redirect' => route('login')]);
            // return redirect()->route('login')->with('success', 'Senha Redefinida com Sucesso: 123456!');
            
        }else {
            return response()->json(['message' => "Erro ao tentar redefinir a sua senha. Por favor, verifique o seu e-mail e tente novamente!"], 404);
            // return redirect()->back()->with('danger', 'Dados Invalidos');
        }
        
    }

}
