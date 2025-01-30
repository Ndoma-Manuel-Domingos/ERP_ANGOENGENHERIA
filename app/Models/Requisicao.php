<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisicao extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'requisicoes';

    protected $fillable = [
        'status',
        'numero',
        'user_aprovador_id',
        'loja_id',
        'data_emissao',
        'previsao_entrega',
        'observacao',
        'code',
        'quantidade',
        'user_id',
        'entidade_id',
    ];
    
    
    public function items()
    {
        return $this->hasMany(ItensRequisicao::class, 'requisicao_id' ,'id' );
    }

    public function aprovador()
    {
    return $this->belongsTo(User::class, 'user_aprovador_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }
}
