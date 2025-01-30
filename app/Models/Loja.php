<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'status',
        'codigo_postal',
        'morada',
        'localidade',
        'telefone',
        'email',
        'cae',
        'descricao',
        'observacao',
        'user_id',
        'entidade_id',
    ];

    public function estoques()
    {
        return $this->hasOne(Estoque::class);
    }

    public function produtos_estoques()
    {
        return $this->hasMany(Estoque::class);
    }

    public function estoque()
    {
        return $this->belongsTo(Estoque::class, 'id', 'loja_id');
    }

    public function loja_produtos()
    {
        return $this->hasMany(lojaProduto::class);
    }

    public function caixas()
    {
        return $this->hasMany(Caixa::class);
    }

    public function bancos()
    {
        return $this->hasMany(ContaBancaria::class);
    }

}
