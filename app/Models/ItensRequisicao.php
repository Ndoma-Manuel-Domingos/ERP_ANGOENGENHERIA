<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItensRequisicao extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'itens_requisicoes';

    protected $fillable = [
        'code',
        'produto_id',
        'requisicao_id',
        'loja_id',
        'quantidade',
        'iva',
        'custo',
        'preco_venda',
        'desconto',
        'margem',
        'desconto_valor',
        'imposto_valor',
        'total',
        'totalCiva',
        'totalSiva',
        'valorIva',
        'status',
        'user_id',
        'data_emissao',
        'entidade_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
