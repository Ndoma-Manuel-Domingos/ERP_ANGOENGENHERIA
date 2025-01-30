<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimentoBanco extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    // Especificando o nome da tabela
    protected $table = 'movimento_bancos';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'banco_id',
        'status',
        'data_abertura',
        'hora_abertura',
        'valor_abertura',
        'valor_cash',
        'valor_multicaixa',
        'valor_total',
        'user_fecho',
        'hora_fecho',
        'data_fecho',
        'valor_valor_fecho',
        'valor_entrada',
        'valor_saida',
        'entidade_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function banco()
    {
        return $this->belongsTo(ContaBancaria::class);
    }

}


