<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimentoContaCliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'montante',
        'observacao',
        'documento',
        'cliente_id',
        'user_id',
        'data_emissao',
        'data_pagamento',
        'tipo_movimento',
        'entidade_id',
    ];

}
