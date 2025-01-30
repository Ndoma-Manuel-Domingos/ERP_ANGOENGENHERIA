<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoGrupoPreco extends Model
{
    use HasFactory, SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'produtos_grupo_precos';

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produto_id',
        'imposto_id',
        'motivo_id',
        'imposto',
        'taxa',
        'motivo_isencao',
        'preco_custo',
        'margem',
        'preco_venda',
        'preco',
        'status',
        'user_id',
        'entidade_id',
    ];
    
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }
    
    public function taxa_imposto()
    {
        return $this->belongsTo(Imposto::class, 'imposto_id', 'id');
    }

    public function motivo()
    {
        return $this->belongsTo(Motivo::class, 'motivo_id', 'id');
    }
    
    public function quantidade()
    {
        return $this->belongsTo(Registro::class, 'id', 'produto_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'produto_id', 'id');
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }

    public function item()
    {
        return $this->hasOne(Itens_venda::class);
    }

    public function lojas()
    {
        return $this->hasMany(lojaProduto::class);
    }

    // exibir imposto
    public function exibir_imposto($string)
    {
        if ($string == ""){
            return "Auto";
        }else if($string == "ISE"){
            return "0%";
        }else if ($string == "RED"){
            return "2%";
        }else if ($string == "INT"){
            return "5%";
        }else if ($string == "OUT"){
            return "7%";
        }else if ($string == "NOR"){
            return "14%";
        }
    }

    public function alert($item)
    {
        if ($item > 50)
        {
            return "<td class='text-danger'>Alerta</td>Excesso</td>";
        }
        if ($item <= 10)
        {
            return "<td class='text-warning'>Alerta</td>";
        }
        if ($item > 10 AND $item <= 50)
        {
            return "<td class='text-success'>Normal</td>";
        }
    }

    public function total_produto($id)
    {
        $totalStock = Estoque::where([
            ['produto_id', $id],
        ])->sum('stock');

        return $totalStock;
    }

}
