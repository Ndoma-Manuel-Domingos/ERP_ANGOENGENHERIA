<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedore extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nif',
        'nome',
        'conta',
        'code',
        'morada',
        'codigo_postal',
        'localidade',
        'pais',
        'status',
        'telefone',
        'telemovel',
        'tipo_fornecedor',
        'tipo_pessoa',
        'email',
        'website',
        'observacao',
        'user_id',
        'entidade_id',
    ];
}
