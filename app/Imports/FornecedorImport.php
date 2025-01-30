<?php

namespace App\Imports;

use App\Models\ContaFornecedore;
use App\Models\Fornecedore;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class FornecedorImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);
        
        $fornecedor = Fornecedore::create([
            "nif" => $row['nif'],
            "nome" => $row['nome'],
            "pais" => $row['pais'] ?? NULL,
            "status" => true,
            "codigo_postal" => $row['codigo_postal'] ?? NULL,
            "localidade" => $row['localidade'] ?? NULL,
            "telefone" => $row['telefone'] ?? NULL,
            "telemovel" => $row['telemovel'] ?? NULL,
            "email" => $row['email'] ?? NULL,
            "website" => $row['website'] ?? NULL,
            "observacao" => $row['observacao'] ?? NULL,         
            "user_id" => Auth::user()->id,          
            'entidade_id' => $entidade->empresa->id,  
        ]);
        
        $saldo = ContaFornecedore::create([
            'user_id' => Auth::user()->id,
            'divida_corrente' => 0,
            'divida_vencida' => 0,
            'saldo' => 0,
            'fornecedor_id' => $fornecedor->id,
            'entidade_id' => $entidade->empresa->id,  
        ]);
            
        return $fornecedor;
    }
}
