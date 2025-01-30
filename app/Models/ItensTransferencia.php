<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItensTransferencia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'produto_id',
        'armazem_origem_id',
        'armazem_destino_id',
        'quantidade',
        'quantidade_anterior',
        'status',
        'user_id',
        'data_emissao',
        'entidade_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function armazem_origem()
    {
        return $this->belongsTo(Loja::class, 'armazem_origem_id', 'id');
    }

    public function armazem_destino()
    {
        return $this->belongsTo(Loja::class, 'armazem_destino_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
