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
        'user_open_id',
        'user_close_id',
        'user_id',
        'loja_id',
        'entidade_id',
    ];

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id', 'id');
    }
    
    public function user_open()
    {
        return $this->belongsTo(User::class, 'user_open_id', 'id');
    }

    public function user_close()
    {
        return $this->belongsTo(User::class, 'user_close_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function entidade()
    {
        return $this->belongsTo(Entidade::class, 'entidade_id', 'id');
    }
}
