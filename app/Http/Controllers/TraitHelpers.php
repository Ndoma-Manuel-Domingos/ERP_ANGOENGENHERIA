<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\OperacaoFinanceiro;
use App\Models\Movimento;
use App\Models\Exercicio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Trait TraitHelpers{

    // ANO LECTIVO ACTIVO 
    public function exercicio()
    {
        $user = User::findOrFail(Auth::user()->id);
        $entidade = Entidade::findOrFail($user->entidade_id);

        $exercicio = Exercicio::where([
            ['entidade_id', '=', $entidade->id],
            ['status', '=', 'activo'],
        ])->first();

        if(!$exercicio){
            return redirect()->route('dashboard-recurso-humanos')->with("warning", "Precisas activar um exercício para poder operar com o sistema!");
        }
        return $exercicio->id;

    }

    function valor_por_extenso( $v ){
		
        $v = filter_var($v, FILTER_SANITIZE_NUMBER_INT);
       
        $sin = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plu = array("centavos", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

        $z = 0;
    
        $v = number_format( $v, 2, ".", "." );
        $int = explode( ".", $v );
    
        for ( $i = 0; $i < count( $int ); $i++ ) 
        {
            for ( $ii = mb_strlen( $int[$i] ); $ii < 3; $ii++ ) 
            {
                $int[$i] = "0" . $int[$i];
            }
        }
    
        $rt = null;
        $fim = count( $int ) - ($int[count( $int ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $int ); $i++ )
        {
            $v = $int[$i];
            $rc = (($v > 100) && ($v < 200)) ? "cento" : $c[$v[0]];
            $rd = ($v[1] < 2) ? "" : $d[$v[1]];
            $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";
    
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $int ) - 1 - $i;
            $r .= $r ? " " . ($v > 1 ? $plu[$t] : $sin[$t]) : "";
            if ( $v == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;
                
            if ( ($t == 1) && ($z > 0) && ($int[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plu[$t];
                
            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
     
        $rt = mb_substr( $rt, 1 );
    
        return($rt ? trim( $rt ) : "zero");
     
    }
    
    
    public function registra_movimentos($subconta_caixa, $code, $observacao, $data_emissao, $empresa, $movimento, $credito = 0, $debito = 0, $exercicio_id = 1, $periodo_id = 12 )
    {
      
        $movimeto = Movimento::create([
            'user_id' => Auth::user()->id,
            'subconta_id' => $subconta_caixa,
            'status' => true,
            'movimento' => $movimento,
            'credito' => $credito,
            'debito' => $debito,
            'observacao' => $observacao,
            'code' => $code,
            'data_at' => $data_emissao,
            'entidade_id' => $empresa,
            'exercicio_id' => $exercicio_id,
            'periodo_id' => $periodo_id,
        ]);
        
        return $movimeto;
        
    }
    
    public function registra_operacoes($valor, $subconta_caixa,  $cliente, $type, $status, $code, $movimento, $data_emissao, $empresa, $observacao, $exercicio_id = 1, $periodo_id = 12)
    {
        $operacoes = OperacaoFinanceiro::create([
            'nome' => $observacao,
            'status' => $status,
            'motante' => $valor,
            'subconta_id' => $subconta_caixa,
            'cliente_id' => $cliente,
            'model_id' => 1,
            'type' => $type,
            'status_pagamento' => $status,
            'code' => $code,
            'descricao' => $observacao,
            'movimento' => $movimento,
            'date_at' => $data_emissao,
            'user_id' => Auth::user()->id,
            'entidade_id' => $empresa,
            'exercicio_id' => $exercicio_id,
            'periodo_id' => $periodo_id,
        ]);
        
        return $operacoes;
    }
}