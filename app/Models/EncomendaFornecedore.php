<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncomendaFornecedore extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'status',
        'status_pagamento',
        'numero',
        'factura',
        'fornecedor_id',
        'loja_id',
        'data_emissao',
        'previsao_entrega',
        'observacao',
        'code',
        'quantidade',
        'quantidade_recebida',
        'custo_transporte',
        'custo_manuseamento',
        'outros_custos',
        'imposto',
        'total_produto',
        'total_sIva',
        'total_cIVa',
        'total',
        'tota_pago',
        'total_a_pagar',
        'desconto',
        'user_id',
        'entidade_id',
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedore::class);
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
