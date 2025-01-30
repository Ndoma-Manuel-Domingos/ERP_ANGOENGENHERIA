<?php

namespace App\Imports;

use App\Models\Categoria;
use App\Models\Estoque;
use App\Models\Loja;
use App\Models\lojaProduto;
use App\Models\Marca;
use App\Models\Produto;
use App\Models\User;
use App\Models\Variacao;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class ProdutoImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $entidade = User::with('empresa')->findOrFail(Auth::user()->id);    
        
        $categoria = Categoria::where('entidade_id', $entidade->id)->where('categoria', "-- Sem Categoria --")->first();
        
        $marca = Marca::where('entidade_id', $entidade->id)->where('nome', "-- Sem Marca --")->first();
        
        $variacao = Variacao::where('entidade_id', $entidade->id)->where('nome', "-- Sem Variação --")->first();
        
        $produto = Produto::create([
            'nome' => $row['nome'] ?? NULL,
            'status' => 'activo',
            'referencia' => $row['referencia'] ?? NULL,
            'codigo_barra' => $row['codigo_barra'] ?? NULL,
            'descricao' => $row['descricao'] ?? NULL,
            'incluir_factura' => "Não",
            'imagem' => NULL,
            'variacao_id' => $variacao->id,
            'categoria_id' => $categoria->id,
            'marca_id' => $marca->id,
            'motivo_id' => 2,
            'imposto_id' => 5,
            'tipo' => $row['tipo'] ?? 'P',
            'unidade' => 'uni',
            'imposto' => "NOR",
            'taxa' => 14,
            'motivo_isencao' => 'M00',
            'preco_custo' => $row['preco_custo'] ?? NULL,
            'margem' => $row['margem'] ?? NULL,
            'preco_venda' => $row['preco_venda'] ?? NULL,
            'preco' => $row['preco'] ?? NULL,
            'controlo_stock' => 'Não',
            'tipo_stock' => 'M',
            'disponibilidade' => 1,
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id,
        ]); 
        
        $lojas = Loja::where([
            ['entidade_id', '=', $entidade->empresa->id],
        ])->get();

        foreach ($lojas as $loja) {
            $estoque = Estoque::create([
                "loja_id" => $loja->id,
                "produto_id" => $produto->id,
                "user_id" => Auth::user()->id,
                "data_operacao" => date('Y-m-d'),
                "stock" => 0,
                "stock_minimo" => 0,
                "operacao" => "Actualizar de Stock",
                'entidade_id' => $entidade->empresa->id,
            ]);   
            $estoque->save();
        }

        foreach ($lojas as $loja) {
            $saveProdutoLoja = lojaProduto::create([
                'produto_id' => $produto->id,
                'loja_id' => $loja->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            $saveProdutoLoja->save();                    
        }
        
        return $produto; 
    }
}
