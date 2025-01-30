<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaBancaria extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Especificando o nome da tabela
    protected $table = 'contas_bancarias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'code',
        'banco_id',
        'active',
        'tipo_banco_id',
        'numero_conta',
        'conta',
        'iban',
        
        'nib',
        'switf',
        'nome_agencia',
        'numero_gestor',
        'nome_titular',
        'morada_titular',
        'local_titular',
        'codigo_postal_titular',
        
        'user_id',
        'loja_id',
        'entidade_id',
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id', 'id');
    }
}
