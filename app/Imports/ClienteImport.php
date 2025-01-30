<?php

namespace App\Imports;

use App\Models\Cliente;
use App\Models\ContaCliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClienteImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        // Verificar se os campos esperados estão presentes
        if (!isset($row['nif']) || !isset($row['nome'])) {
            // Se os campos não estiverem presentes, lançar uma exceção ou logar o erro
            throw new \Exception('Os campos necessários não estão presentes na linha.');
        }
                
        $cliente = Cliente::create([
            'nif' => $row['nif'],
            'nome' => $row['nome'],
            'pais' => "AO",
            'nome_do_pai' => NULL,
            'nome_da_mae' => NULL,
            'data_nascimento' => NULL,
            'genero' => $row['genero'],
            'estado_civil_id' => NULL,
            'seguradora_id' => NULL,
            'provincia_id' => NULL,
            'municipio_id' => NULL,
            'distrito_id' => NULL,
            'status' => true,
            'vencimento' => NULL,
            'gestor_conta' => NULL,
            'morada' => NULL,
            'codigo_postal' => $row['codigo_postal'],
            'localidade' => $row['localidade'],
            'telefone' => $row['telefone'],
            'telemovel' => $row['telemovel'],
            'email' => $row['email'],
            'website' => NULL,
            'referencia_externa' => NULL,
            'observacao' => NULL,
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id,
        ]);
                
        $saldo = ContaCliente::create([
            'user_id' => Auth::user()->id,
            'divida_corrente' => 0,
            'divida_vencida' => 0,
            'saldo' => 0,
            'cliente_id' => $cliente->id,
            'entidade_id' => $entidade->empresa->id,
        ]); 
        
        return $cliente; 
    }
}
