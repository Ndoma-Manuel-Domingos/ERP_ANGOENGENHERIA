<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'operacao',
        'data_operacao',
        'stock_minimo',
        'stock_alerta',
        'stock',
        'observacao',
        'produto_id',
        'loja_id',
        'user_id',
        'entidade_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function lojas()
    {
        return $this->belongsTo(Loja::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id', 'id');
    }

}


