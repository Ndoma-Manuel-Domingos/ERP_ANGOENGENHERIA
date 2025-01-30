<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimentoCaixa extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'caixa_id',
        'status',
        'data_abertura',
        'hora_abertura',
        'valor_abertura',
        'valor_cash',
        'movimento',
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

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }

}


