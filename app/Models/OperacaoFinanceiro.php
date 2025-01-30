<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperacaoFinanceiro extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'operacoes_financeiras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'motante',
        'subconta_id',
        'cliente_id',
        'fornecedor_id',
        'model_id',
        'type',
        'parcelado',
        'parcelas',
        'status_pagamento',
        'code',
        'descricao',
        'movimento',
        'date_at',
        'exercicio_id',
        'periodo_id',
        'user_id',
        'entidade_id',
    ];

    public function subconta()
    {
        return $this->belongsTo(Subconta::class, 'subconta_id', 'id');
    }

    public function subconta_origem()
    {
        return $this->belongsTo(Subconta::class, 'subconta_origem_id', 'id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedore::class, 'fornecedor_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Subconta::class, 'cliente_id', 'id');
    }

    public function dispesa()
    {
        return $this->belongsTo(Dispesa::class, 'model_id', 'id');
    }

    public function receita()
    {
        return $this->belongsTo(Receita::class, 'model_id', 'id');
    }

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class);
    }
}
