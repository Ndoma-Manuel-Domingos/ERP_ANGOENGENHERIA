<?php

namespace App\Http\Controllers;

use App\Models\ControloSistema;
use App\Models\Entidade;
use App\Models\HashLicenca;
use App\Models\Pin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SegurancaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function licenca()
    {
        $head = [
            "titulo" => "Activação de Licença",
            "descricao" => env('APP_NAME'),
        ];

        return view('dashboard.pins.licenca', $head);
    }

    public function licenca_post(Request $request)
    {

        $hash = HashLicenca::where('hash', $request->codigo)->first();

        if(!$hash){

            $dados = (array) Crypt::decrypt($request->codigo);

            $controle = ControloSistema::where('entidade_id', Auth::user()->entidade_id)->first();

            if ($controle) {
                $controle_update = ControloSistema::findOrFail($controle->id);
        
                $controle_update->inicio = $dados['data_inicio'];
                $controle_update->final = $dados['data_final'];
                
                $controle_update->update();
                
                HashLicenca::create([
                    'hash' => $request->codigo  
                ]);
                
                return redirect()->route('dashboard');
            }

            return redirect()->back()->with('danger', 'Ocorreu um erro ao actualizar a sua licença entra em contacto com o administrador do sistema!');

        }else{
            return redirect()->back()->with('danger', 'Codigo Inválido, entra em contacto com o administrador do sistema!');
        }

        

        // $request->validate([
        //     'codigo' => 'required'
        // ], [
        //     'codigo.required' => 'Informe o codigo por favor'
        // ]);

        // if (Auth::user()->codigo == $request->codigo) {

        //     $user = auth()->user();
        
        //     $usuario = User::findOrFail($user->id);
        //     $usuario->codigo = NULL;
        //     $usuario->update();

        //     return redirect()->route('dashboard');
        // }

        // return redirect()->back();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function pin()
    {
        $head = [
            "titulo" => "Congelador de tela",
            "descricao" => env('APP_NAME'),
        ];

        return view('dashboard.pins.congelamento', $head);
    }


    public function pin_post(Request $request)
    {
        $request->validate([
            'codigo' => 'required'
        ], [
            'codigo.required' => 'Informe o codigo por favor'
        ]);

        if (Auth::user()->codigo == $request->codigo) {

            $user = auth()->user();
        
            $usuario = User::findOrFail($user->id);
            $usuario->codigo = NULL;
            $usuario->update();

            return redirect()->route('dashboard');
        }

        return redirect()->back();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create()
    {
        $head = [
            "titulo" => "Congelador de tela",
            "descricao" => env('APP_NAME'),
        ];

        return view('dashboard.pins.create-congelamento', $head);
    }


    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|numeric'
        ], [
            'codigo.required' => 'Informe o codigo por favor',
            'codigo.numeric' => 'O Código deve ser Numerico',
        ]);

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);

        $pins = Pin::where('status', 'activo')->where('entidade_id', $entidade->empresa->id)->first();

        if ($pins) {
            $pim = Pin::findOrFail($pins->id);
            $pim->status = 'activo';
            $pim->update();
    
            return redirect()->route('dashboard');
        }
        
        $user = auth()->user();
        
        
        $usuario = User::findOrFail($user->id);
        $usuario->codigo = $request->codigo;
        $usuario->update();

        return redirect()->route('dashboard');
    }
}
