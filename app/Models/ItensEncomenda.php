<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItensEncomenda extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'fornecedor_id',
        'produto_id',
        'loja_id',
        'quantidade',
        'quantidade_recebida',
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

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedore::class);
    }

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
